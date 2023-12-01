<?php

namespace App\Livewire\Payments;

use App\Enums\PaymentStatus;
use App\Models\Payment;
use App\Models\User;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Livewire\Component;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use pxlrbt\FilamentExcel\Columns\Column;
use pxlrbt\FilamentExcel\Exports\ExcelExport;

class ListPayments extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    public User $user;


    public function table(Table $table): Table
    {
        return $table
            ->query(
                Payment::query()
                    ->parents()
                    ->when(isset($this->user),
                        function ($q) {
                            return $q->where('client_id', $this->user->id);
                        },
                    ),
            )
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
                //ActionGroup::make([
                //    Tables\Actions\ViewAction::make(),
                //    Tables\Actions\EditAction::make(),
                //    Tables\Actions\DeleteAction::make()
                //        ->requiresConfirmation()
                //        ->disabled(fn(Payment $payment) => $payment->status === PaymentStatus::PAID),
                //]),
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
            ->defaultSort('id', 'desc');
    }

    public function render(): View
    {
        return view('livewire.payments.list-payments');
    }
}
