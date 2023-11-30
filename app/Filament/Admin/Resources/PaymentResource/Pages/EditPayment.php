<?php

namespace App\Filament\Admin\Resources\PaymentResource\Pages;

use App\Enums\PaymentStatus;
use App\Filament\Admin\Resources\PaymentResource;
use App\Models\ContractorService;
use App\Models\Payment;
use App\Models\User;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditPayment extends EditRecord
{
    protected static string $resource = PaymentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        /** @var Payment $payment */
        $payment = $this->getRecord();

        $data = $payment->toArray();

        $partials = $payment->partials;

        $partials->each(function (Payment $payment) use (&$data) {
            $data['partials'][] = [
                'amount'       => $payment->paid_amount,
                'payment_date' => $payment->payment_date,
            ];
        });

        return $data;
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $record->update([
            'client_id'      => data_get($data, 'client_id'),
            'rental_plan_id' => data_get($data, 'rental_plan_id'),
            'amount'         => data_get($data, 'amount'),
            'paid_amount'    => 0,
            'payment_date'   => data_get($data, 'payment_date'),
            'status'         => data_get($data, 'status'),
        ]);

        $partials = data_get($data, 'partials');

        if (isset($partials) && count($partials) > 0) {
            $record->partials()->delete();

            foreach ($partials as $partial) {
                $partial = $record->partials()->create([
                    'client_id'      => data_get($data, 'client_id'),
                    'rental_plan_id' => data_get($data, 'rental_plan_id'),
                    'amount'         => data_get($partial, 'amount'),
                    'paid_amount'    => data_get($partial, 'amount'),
                    'payment_date'   => data_get($data, 'payment_date'),
                    'status'         => PaymentStatus::PAID,
                ]);

                $record->update([
                    'paid_amount' => $record->refresh()->paid_amount + $partial->paid_amount,
                ]);

                if ($record->paid_amount >= $record->amount) {
                    $record->update([
                        'status' => PaymentStatus::PAID,
                    ]);
                }

                if ($record->paid_amount <= $record->amount) {
                    $record->update([
                        'status' => PaymentStatus::PARTIAL,
                    ]);
                }
            }
        }

        if ($record->paid_amount === 0) {
            $record->update([
                'status' => PaymentStatus::UNPAID,
            ]);
        }

        return $record;
    }
}
