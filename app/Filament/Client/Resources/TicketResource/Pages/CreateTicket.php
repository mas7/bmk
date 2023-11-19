<?php

namespace App\Filament\Client\Resources\TicketResource\Pages;

use App\Enums\TicketStatus;
use App\Filament\Client\Resources\TicketResource;
use App\Models\Ticket;
use App\Models\User;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateTicket extends CreateRecord
{
    protected static string $resource = TicketResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        /** @var User $user */
        $user = auth()->user();

        $data['status'] = TicketStatus::OPEN;

        /** @var Ticket $ticket */
        $ticket = $user->tickets()->create($data);

        return $ticket;
    }
}
