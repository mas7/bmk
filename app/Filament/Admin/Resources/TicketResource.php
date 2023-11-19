<?php

namespace App\Filament\Admin\Resources;

use App\Enums\TicketStatus;
use App\Filament\Resources\TicketResource\Pages;
use App\Filament\Resources\TicketResource\RelationManagers;
use App\Models\Ticket;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Form;
use Filament\Forms\Get;
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
                    ->searchable(),
                Select::make('property_id')
                    ->label('Property')
                    ->relationship(
                        name: 'property',
                        titleAttribute: 'name',
                        modifyQueryUsing: fn(Builder $query, Get $get) => $query->where('client_id', $get('user_id'))
                    )
                    ->preload()
                    ->searchable(),
                Select::make('service_category_id')
                    ->label('Service category')
                    ->required()
                    ->relationship('serviceCategory', 'name')
                    ->preload()
                    ->searchable(),
                Select::make('contractor_id')
                    ->label('Contractor')
                    ->required()
                    ->relationship(
                        name: 'contractor',
                        titleAttribute: 'name',
                        modifyQueryUsing: fn(Builder $query, Get $get) => $query->contractors(serviceCategoryId: $get('service_category_id'))
                    )
                    ->preload()
                    ->searchable(),
                Select::make('status')
                    ->required()
                    ->default(TicketStatus::OPEN)
                    ->options(TicketStatus::class),
                DateTimePicker::make('expected_visit_at')
                    ->label('Expected visit date')
                    ->required(),
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
                    ->formatStateUsing(fn($state): string => "#$state"),
                TextColumn::make("client.name")
                    ->searchable()
                    ->label("Client"),
                TextColumn::make("property.name")
                    ->searchable()
                    ->label("Property"),
                TextColumn::make("serviceCategory.name")
                    ->label("Service"),
                TextColumn::make("contractor.name")
                    ->label("Contractor")
                    ->searchable()
                    ->placeholder('Not assigned'),
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
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('id', 'desc');
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
            'index' => \App\Filament\Admin\Resources\TicketResource\Pages\ListTickets::route('/'),
            'create' => \App\Filament\Admin\Resources\TicketResource\Pages\CreateTicket::route('/create'),
            'edit' => \App\Filament\Admin\Resources\TicketResource\Pages\EditTicket::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}
