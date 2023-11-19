<?php

namespace App\Filament\Admin\Resources\ContractorResource\Pages;

use App\Filament\Admin\Resources\ContractorResource;
use App\Models\User;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class EditContractor extends EditRecord
{
    protected static string $resource = ContractorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        /** @var User $user */
        $user = $this->getRecord()->user;

        $data['name']           = $user->name;
        $data['email']          = $user->email;
        $data['phone_number']   = $user->phone_number;

        return $data;
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $userData = [
            'name'          => $data['name'],
            'email'         => $data['email'],
            'phone_number'  => $data['phone_number'],

        ];

        if (data_get($data, 'password')) {
            data_set($userData, 'password', Hash::make($data['password']));
        }

        $record->user()->update($userData);

        $record->update([
            'service_category_id'   => $data['service_category'],
            'status'                => $data['status'],
        ]);

        return $record;
    }
}
