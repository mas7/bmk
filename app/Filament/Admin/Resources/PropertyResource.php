<?php

namespace App\Filament\Admin\Resources;

use App\Enums\ContractorStatus;
use App\Filament\Resources\PropertyResource\Pages;
use App\Filament\Resources\PropertyResource\RelationManagers;
use App\Models\Property;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PropertyResource extends Resource
{
    protected static ?string $model = Property::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';

    protected static ?string $navigationGroup = 'Properties';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required()
                    ->autofocus()
                    ->maxLength(255),
                TextInput::make('location')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('location')
                    ->searchable(),
                TextColumn::make('rent_amount')
                    ->placeholder('~')
                    ->prefix('QAR ')
                    ->numeric(
                        decimalPlaces: 0,
                    )
                    ->sortable(),
                TextColumn::make('client.name')
                    ->badge()
                    ->placeholder('~')
                    ->searchable()
                    ->color(fn(string $state): string => 'warning')
            ])
            ->filters([
                //
            ])
            ->actions([
                ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make()
                        ->requiresConfirmation()
                        ->disabled(fn(Property $property) => $property->hasClient),
                ]),
            ])
            ->bulkActions([
                //Tables\Actions\BulkActionGroup::make([
                //    Tables\Actions\DeleteBulkAction::make(),
                //]),
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
            'index' => \App\Filament\Admin\Resources\PropertyResource\Pages\ListProperties::route('/'),
            'create' => \App\Filament\Admin\Resources\PropertyResource\Pages\CreateProperty::route('/create'),
            'edit' => \App\Filament\Admin\Resources\PropertyResource\Pages\EditProperty::route('/{record}/edit'),
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
                TextEntry::make('name'),
                TextEntry::make('location'),
                TextEntry::make('rent_amount')
                    ->placeholder('~')
                    ->prefix('QAR ')
                    ->numeric(
                        decimalPlaces: 0,
                    ),
                TextEntry::make('client.name')
                    ->label('Client Name')
                    ->placeholder('~')
                    ->badge()
                    ->color(fn(string $state): string => 'warning'),
            ]);
    }
}
