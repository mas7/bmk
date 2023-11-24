<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Ticket;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class UsersStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Clients', User::clients()->count()),
            Stat::make('Contractors', User::contractors()->count()),
        ];
    }
}
