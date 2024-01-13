<?php

namespace App\Filament\Admin\Resources\TicketPaymentResource\Pages;

use App\Enums\TicketPaymentStatus;
use App\Filament\Admin\Resources\TicketPaymentResource;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListTicketPayments extends ListRecords
{
    protected static string $resource = TicketPaymentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            //Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'ALL'    => Tab::make('All')
                ->badge($this->getTableQuery()->count()),
            'PAID'   => Tab::make('Paid')
                ->badgeColor('success')
                ->badge($this->getTableQuery()->where('status', TicketPaymentStatus::PAID->value)->count())
                ->modifyQueryUsing(fn(Builder $query) => $query->where('status', TicketPaymentStatus::PAID->value)),
            'UNPAID' => Tab::make('Unpaid')
                ->badgeColor('danger')
                ->badge($this->getTableQuery()->where('status', TicketPaymentStatus::UNPAID->value)->count())
                ->modifyQueryUsing(fn(Builder $query) => $query->where('status', TicketPaymentStatus::UNPAID->value)),
        ];
    }

    public function getDefaultActiveTab(): string|int|null
    {
        return 'ALL';
    }
}
