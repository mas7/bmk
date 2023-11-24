<?php

namespace Database\Seeders;

use App\Enums\PaymentStatus;
use App\Models\Payment;
use App\Models\RentalPlan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rentalPlan = RentalPlan::first();

        $initialRentDate = $rentalPlan->start_date;

        while ($initialRentDate->lt(now())) {
            Payment::create([
                'client_id'      => $rentalPlan->client_id,
                'rental_plan_id' => $rentalPlan->id,
                'amount'         => $rentalPlan->monthly_rent,
                'payment_date'   => $initialRentDate,
                'status'         => PaymentStatus::PAID,
            ]);

            $initialRentDate = $initialRentDate->addMonth();
        }

        Payment::create([
            'client_id'      => $rentalPlan->client_id,
            'rental_plan_id' => $rentalPlan->id,
            'amount'         => $rentalPlan->monthly_rent,
            'payment_date'   => $initialRentDate,
            'status'         => PaymentStatus::UNPAID,
        ]);

    }
}
