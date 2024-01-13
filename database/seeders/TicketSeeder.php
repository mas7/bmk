<?php

namespace Database\Seeders;

use App\Enums\ContractorStatus;
use App\Enums\TicketPaymentStatus;
use App\Enums\TicketStatus;
use App\Models\Contractor;
use App\Models\Service;
use App\Models\Ticket;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TicketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (range(1, 4) as $ignored) {
            $user       = User::query()->whereHas('rentalPlans')->inRandomOrder()->limit(1)->first();
            $contractor = Contractor::query()->whereStatus(ContractorStatus::ACTIVE->value)->inRandomOrder()->limit(1)->first();
            $services   = Service::query()->inRandomOrder()->limit(2)->pluck('id');

            $ticket = Ticket::create([
                'user_id'                => $user->id,
                'property_id'            => $user->rentalPlans->first()?->property_id,
                'contractor_id'          => $contractor->user_id,
                'description'            => fake()->sentence(),
                'contractor_description' => null,
                'status'                 => TicketStatus::OPEN,
                'expected_visit_at'      => Carbon::now()->addDays(2),
                'resolution_at'          => null
            ]);

            foreach ($services as $serviceId) {
                $ticket->ticketServices()->create([
                    'service_id' => $serviceId
                ]);
            }

            $ticket->payment()->create([
                'total'  => fake()->randomNumber(3),
                'status' => TicketPaymentStatus::UNPAID,
            ]);
        }
    }
}
