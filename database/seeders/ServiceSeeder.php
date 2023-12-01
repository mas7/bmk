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
        Service::create([
            'service_category_id' => 1,
            'name'                => 'Washing clothes',
            'price'               => fake()->randomNumber(3),
        ]);

        Service::create([
            'service_category_id' => 1,
            'name'                => 'Cleaning furniture',
            'price'               => fake()->randomNumber(3),
        ]);

        Service::create([
            'service_category_id' => 2,
            'name'                => 'Electrical Installation',
            'price'               => fake()->randomNumber(3),
        ]);

        Service::create([
            'service_category_id' => 2,
            'name'                => 'Home Automation',
            'price'               => fake()->randomNumber(3),
        ]);

        Service::create([
            'service_category_id' => 2,
            'name'                => 'Lighting Services',
            'price'               => fake()->randomNumber(3),
        ]);

        Service::create([
            'service_category_id' => 3,
            'name'                => 'General Repairs',
            'price'               => fake()->randomNumber(3),
        ]);

        Service::create([
            'service_category_id' => 3,
            'name'                => 'HVAC Maintenance',
            'price'               => fake()->randomNumber(3),
        ]);
    }
}
