<?php

namespace App\Filament\Admin\Widgets;

use App\Enums\PaymentStatus;
use App\Models\Payment;
use App\Models\Property;
use App\Models\RentalPlan;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class PropertyStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Properties', Property::owner()->count()),
            Stat::make('Rentals', RentalPlan::owner()->count()),
            Stat::make('Payments', "QAR " . Payment::owner()->parents()->sum('paid_amount')),
        ];
    }
}
