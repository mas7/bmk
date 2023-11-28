<?php

namespace App\Filament\Admin\Resources\TicketResource\Pages;

use App\Filament\Admin\Resources\TicketResource;
use App\Models\Ticket;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateTicket extends CreateRecord
{
    protected static string $resource = TicketResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        /** @var Ticket $ticket */
        $ticket = Ticket::create($data);

        foreach ($data['service_ids'] as $serviceId) {
            $ticket->ticketServices()->create([
                'service_id' => $serviceId
            ]);
        }

        return $ticket;
    }
}
