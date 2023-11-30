<?php

namespace App\Filament\Client\Resources\PaymentResource\Pages;

use App\Filament\Client\Resources\PaymentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListPayments extends ListRecords
{
    protected static string $resource = PaymentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            //Actions\CreateAction::make(),
        ];
    }

    protected function getTableQuery(): Builder
    {
        return parent::getTableQuery()->parents()->where('client_id', auth()->id())->withoutGlobalScopes();
    }
}
