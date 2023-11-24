<?php

namespace App\Filament\Client\Resources\RentalPlanResource\Pages;

use App\Filament\Client\Resources\RentalPlanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListRentalPlans extends ListRecords
{
    protected static string $resource = RentalPlanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            //Actions\CreateAction::make(),
        ];
    }

    protected function getTableQuery(): Builder
    {
        return parent::getTableQuery()->where('client_id', auth()->id())->withoutGlobalScopes();
    }
}
