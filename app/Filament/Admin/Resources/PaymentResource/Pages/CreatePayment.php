<?php

namespace App\Filament\Admin\Resources\PaymentResource\Pages;

use App\Enums\PaymentStatus;
use App\Filament\Admin\Resources\PaymentResource;
use App\Models\Payment;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreatePayment extends CreateRecord
{
    protected static string $resource = PaymentResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        /** @var Payment $payment */
        $payment = Payment::create([
            'client_id'      => data_get($data, 'client_id'),
            'rental_plan_id' => data_get($data, 'rental_plan_id'),
            'amount'         => data_get($data, 'amount'),
            'paid_amount'    => 0,
            'payment_date'   => data_get($data, 'payment_date'),
            'status'         => data_get($data, 'status'),
        ]);

        $partials = data_get($data, 'partials');

        if (isset($partials) && count($partials) > 0) {
            foreach ($partials as $partial) {
                $partial = $payment->partials()->create([
                    'client_id'      => data_get($data, 'client_id'),
                    'rental_plan_id' => data_get($data, 'rental_plan_id'),
                    'amount'         => data_get($partial, 'amount'),
                    'paid_amount'    => data_get($partial, 'amount'),
                    'payment_date'   => data_get($data, 'payment_date'),
                    'status'         => PaymentStatus::PAID,
                ]);

                $payment->update([
                    'paid_amount' => $payment->refresh()->paid_amount + $partial->paid_amount,
                ]);

                if ($payment->paid_amount >= $payment->amount) {
                    $payment->update([
                        'status' => PaymentStatus::PAID,
                    ]);
                }

                if ($payment->paid_amount <= $payment->amount) {
                    $payment->update([
                        'status' => PaymentStatus::PARTIAL,
                    ]);
                }
            }
        }

        if ($payment->paid_amount === 0) {
            $payment->update([
                'status' => PaymentStatus::UNPAID,
            ]);
        }

        return $payment;
    }
}
