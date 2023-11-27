<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\ServiceResource\Pages;
use App\Filament\Admin\Resources\ServiceResource\RelationManagers;
use App\Models\Service;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
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

class ServiceResource extends Resource
{
    protected static ?string $model = Service::class;

    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';

    protected static ?string $navigationGroup = 'Contractors';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('service_category_id')
                    ->label('Category')
                    ->required()
                    ->relationship(
                        'category',
                        'name',
                    )
                    ->preload()
                    ->searchable(),
                TextInput::make('name')
                    ->required()
                    ->placeholder('Ex: Fix cooling, cleaning air condition...')
                    ->maxLength(255),
                TextInput::make('price')
                    ->required()
                    ->numeric()
                    ->minValue(1)
                    ->prefix('QR')
                    ->placeholder('500')
                    ->columnSpanFull(),
                Textarea::make('description')
                    ->rows(2)
                    ->placeholder('Write a small brief about the service...')
                    ->columnSpanFull()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make("name")
                    ->searchable(),
                TextColumn::make("category.name")
                    ->searchable(),
                TextColumn::make('price')
                    ->prefix('QAR ')
                    ->numeric(decimalPlaces: 0)
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('category')
                    ->multiple()
                    ->relationship('category', 'name')
                    ->preload()
            ])
            ->actions([
                ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make()
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
            'index'  => Pages\ListServices::route('/'),
            'create' => Pages\CreateService::route('/create'),
            'edit'   => Pages\EditService::route('/{record}/edit'),
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
                TextEntry::make('id'),
                TextEntry::make('name'),
                TextEntry::make('category.name'),
                TextEntry::make('price')
                    ->prefix('QAR ')
                    ->numeric(decimalPlaces: 0),
                TextEntry::make('description')
                    ->placeholder('~'),
            ]);
    }
}
