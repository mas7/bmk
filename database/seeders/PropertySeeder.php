<?php

namespace Database\Seeders;

use App\Models\Property;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PropertySeeder extends Seeder
{
    public function run(): void
    {
        Property::create([
            'name'     => 'Villa-5-Thumama',
            'location' => 'Al Thumama Doha, Al Thumama'
        ]);

        Property::create([
            'name'     => 'Apartment-1-Floresta-Gardins',
            'location' => 'Floresta Gardens Doha, The Pearl Island, Floresta Gardens'
        ]);

        Property::create([
            'name'     => 'Apartment-34-Musheireb',
            'location' => 'Regency Residence Musheireb Doha, Musheireb, Regency Residence Musheireb'
        ]);
    }
}
