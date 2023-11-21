<?php

namespace App\Filament\Admin\Resources\TicketResource\Pages;

use App\Enums\TicketStatus;
use App\Filament\Admin\Resources\TicketResource;
use App\Models\Ticket;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTicket extends EditRecord
{
    protected static string $resource = TicketResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->disabled(fn(Ticket $ticket) => $ticket->status === TicketStatus::RESOLVED),
        ];
    }
}
