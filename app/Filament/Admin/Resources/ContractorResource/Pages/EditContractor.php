<?php

namespace App\Filament\Admin\Resources\ContractorResource\Pages;

use App\Filament\Admin\Resources\ContractorResource;
use App\Models\ContractorService;
use App\Models\Service;
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

        $data['name']         = $user->name;
        $data['email']        = $user->email;
        $data['phone_number'] = $user->phone_number;
        $data['services']     = [];

        $contractor = $user->contractor;

        $contractor->contractorServices->each(function (ContractorService $contractorService) use (&$data) {
            $data['services'][] = [
                'service_id' => $contractorService->service_id,
                'price'      => $contractorService->price,
            ];
        });

        return $data;
    }


    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $userData = [
            'name'         => $data['name'],
            'email'        => $data['email'],
            'phone_number' => $data['phone_number'],

        ];

        if (data_get($data, 'password')) {
            data_set($userData, 'password', Hash::make($data['password']));
        }

        $record->user()->update($userData);

        $record->update([
            'status' => $data['status'],
        ]);

        $serviceIds = collect(data_get($data, 'services'))->pluck('service_id')->all();

        foreach ($serviceIds as $serviceId) {
            $serviceData = collect(data_get($data, 'services'))->firstWhere('service_id', $serviceId);
            $service     = Service::find($serviceId);

            ContractorService::updateOrCreate([
                'contractor_id'       => $record->id,
                'service_category_id' => $service->service_category_id,
                'service_id'          => $serviceId,
            ], [
                'price' => data_get($serviceData, 'price'),
            ]);
        }

        ContractorService::where('contractor_id', $record->id)
            ->whereNotIn('service_id', $serviceIds)
            ->delete();

        return $record;
    }
}
