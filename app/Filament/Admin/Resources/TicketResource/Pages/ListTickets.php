<?php

namespace App\Filament\Admin\Resources\TicketResource\Pages;

use App\Enums\TicketStatus;
use App\Filament\Admin\Resources\TicketResource;
use App\Models\Ticket;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListTickets extends ListRecords
{
    protected static string $resource = TicketResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'ALL'       => Tab::make('All')
                ->badge($this->getTableQuery()->count()),
            'OPEN'      => Tab::make('Open')
                ->badge($this->getTableQuery()->where('status', TicketStatus::OPEN->value)->count())
                ->badgeColor('info')
                ->modifyQueryUsing(fn(Builder $query) => $query->where('status', TicketStatus::OPEN->value)),
            'ANSWERED'  => Tab::make('Answered')
                ->badge($this->getTableQuery()->where('status', TicketStatus::ANSWERED->value)->count())
                ->badgeColor('warning')
                ->modifyQueryUsing(fn(Builder $query) => $query->where('status', TicketStatus::ANSWERED->value)),
            'RESOLVED'  => Tab::make('Resolved')
                ->badge($this->getTableQuery()->where('status', TicketStatus::RESOLVED->value)->count())
                ->badgeColor('success')
                ->modifyQueryUsing(fn(Builder $query) => $query->where('status', TicketStatus::RESOLVED->value)),
            'POSTPONED' => Tab::make('Postponed')
                ->badge($this->getTableQuery()->where('status', TicketStatus::POSTPONED->value)->count())
                ->badgeColor('danger')
                ->modifyQueryUsing(fn(Builder $query) => $query->where('status', TicketStatus::POSTPONED->value)),
            'REVIEW'    => Tab::make('Review')
                ->badge($this->getTableQuery()->where('status', TicketStatus::REVIEW->value)->count())
                ->badgeColor('warning')
                ->modifyQueryUsing(fn(Builder $query) => $query->where('status', TicketStatus::REVIEW->value)),
        ];
    }

    public function getDefaultActiveTab(): string|int|null
    {
        return 'ALL';
    }
}
