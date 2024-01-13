<?php

namespace App\Livewire\TicketPayments;

use App\Enums\TicketPaymentStatus;
use App\Filament\Admin\Resources\TicketPaymentResource\Pages\EditTicketPayment;
use App\Models\Contractor;
use App\Models\TicketPayment;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Livewire\Component;
use Illuminate\Contracts\View\View;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use pxlrbt\FilamentExcel\Columns\Column;
use pxlrbt\FilamentExcel\Exports\ExcelExport;

class ListTicketPayments extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    public Contractor $contractor;


    public function table(Table $table): Table
    {
        return $table
            ->query(
                TicketPayment::query()
                    ->whereHas('ticket.contractor', fn($q) => $q->where('id', $this->contractor->user_id))
            )
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
                SelectFilter::make('status')
                    ->multiple()
                    ->options(TicketPaymentStatus::class),
            ])
            ->actions([
                //ActionGroup::make([
                //    Tables\Actions\EditAction::make(),
                //    Tables\Actions\DeleteAction::make()
                //        ->requiresConfirmation()
                //        ->disabled(fn(TicketPayment $payment) => $payment->status === TicketPaymentStatus::PAID),
                //]),
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
            ->defaultSort('id', 'desc');
    }

    //public static function getPages(): array
    //{
    //    return [
    //        'edit' => EditTicketPayment::route('/{record}/edit'),
    //    ];
    //}

    public function render(): View
    {
        return view('livewire.payments.list-payments');
    }
}
