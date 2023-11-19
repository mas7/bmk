<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum RentalPlanStatus: int implements HasLabel
{
    case ACTIVE = 1;
    case INACTIVE = 2;

    public function getLabel(): ?string
    {
        return $this->name;
    }
}
