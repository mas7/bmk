<?php

namespace App\Filament\Resources\ContractorResource\Pages;

use App\Enums\ContractorStatus;
use App\Filament\Resources\ContractorResource;
use App\Mail\UserRegistrationMail;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class CreateContractor extends CreateRecord
{
    protected static string $resource = ContractorResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        /** @var User $user */
        $user = User::create([
            'name'          => $data['name'],
            'email'         => $data['email'],
            'phone_number'  => $data['phone_number'],
            'password'      => Hash::make($data['password']),
        ]);

        $user->contractor()->create([
            'service_category_id'   => $data['service_category'],
            'status'                => $data['status'],
        ]);

        $user->assignRole('contractor');

        data_set($user, 'plainPassword', $data['password']);
        Mail::to($user->email)->send(new UserRegistrationMail($user));

        return $user->contractor;
    }
}
