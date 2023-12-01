<?php

namespace Database\Seeders;

use App\Models\Contractor;
use App\Models\ContractorService;
use App\Models\Service;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ContractorServiceSeeder extends Seeder
{
    public function run(): void
    {
        Contractor::each(function (Contractor $contractor) {
            $recordCount = rand(1, 3);
            Service::inRandomOrder()->get()->take($recordCount)->each(function (Service $service) use ($contractor) {
                ContractorService::create([
                    'contractor_id'       => $contractor->id,
                    'service_id'          => $service->id,
                    'service_category_id' => $service->service_category_id,
                    'price'               => $service->price,
                ]);
            });
        });
    }
}
