<?php

namespace App\Filament\Admin\Resources\RentalPlanResource\Pages;

use App\Filament\Admin\Resources\RentalPlanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRentalPlans extends ListRecords
{
    protected static string $resource = RentalPlanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
