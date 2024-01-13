<?php

namespace App\Filament\Admin\Resources;

use App\Enums\TicketStatus;
use App\Filament\Admin\Resources\UserResource\Pages\CreateUser;
use App\Filament\Admin\Resources\UserResource\Pages\EditUser;
use App\Filament\Admin\Resources\UserResource\Pages\ListUsers;
use App\Filament\Admin\Resources\UserResource\Pages\ViewUser;
use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\Ticket;
use App\Models\User;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
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
use Illuminate\Support\Str;
use Rawilk\FilamentPasswordInput\Password;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $modelLabel = 'user';

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?int $navigationSort = 1;

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
                    ->unique()
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
                    ->dehydrated(fn($state) => filled($state))
                    ->required(fn(string $context): bool => $context === 'create'),
                Select::make('role')
                    ->relationship(
                        'roles',
                        'name',
                        fn(Builder $query) => $query->where('name', '!=', 'contractor')
                    )
                    ->preload()
                    ->searchable()
                    ->default(2)
                    ->columnSpanFull()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('email')
                    ->searchable()
                    ->copyable(),
                TextColumn::make('phone_number')
                    ->searchable()
                    ->copyable(),
                TextColumn::make('roles.name')
                    ->formatStateUsing(fn(string $state): string => ucwords(str_replace('_', ' ', $state)))
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'super_admin' => 'primary',
                        'client'      => 'success',
                        'contractor'  => 'info',
                        default       => 'gray'
                    })
            ])
            ->filters([
                SelectFilter::make('role')
                    ->relationship('roles', 'name', fn(Builder $query) => $query->whereNot('name', '=', 'contractor'))
                    ->multiple()
                    ->preload(),
            ])
            ->actions([
                ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make()
                        ->requiresConfirmation()
                        ->disabled(fn(User $user) => $user->properties->isNotEmpty()),
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
            'index'  => ListUsers::route('/'),
            'create' => CreateUser::route('/create'),
            'view'   => ViewUser::route('/{record}'),
            'edit'   => EditUser::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::notContractor()->count();
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                TextEntry::make('name'),
                TextEntry::make('email'),
                TextEntry::make('phone_number'),
                TextEntry::make('roles.name')
                    ->formatStateUsing(fn(string $state): string => ucwords(str_replace('_', ' ', $state)))
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'super_admin' => 'primary',
                        'client'      => 'success',
                        'contractor'  => 'info',
                        default       => 'gray'
                    }),
                TextEntry::make('created_at')
                    ->label('Added Date')
                    ->placeholder('~'),
            ]);
    }
}
