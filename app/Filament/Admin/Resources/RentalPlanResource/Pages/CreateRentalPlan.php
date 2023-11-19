<?php

namespace App\Filament\Admin\Resources\RentalPlanResource\Pages;

use App\Filament\Admin\Resources\RentalPlanResource;
use App\Mail\UserRegistrationMail;
use App\Models\Property;
use App\Models\RentalPlan;
use App\Models\User;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;

class CreateRentalPlan extends CreateRecord
{
    protected static string $resource = RentalPlanResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        /** @var RentalPlan $rentalPlan */
        $rentalPlan = static::getModel()::create($data);

        Property::find(data_get($data, 'property_id'))
            ->update([
                'rent_amount' => data_get($data, 'monthly_rent'),
                'client_id' => data_get($data, 'client_id')
            ]);

        return $rentalPlan;
    }

}
