<?php

namespace App\Filament\Contractor\Resources;

use App\Enums\TicketStatus;
use App\Filament\Contractor\Resources\TicketResource\Pages;
use App\Filament\Contractor\Resources\TicketResource\RelationManagers;
use App\Models\Ticket;
use Filament\Forms;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
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
                    ->columnSpanFull(),
                FileUpload::make('Images')
                    //->required()
                    ->columnSpanFull()
                    ->multiple()
                    ->image()
                    ->imageEditor()
                    ->reorderable()
                    ->appendFiles()
                    ->previewable(true),
                SignaturePad::make('signature')
                    ->label('Client Signature')
                    //->required()
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
                    ->formatStateUsing(fn($state): string => "#$state"),
                TextColumn::make("client.name")
                    ->searchable()
                    ->label("Client"),
                TextColumn::make("property.name")
                    ->searchable()
                    ->label("Property"),
                TextColumn::make("serviceCategory.name")
                    ->label("Service"),
                TextColumn::make("expected_visit_at")
                    ->label("Visit Date")
                    ->placeholder('~'),
                TextColumn::make("resolution_at")
                    ->label("Resolution Date")
                    ->placeholder('~'),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn(TicketStatus $state): string => match ($state) {
                        TicketStatus::OPEN => 'info',
                        TicketStatus::RESOLVED => 'success',
                        TicketStatus::POSTPONED => 'danger',
                        default => 'warning'
                    }),
                TextColumn::make("created_at")
                    ->label("Created"),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options(TicketStatus::class),
                SelectFilter::make('service')
                    ->relationship('serviceCategory', 'name')
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make()
                    ->visible(fn(Ticket $ticket) => $ticket->status !== TicketStatus::RESOLVED),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
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
            'index' => Pages\ListTickets::route('/'),
            'create' => Pages\CreateTicket::route('/create'),
            'edit' => Pages\EditTicket::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('contractor_id', auth()->id())->count();
    }
}
