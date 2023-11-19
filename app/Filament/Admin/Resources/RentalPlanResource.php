<?php

namespace App\Filament\Admin\Resources;

use App\Enums\RentalPlanStatus;
use App\Enums\TicketStatus;
use App\Filament\Admin\Resources\RentalPlanResource\Pages;
use App\Filament\Admin\Resources\RentalPlanResource\RelationManagers;
use App\Models\RentalPlan;
use Filament\Forms;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RentalPlanResource extends Resource
{
    protected static ?string $model = RentalPlan::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

    protected static ?string $navigationGroup = 'Properties';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('property_id')
                    ->label('Property')
                    ->relationship(
                        name: 'property',
                        titleAttribute: 'name',
                        modifyQueryUsing: fn(Builder $query) => $query->doesntHaveClient()
                    )
                    ->preload()
                    ->searchable(),
                Select::make('client_id')
                    ->label('Client')
                    ->required()
                    ->relationship(
                        name: 'client',
                        titleAttribute: 'name',
                        modifyQueryUsing: fn(Builder $query) => $query->clients()->doesntHaveProperty()
                    )
                    ->preload()
                    ->searchable(),
                DateTimePicker::make('start_date')
                    ->label('Start Date')
                    ->required()
                    ->native(false),
                DateTimePicker::make('end_date')
                    ->label('End Date')
                    ->required()
                    ->native(false),
                TextInput::make('monthly_rent')
                    ->required()
                    ->numeric()
                    ->minValue(1)
                    ->prefix('QR'),
                Select::make('status')
                    ->required()
                    ->default(RentalPlanStatus::ACTIVE)
                    ->options(RentalPlanStatus::class),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('Number')
                    ->searchable(),
                TextColumn::make("property.name")
                    ->label("Property")
                    ->searchable(),
                TextColumn::make("client.name")
                    ->label("Client")
                    ->searchable(),
                TextColumn::make('monthly_rent')
                    ->prefix('QAR ')
                    ->numeric(decimalPlaces: 0)
                    ->sortable(),
                TextColumn::make("start_date")
                    ->searchable(),
                TextColumn::make("end_date")
                    ->searchable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn(RentalPlanStatus $state): string => match ($state) {
                        RentalPlanStatus::ACTIVE => 'success',
                        RentalPlanStatus::INACTIVE => 'danger',
                        default => 'warning'
                    }),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
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
            'index' => Pages\ListRentalPlans::route('/'),
            'create' => Pages\CreateRentalPlan::route('/create'),
            'edit' => Pages\EditRentalPlan::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}
