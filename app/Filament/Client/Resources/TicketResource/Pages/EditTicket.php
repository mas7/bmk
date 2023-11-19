<?php

namespace App\Filament\Client\Resources\TicketResource\Pages;

use App\Enums\TicketStatus;
use App\Filament\Client\Resources\TicketResource;
use App\Models\Ticket;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTicket extends EditRecord
{
    protected static string $resource = TicketResource::class;

    protected function getHeaderActions(): array
    {
        /** @var Ticket $ticket */
        $ticket = static::getRecord();

        return [
            Actions\DeleteAction::make()
                ->disabled(static::getRecord()->status !== TicketStatus::OPEN),
        ];
    }
}
