<?php

namespace App\Filament\Client\Resources;

use App\Enums\TicketStatus;
use App\Filament\Client\Resources\TicketResource\Pages;
use App\Filament\Client\Resources\TicketResource\RelationManagers;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\Ticket;
use Filament\Forms;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Form;
use Filament\Forms\Get;
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

class TicketResource extends Resource
{
    protected static ?string $model = Ticket::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('property_id')
                    ->label('Property')
                    ->relationship(
                        name: 'property',
                        titleAttribute: 'name',
                        modifyQueryUsing: fn(Builder $query, Get $get) => $query->where('client_id', auth()->id())
                    )
                    ->preload()
                    ->searchable(),
                Select::make('service_ids')
                    ->label('Services (Multiple)')
                    ->required()
                    ->multiple()
                    ->searchable()
                    ->getOptionLabelUsing(fn($value): ?string => Service::find($value)?->name)
                    ->native(false)
                    ->minItems(1)
                    ->options(
                        function () {
                            return ServiceCategory::query()
                                ->with(['services'])
                                ->get()
                                ->mapWithKeys(fn($category) => [$category->name => $category->services->pluck('name', 'id')])
                                ->toArray();
                        }
                    ),
                Textarea::make('description')
                    ->rows(4)
                    ->placeholder('Write a small brief about the issue...')
                    ->columnSpanFull()
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
                TextColumn::make("property.name")
                    ->searchable()
                    ->label("Property"),
                TextColumn::make('ticketServices.service.name')
                    ->label('Services')
                    ->badge()
                    ->color(fn(string $state): string => 'primary'),
                TextColumn::make("contractor.name")
                    ->label("Contractor")
                    ->searchable()
                    ->placeholder('Not assigned'),
                TextColumn::make("expected_visit_at")
                    ->label("Expected Visit Date")
                    ->placeholder('~'),
                TextColumn::make("resolution_at")
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
                    ->label("Created"),
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
            ->bulkActions([])
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
        return static::getModel()::where('user_id', auth()->id())->count();
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
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
                    ->placeholder('~'),
                ImageEntry::make('signature.path')
                    ->disk('public')
                    ->columnSpanFull()
                    ->width('40rem')
                    ->placeholder('~'),
            ]);
    }

}
