<?php

namespace Database\Seeders;

use App\Models\ServiceCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ServiceCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ServiceCategory::insert([
            [
                'name' => 'Cleaning Services'
            ],
            [
                'name' => 'Electrical Services'
            ],
            [
                'name' => 'House Maintenance Services'
            ],
            [
                'name' => 'AC Repair And Service'
            ],
            [
                'name' => 'Carpentry Services'
            ],
            [
                'name' => 'Painting Services'
            ],
        ]);
    }
}
