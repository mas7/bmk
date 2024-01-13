<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        DB::unprepared(file_get_contents(database_path('dumps/roles_and_permissions.sql')));

        $this->call([
            UserSeeder::class,
            ServiceCategorySeeder::class,
            ServiceSeeder::class,
            ContractorSeeder::class,
            ContractorServiceSeeder::class,
            PropertySeeder::class,
            RentalPlanSeeder::class,
            //PaymentSeeder::class,
            TicketSeeder::class,
        ]);

        //DB::unprepared(file_get_contents(database_path('dumps/tickets.sql')));
    }
}
