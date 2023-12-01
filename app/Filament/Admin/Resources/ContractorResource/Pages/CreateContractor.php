<?php

namespace App\Filament\Admin\Resources\ContractorResource\Pages;

use App\Filament\Admin\Resources\ContractorResource;
use App\Mail\UserRegistrationMail;
use App\Models\ContractorService;
use App\Models\Service;
use App\Models\User;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class CreateContractor extends CreateRecord
{
    protected static string $resource = ContractorResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        /** @var User $user */
        $user = User::create([
            'name'         => $data['name'],
            'email'        => $data['email'],
            'phone_number' => $data['phone_number'],
            'password'     => Hash::make($data['password']),
        ]);

        $contractor = $user->contractor()->create([
            'status' => $data['status'],
        ]);

        foreach (data_get($data, 'services') as $service) {
            $service = Service::find(data_get($service, 'service_id'));
            ContractorService::create([
                'contractor_id'       => $contractor->id,
                'service_id'          => $service->id,
                'service_category_id' => $service->service_category_id,
                'price'               => data_get($service, 'price')
            ]);
        }

        $user->assignRole('contractor');

        data_set($user, 'plainPassword', $data['password']);

        $this->sendWelcomeEmail($user);

        return $user->contractor;
    }

    public function sendWelcomeEmail(User $user): void
    {
        try {
            Mail::to($user->email)->send(new UserRegistrationMail($user));
        } catch (\Throwable $th) {
            Log::error("[!] Error while sending email to contractor ({$user->id}-{$user->name})");
            Log::error($th);
        }
    }
}
