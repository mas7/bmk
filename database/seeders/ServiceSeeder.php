<?php

namespace Database\Seeders;

use App\Models\Service;
use App\Models\ServiceCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        ServiceCategory::each(function (ServiceCategory $category) {
            foreach (range(1, rand(1, 3)) as $value) {
                Service::create([
                    'service_category_id' => $category->id,
                    'name'                => fake()->name(),
                    'description'         => fake()->sentence(),
                    'price'               => fake()->randomNumber(3),
                ]);
            }
        });
    }
}
