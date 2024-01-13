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
        Contractor::query()->each(function (Contractor $contractor) {
            if ($contractor->user->name == 'Default') {
                Service::query()->get()->each(function (Service $service) use ($contractor) {
                    ContractorService::query()->create([
                        'contractor_id'       => $contractor->id,
                        'service_id'          => $service->id,
                        'service_category_id' => $service->service_category_id,
                        'price'               => $service->price,
                    ]);
                });
            }

            $recordCount = rand(1, 3);
            Service::query()->inRandomOrder()->get()->take($recordCount)->each(function (Service $service) use ($contractor) {
                ContractorService::query()->create([
                    'contractor_id'       => $contractor->id,
                    'service_id'          => $service->id,
                    'service_category_id' => $service->service_category_id,
                    'price'               => $service->price,
                ]);
            });
        });
    }
}
