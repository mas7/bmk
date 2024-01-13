<?php

namespace App\Filament\Client\Resources;

use App\Enums\PaymentStatus;
use App\Filament\Client\Resources\PaymentResource\Pages;
use App\Filament\Client\Resources\PaymentResource\RelationManagers;
use App\Models\Payment;
use App\Models\Property;
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

class PaymentResource extends Resource
{
    protected static ?string $model = Payment::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    protected static ?string $navigationGroup = 'Properties';

    protected static ?int $navigationSort = 3;

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
                    ->sortable()
                    ->formatStateUsing(fn($state): string => "#$state"),
                TextColumn::make('rentalPlan.property.name')
                    ->label('Property')
                    ->searchable(),
                TextColumn::make("client.name")
                    ->searchable()
                    ->label("Client"),
                TextColumn::make('amount')
                    ->prefix('QAR ')
                    ->numeric(decimalPlaces: 0)
                    ->sortable(),
                TextColumn::make("payment_date")
                    ->searchable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn(PaymentStatus $state): string => match ($state) {
                        PaymentStatus::PAID   => 'success',
                        PaymentStatus::UNPAID => 'danger',
                        default               => 'warning'
                    }),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->multiple()
                    ->options(PaymentStatus::class),
            ])
            ->actions([
                ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                ]),
            ])
            ->bulkActions([

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
            'index'  => Pages\ListPayments::route('/'),
            'create' => Pages\CreatePayment::route('/create'),
            'edit'   => Pages\EditPayment::route('/{record}/edit'),
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
                TextEntry::make('rentalPlan.property.name'),
                TextEntry::make('amount'),
                TextEntry::make('payment_date'),
                TextEntry::make('status')
                    ->badge()
                    ->color(fn(PaymentStatus $state): string => match ($state) {
                        PaymentStatus::PAID   => 'success',
                        PaymentStatus::UNPAID => 'danger',
                        default               => 'warning'
                    }),
            ]);
    }

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }
}
