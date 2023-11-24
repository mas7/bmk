<?php

namespace App\Filament\Client\Resources;

use App\Enums\RentalPlanStatus;
use App\Filament\Client\Resources\RentalPlanResource\Pages;
use App\Filament\Client\Resources\RentalPlanResource\RelationManagers;
use App\Models\RentalPlan;
use Filament\Forms;
use Filament\Forms\Form;
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

class RentalPlanResource extends Resource
{
    protected static ?string $model = RentalPlan::class;


    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

    protected static ?string $navigationGroup = 'Properties';

    protected static ?int $navigationSort = 2;


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('Number')
                    ->sortable(),
                TextColumn::make("property.name")
                    ->label("Property")
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
                        RentalPlanStatus::ACTIVE   => 'success',
                        RentalPlanStatus::INACTIVE => 'danger'
                    }),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->multiple()
                    ->options(RentalPlanStatus::class),
            ])
            ->actions([
                ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                ]),
            ])
            ->bulkActions([
            ])
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
            'index'  => Pages\ListRentalPlans::route('/'),
            'create' => Pages\CreateRentalPlan::route('/create'),
            'edit'   => Pages\EditRentalPlan::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('client_id', auth()->id())->count();
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                TextEntry::make('id'),
                TextEntry::make('property.name'),
                TextEntry::make('monthly_rent'),
                TextEntry::make('start_date'),
                TextEntry::make('end_date'),
                TextEntry::make('status')
                    ->badge()
                    ->color(fn(RentalPlanStatus $state): string => match ($state) {
                        RentalPlanStatus::ACTIVE   => 'success',
                        RentalPlanStatus::INACTIVE => 'danger',
                    }),
            ]);
    }
}
