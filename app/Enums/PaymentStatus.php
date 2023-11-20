<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum PaymentStatus: int implements HasLabel
{
    case PAID = 1;

    case UNPAID = 2;

    case POSTPONED = 3;

    public function getLabel(): ?string
    {
        return $this->name;
    }
}
