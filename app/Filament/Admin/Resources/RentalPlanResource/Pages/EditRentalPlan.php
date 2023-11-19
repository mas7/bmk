<?php

namespace App\Filament\Admin\Resources\RentalPlanResource\Pages;

use App\Filament\Admin\Resources\RentalPlanResource;
use App\Models\Property;
use App\Models\RentalPlan;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditRentalPlan extends EditRecord
{
    protected static string $resource = RentalPlanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        Property::find(data_get($data, 'property_id'))
            ->update([
                'rent_amount' => data_get($data, 'monthly_rent'),
                'client_id' => data_get($data, 'client_id')
            ]);

        $record->update($data);
        
        return $record;
    }
}
