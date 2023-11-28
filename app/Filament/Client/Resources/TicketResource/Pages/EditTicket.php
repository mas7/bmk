<?php

namespace App\Filament\Client\Resources\TicketResource\Pages;

use App\Enums\TicketStatus;
use App\Filament\Client\Resources\TicketResource;
use App\Models\Ticket;
use App\Models\TicketService;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

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

    protected function mutateFormDataBeforeFill(array $data): array
    {
        /** @var Ticket $ticket */
        $ticket = $this->getRecord();

        $data = $ticket->toArray();

        $data['service_ids'] = $ticket->ticketServices->pluck('service_id');

        return $data;
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $record->update($data);

        $serviceIds = $data['service_ids'];

        TicketService::query()
            ->where('ticket_id', $record->id)
            ->whereNotIn('service_id', $serviceIds)
            ->delete();

        foreach ($serviceIds as $serviceId) {
            TicketService::query()
                ->updateOrCreate(
                    [
                        'ticket_id'  => $record->id,
                        'service_id' => $serviceId
                    ],
                    [
                        // Additional fields to update, if any
                    ]
                );
        }

        return $record;
    }
}
