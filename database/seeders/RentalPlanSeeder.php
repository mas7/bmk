<?php

namespace Database\Seeders;

use App\Enums\RentalPlanStatus;
use App\Models\Property;
use App\Models\RentalPlan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RentalPlanSeeder extends Seeder
{
    public function run(): void
    {
        $property = Property::first();
        $client   = User::clients()->first();

        RentalPlan::create([
            'property_id'  => $property->id,
            'client_id'    => $client->id,
            'start_date'   => Carbon::now()->subMonths(6),
            'end_date'     => Carbon::now()->addMonths(6),
            'monthly_rent' => 12000,
            'status'       => RentalPlanStatus::ACTIVE
        ]);

        $property->update([
            'rent_amount' => 12000,
            'client_id'   => $client->id,
        ]);
    }
}
