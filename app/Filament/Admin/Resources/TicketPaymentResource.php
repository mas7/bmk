<?php

namespace App\Filament\Admin\Resources;

use App\Enums\TicketPaymentStatus;
use App\Filament\Admin\Resources\TicketPaymentResource\Pages;
use App\Filament\Admin\Resources\TicketPaymentResource\RelationManagers;
use App\Models\TicketPayment;
use Carbon\Carbon;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Malzariey\FilamentDaterangepickerFilter\Filters\DateRangeFilter;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use pxlrbt\FilamentExcel\Columns\Column;
use pxlrbt\FilamentExcel\Exports\ExcelExport;
use Filament\Tables\Columns\Summarizers\Sum;

class TicketPaymentResource extends Resource
{
    protected static ?string $model = TicketPayment::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Ticket Management';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //Select::make('ticket_id')
                //    ->label('Ticket')
                //    ->required()
                //    ->relationship(
                //        name: 'ticket',
                //        titleAttribute: 'id',
                //    )
                //    ->preload()
                //    ->searchable()
                //    ->native(false),
                TextInput::make('total')
                    ->required()
                    ->numeric()
                    ->minValue(1)
                    ->prefix('QR'),
                Select::make('status')
                    ->required()
                    ->default(TicketPaymentStatus::UNPAID)
                    ->options(TicketPaymentStatus::class)
                    ->native(false),
            ]);
    }

    /**
     * @throws \Exception
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('Number')
                    ->sortable()
                    ->formatStateUsing(fn($state): string => "#$state"),
                TextColumn::make("ticket.id")
                    ->label('Ticket ID')
                    ->formatStateUsing(fn($state): string => "#$state")
                    ->searchable(),
                TextColumn::make("ticket.contractor.name")
                    ->label('Contractor')
                    ->searchable(),
                TextColumn::make('total')
                    ->label('Total')
                    ->prefix('QAR ')
                    ->numeric(decimalPlaces: 0)
                    ->sortable()
                    ->summarize(Sum::make()
                        ->label('Total')
                        ->money('QAR')
                    ),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn(TicketPaymentStatus $state): string => match ($state) {
                        TicketPaymentStatus::PAID   => 'success',
                        TicketPaymentStatus::UNPAID => 'danger',
                    }),
                TextColumn::make("created_at")
                    ->searchable()
                    ->sortable()
                    ->label("Created At")
                    ->placeholder('~'),
            ])
            ->filters([
                DateRangeFilter::make('created_at')
                    ->label('Created At')
                    ->startDate(Carbon::now())
                    ->setTimePickerOption(false)
                    ->setAutoApplyOption(true)
                    ->setLinkedCalendarsOption(false)
                    ->withIndicator()
                    ->useRangeLabels(),
            ])
            ->actions([
                ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make()
                        ->requiresConfirmation()
                        ->disabled(fn(TicketPayment $payment) => $payment->status === TicketPaymentStatus::PAID),
                ]),
            ])
            ->bulkActions([
                ExportBulkAction::make()->exports([
                    ExcelExport::make('table')
                        ->askForFilename()
                        ->fromTable()
                        ->withColumns([
                            Column::make('status')
                                ->formatStateUsing(fn(TicketPaymentStatus $state): string => match ($state) {
                                    TicketPaymentStatus::PAID   => TicketPaymentStatus::PAID->name,
                                    TicketPaymentStatus::UNPAID => TicketPaymentStatus::UNPAID->name,
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
            'index'  => Pages\ListTicketPayments::route('/'),
            'create' => Pages\CreateTicketPayment::route('/create'),
            'edit'   => Pages\EditTicketPayment::route('/{record}/edit'),
        ];
    }
}
