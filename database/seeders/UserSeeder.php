<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::factory()->create([
            'name'         => 'Admin',
            'email'        => 'info@bmkfacilities.com',
            'phone_number' => '30065065',
            'password'     => bcrypt('123456')
        ]);

        $admin->roles()->attach(Role::firstWhere('name', 'LIKE', '%admin%'));

        $client = User::factory()->create([
            'name'         => 'Maged Ali',
            'email'        => 'maged@gmail.com',
            'phone_number' => '30936209',
            'password'     => bcrypt('123456')
        ]);

        $client->roles()->attach(Role::firstWhere('name', 'LIKE', '%client%'));

    }
}
