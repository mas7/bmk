<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Mail\UserRegistrationMail;
use App\Models\User;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        /** @var User $user */
        $user = static::getModel()::create($data);

        $user['plainPassword'] = $data['password'];

        Mail::to($user->email)->send(new UserRegistrationMail($user));

        return $user;
    }
}
