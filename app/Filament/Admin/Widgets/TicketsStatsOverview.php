<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Ticket;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class TicketsStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Tickets Open', Ticket::open()->count()),
            Stat::make('Tickets in Review', Ticket::review()->count()),
            Stat::make('Tickets Resolved', Ticket::resolved()->count()),
        ];
    }
}
