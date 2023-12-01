<?php

namespace App\Filament\Admin\Resources\UserResource\Pages;

use App\Filament\Admin\Resources\UserResource;
use App\Mail\UserRegistrationMail;
use App\Models\User;
use ErrorException;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;
use Symfony\Component\Mailer\Exception\TransportException;
use Throwable;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    /**
     * @throws ErrorException
     */
    protected function handleRecordCreation(array $data): Model
    {
        try {
            /** @var User $user */
            $user = static::getModel()::create($data);

            $user['plainPassword'] = $data['password'];

            Mail::to($user->email)->send(new UserRegistrationMail($user));
        } catch (Throwable $th) {
            Notification::make()
                ->title("Error occurred while sending registration email")
                ->body($th->getMessage())
                ->danger()
                ->send();
        }

        return $user;
    }
}
