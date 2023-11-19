<?php

namespace App\Filament\Contractor\Resources\TicketResource\Pages;

use App\Filament\Contractor\Resources\TicketResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListTickets extends ListRecords
{
    protected static string $resource = TicketResource::class;

    protected function getHeaderActions(): array
    {
        return [
            //Actions\CreateAction::make(),
        ];
    }

    protected function getTableQuery(): Builder
    {
        return parent::getTableQuery()->where('contractor_id', auth()->id())->withoutGlobalScopes();
    }
}
