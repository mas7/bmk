<?php

namespace App\Filament\Admin\Resources;

use App\Enums\PaymentStatus;
use App\Enums\RentalPlanStatus;
use App\Enums\TicketStatus;
use App\Filament\Admin\Resources\PaymentResource\Pages;
use App\Models\Payment;
use App\Models\Property;
use App\Models\RentalPlan;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\Ticket;
use Filament\Forms;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\Layout\Panel;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use pxlrbt\FilamentExcel\Actions\Pages\ExportAction;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use pxlrbt\FilamentExcel\Columns\Column;
use pxlrbt\FilamentExcel\Exports\ExcelExport;

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
                Select::make('client_id')
                    ->label('Client')
                    ->required()
                    ->relationship(
                        name: 'client',
                        titleAttribute: 'name',
                        modifyQueryUsing: fn(Builder $query) => $query->whereHas('rentalPlans', fn($q) => $q->active())
                    )
                    ->preload()
                    ->searchable(),
                Select::make('rental_plan_id')
                    ->label('Rental Plan')
                    ->required()
                    ->reactive()
                    ->relationship(
                        'rentalPlan',
                        'id',
                        fn(Builder $query, Get $get) => $query->where('client_id', $get('client_id'))
                    )
                    ->getOptionLabelFromRecordUsing(fn(RentalPlan $record) => "{$record->property->name}")
                    ->preload()
                    ->searchable()
                    ->afterStateUpdated(function (?string $state, ?string $old, Set $set) {
                        $rentAmount = RentalPlan::find($state)?->monthly_rent ?? 0;
                        $set('amount', $rentAmount);
                    }),
                TextInput::make('amount')
                    ->label('Due Amount')
                    ->required()
                    ->numeric()
                    ->minValue(1)
                    ->prefix('QR'),
                DateTimePicker::make('payment_date')
                    ->label('Payment Date')
                    ->required()
                    ->native(false)
                    ->default(today()),
                Select::make('status')
                    ->required()
                    ->default(PaymentStatus::PAID)
                    ->options(PaymentStatus::class),
                Repeater::make('partials')
                    ->label('Partials')
                    ->reorderable(false)
                    ->schema([
                        Grid::make([
                            'sm'  => 1,
                            'md'  => 1,
                            'lg'  => 2,
                            'xl'  => 2,
                            '2xl' => 2,
                        ])
                            ->schema([
                                TextInput::make('amount')
                                    ->required()
                                    ->numeric()
                                    ->minValue(1)
                                    ->prefix('QR'),
                                DateTimePicker::make('payment_date')
                                    ->label('Payment Date')
                                    ->required()
                                    ->native(false),
                            ])
                    ])
                    ->columnSpanFull()
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
                    ->label('Due Amount')
                    ->prefix('QAR ')
                    ->numeric(decimalPlaces: 0)
                    ->sortable(),
                TextColumn::make('paid_amount')
                    ->label('Paid Amount')
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
                    })
            ])
            ->filters([
                SelectFilter::make('status')
                    ->multiple()
                    ->options(PaymentStatus::class),
            ])
            ->actions([
                ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make()
                        ->requiresConfirmation()
                        ->disabled(fn(Payment $payment) => $payment->status === PaymentStatus::PAID),
                ]),
            ])
            ->bulkActions([
                ExportBulkAction::make()->exports([
                    ExcelExport::make('table')
                        ->askForFilename()
                        ->fromTable()
                        ->withColumns([
                            Column::make('status')
                                ->formatStateUsing(fn(PaymentStatus $state): string => match ($state) {
                                    PaymentStatus::PAID   => PaymentStatus::PAID->name,
                                    PaymentStatus::UNPAID => PaymentStatus::UNPAID->name,
                                    default               => '~'
                                }),
                        ]),
                ])
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
        return static::getModel()::count();
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                TextEntry::make('id'),
                TextEntry::make('rentalPlan.property.name'),
                TextEntry::make('client.name'),
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
