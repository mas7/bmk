<?php

namespace App\Filament\Resources;

use App\Enums\ContractorStatus;
use App\Filament\Resources\ContractorResource\Pages;
use App\Filament\Resources\ContractorResource\RelationManagers;
use App\Models\Contractor;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Rawilk\FilamentPasswordInput\Password;
use Illuminate\Support\Str;

class ContractorResource extends Resource
{
    protected static ?string $model = Contractor::class;

    protected static ?string $navigationIcon = 'heroicon-o-wrench-screwdriver';

    protected static ?string $navigationGroup = 'Contractors';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required()
                    ->autofocus()
                    ->maxLength(255),
                TextInput::make('email')
                    ->required()
                    ->email()
                    ->maxLength(255),
                TextInput::make('phone_number')
                    ->required()
                    ->tel(),
                Password::make('password')
                    ->label('Password')
                    ->copyable()
                    ->copyMessage('Copied')
                    ->regeneratePassword()
                    ->generatePasswordUsing(function ($state) {
                        return Str::password(8);
                    })
                    ->dehydrated(fn ($state) => filled($state))
                    ->required(fn (string $context): bool => $context === 'create'),
                Select::make('service_category')
                    ->required()
                    ->relationship('serviceCategory', 'name')
                    ->preload()
                    ->searchable(),
                Select::make('status')
                    ->required()
                    ->options(ContractorStatus::class)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('Name')
                    ->searchable(),
                TextColumn::make('user.email')
                    ->label('Email')
                    ->searchable()
                    ->copyable()
                    ->copyMessage('Email address copied')
                    ->copyMessageDuration(1500),
                TextColumn::make('user.phone_number')
                    ->label('Phone number')
                    ->searchable(),
                TextColumn::make('serviceCategory.name')
                    ->label('Service')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        default => 'warning'
                    }),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (ContractorStatus $state): string => match ($state) {
                        ContractorStatus::ACTIVE    => 'success',
                        ContractorStatus::INACTIVE  => 'danger',
                        default => 'warning'
                    })
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
            'index' => Pages\ListContractors::route('/'),
            'create' => Pages\CreateContractor::route('/create'),
            'edit' => Pages\EditContractor::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}
