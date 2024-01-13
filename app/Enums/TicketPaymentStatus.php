<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum TicketPaymentStatus: int implements HasLabel
{
    case PAID   = 1;
    case UNPAID = 2;

    public function getLabel(): ?string
    {
        return $this->name;
    }
}
