<?php

namespace App\Filament\Admin\Resources;

use App\Enums\TicketStatus;
use App\Filament\Resources\TicketResource\Pages;
use App\Filament\Resources\TicketResource\RelationManagers;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\Ticket;
use Carbon\Carbon;
use Filament\Tables\Actions\ActionGroup;
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
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class TicketResource extends Resource
{
    protected static ?string $model = Ticket::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('user_id')
                    ->label('Client')
                    ->required()
                    ->relationship(
                        name: 'client',
                        titleAttribute: 'name',
                        modifyQueryUsing: fn(Builder $query) => $query->clients()->hasProperty()
                    )
                    ->preload()
                    ->searchable(['name', 'phone_number', 'email'])
                    ->native(false),
                Select::make('property_id')
                    ->label('Property')
                    ->relationship(
                        name: 'property',
                        titleAttribute: 'name',
                        modifyQueryUsing: fn(Builder $query, Get $get) => $query->where('client_id', $get('user_id'))
                    )
                    ->preload()
                    ->searchable()
                    ->native(false),
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
                Select::make('contractor_id')
                    ->label('Contractor')
                    ->required()
                    ->relationship(
                        name: 'contractor',
                        titleAttribute: 'name',
                        modifyQueryUsing: fn(Builder $query, Get $get) => $query->contractors(serviceCategoryId: $get('service_category_id'))
                    )
                    ->preload()
                    ->searchable()
                    ->native(false),
                Select::make('status')
                    ->required()
                    ->default(TicketStatus::OPEN)
                    ->options(TicketStatus::class)
                    ->native(false),
                DateTimePicker::make('expected_visit_at')
                    ->label('Expected visit date')
                    ->required()
                    ->timezone('Asia/Qatar')
                    ->native(false),
                Textarea::make('description')
                    ->rows(4)
                    ->placeholder('Write a small brief about the issue...')
                    ->columnSpanFull(),

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
                TextColumn::make("contractor.name")
                    ->label("Contractor")
                    ->searchable()
                    ->placeholder('Not assigned'),
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
                    ->multiple()
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
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make()
                        ->requiresConfirmation()
                        ->disabled(fn(Ticket $ticket) => $ticket->status === TicketStatus::RESOLVED),
                ]),
            ])
            ->bulkActions([
            ])
            ->poll()
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
            'index'  => \App\Filament\Admin\Resources\TicketResource\Pages\ListTickets::route('/'),
            'create' => \App\Filament\Admin\Resources\TicketResource\Pages\CreateTicket::route('/create'),
            'edit'   => \App\Filament\Admin\Resources\TicketResource\Pages\EditTicket::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
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
                    ->placeholder('~'),
                ImageEntry::make('signature.path')
                    ->disk('public')
                    ->columnSpanFull()
                    ->width('40rem')
                    ->placeholder('~'),
            ]);
    }

}
