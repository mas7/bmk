<?php

namespace App\Filament\Admin\Resources\TicketPaymentResource\Pages;

use App\Filament\Admin\Resources\TicketPaymentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTicketPayment extends EditRecord
{
    protected static string $resource = TicketPaymentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
