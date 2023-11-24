<?php

namespace Database\Seeders;

use App\Enums\ContractorStatus;
use App\Models\Contractor;
use App\Models\ServiceCategory;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class ContractorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->createContractor(
            'Emerald Cleaning Services Qatar',
            'info@emeraldqatar.com',
            '30001817',
            'cleaning',
            ContractorStatus::ACTIVE
        );

        $this->createContractor(
            'Abu Zaid Contracting',
            'info@abuzaidservices.com',
            '50283052',
            'electrical',
            ContractorStatus::INACTIVE
        );

        $this->createContractor(
            'FIXIT',
            'helpdesk@fixitqatar.com',
            '31334948',
            'AC Repair',
            ContractorStatus::ACTIVE
        );
    }

    public function createContractor(string $name, string $email, string $phone, string $service, ContractorStatus $status): void
    {
        $contractorUser = User::factory()->create([
            'name'         => $name,
            'email'        => $email,
            'phone_number' => $phone,
            'password'     => bcrypt('123456')
        ]);

        $contractorUser->roles()->attach(Role::firstWhere('name', 'LIKE', '%contract%'));

        Contractor::create([
            'user_id'             => $contractorUser->id,
            'service_category_id' => ServiceCategory::firstWhere('name', 'LIKE', "%{$service}%")->id,
            'status'              => $status
        ]);
    }
}
