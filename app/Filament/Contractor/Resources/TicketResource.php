<?php

namespace App\Filament\Contractor\Resources;

use App\Enums\TicketStatus;
use App\Filament\Contractor\Resources\TicketResource\Pages;
use App\Filament\Contractor\Resources\TicketResource\RelationManagers;
use App\Models\Ticket;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Saade\FilamentAutograph\Forms\Components\Enums\DownloadableFormat;
use Saade\FilamentAutograph\Forms\Components\SignaturePad;

class TicketResource extends Resource
{
    protected static ?string $model = Ticket::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                DateTimePicker::make('expected_visit_at')
                    ->label('Expected visit date')
                    ->required()
                    ->columnSpanFull()
                    ->native(false),
                Textarea::make('contractor_description')
                    ->rows(4)
                    ->placeholder('Write a small brief about the issue...')
                    ->columnSpanFull(),
                FileUpload::make('images')
                    ->disk('public')
                    ->directory(fn(Ticket $record) => "images/{$record->id}")
                    ->columnSpanFull()
                    ->multiple()
                    ->image()
                    ->imageEditor()
                    ->reorderable()
                    ->appendFiles()
                    ->previewable(true),
                SignaturePad::make('signature')
                    ->label('Client Signature')
                    ->confirmable()
                    ->columnSpanFull()
                    ->velocityFilterWeight(0.7)
                    ->backgroundColor('#fff')
                    ->backgroundColorOnDark('#f0a')
                    ->downloadable()
                    ->downloadableFormats([
                        DownloadableFormat::PNG
                    ])
                    ->downloadActionDropdownPlacement('center-end'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('Number')
                    ->searchable()
                    ->sortable()
                    ->formatStateUsing(fn($state): string => "#$state"),
                TextColumn::make("client.name")
                    ->searchable()
                    ->label("Client"),
                TextColumn::make("property.name")
                    ->searchable()
                    ->label("Property"),
                TextColumn::make('ticketServices.service.name')
                    ->label('Services')
                    ->badge()
                    ->color(fn(string $state): string => 'primary'),
                TextColumn::make("expected_visit_at")
                    ->searchable()
                    ->sortable()
                    ->label("Expected Visit Date")
                    ->placeholder('~'),
                TextColumn::make("resolution_at")
                    ->searchable()
                    ->sortable()
                    ->label("Resolution Date")
                    ->placeholder('~'),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn(TicketStatus $state): string => match ($state) {
                        TicketStatus::OPEN      => 'info',
                        TicketStatus::RESOLVED  => 'success',
                        TicketStatus::POSTPONED => 'danger',
                        default                 => 'warning'
                    }),
                TextColumn::make("created_at")
                    ->searchable()
                    ->sortable()
                    ->label("Created")
                    ->formatStateUsing(fn($state): string => Carbon::parse($state)->diffForHumans()),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options(TicketStatus::class),
                SelectFilter::make('service')
                    ->relationship('ticketServices.service', 'name')
                    ->native(false)
                    ->multiple()
                    ->preload()
            ])
            ->actions([
                ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make()
                        ->disabled(fn(Ticket $ticket) => $ticket->status === TicketStatus::RESOLVED),
                ]),
            ])
            ->bulkActions([
                //Tables\Actions\BulkActionGroup::make([
                //    Tables\Actions\DeleteBulkAction::make(),
                //]),
            ])
            ->poll('10s')
            ->defaultSort('id', 'desc')
            ->recordUrl(null);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListTickets::route('/'),
            'create' => Pages\CreateTicket::route('/create'),
            'edit'   => Pages\EditTicket::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('contractor_id', auth()->id())->count();
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                TextEntry::make('client.name'),
                TextEntry::make('property.name'),
                TextEntry::make('status')
                    ->badge()
                    ->color(fn(TicketStatus $state): string => match ($state) {
                        TicketStatus::OPEN      => 'info',
                        TicketStatus::RESOLVED  => 'success',
                        TicketStatus::POSTPONED => 'danger',
                        default                 => 'warning'
                    }),
                TextEntry::make('ticketServices.service.name')
                    ->label('Services')
                    ->badge()
                    ->color(fn(string $state): string => 'primary'),
                TextEntry::make('expected_visit_at')
                    ->label('Expected Visit Date')
                    ->placeholder('~'),
                TextEntry::make('resolution_at')
                    ->label('Resolution Date')
                    ->placeholder('~'),
                TextEntry::make('description')
                    ->placeholder('~')
                    ->columnSpanFull(),
                TextEntry::make('contractor_description')
                    ->placeholder('~')
                    ->columnSpanFull(),
                ImageEntry::make('images.path')
                    ->disk('public')
                    ->size(200)
                    ->grow(true)
                    ->placeholder('~'),
                ImageEntry::make('signature.path')
                    ->disk('public')
                    ->columnSpanFull()
                    ->width('40rem')
                    ->placeholder('~'),
            ]);
    }
}
