<?php

namespace App\Filament\Client\Resources\RentalPlanResource\Pages;

use App\Filament\Client\Resources\RentalPlanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRentalPlan extends EditRecord
{
    protected static string $resource = RentalPlanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
