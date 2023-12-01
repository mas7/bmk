<?php

namespace App\Filament\Admin\Resources\TicketResource\Pages;

use App\Enums\TicketStatus;
use App\Filament\Admin\Resources\TicketResource;
use App\Models\ContractorService;
use App\Models\Service;
use App\Models\Ticket;
use App\Models\TicketService;
use App\Models\User;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

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
