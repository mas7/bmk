<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum TicketStatus: int implements HasLabel
{
    case OPEN       = 1;
    case ANSWERED   = 2;
    case RESOLVED   = 3;
    case POSTPONED  = 4;

    public function getLabel(): ?string
    {
        return $this->name;
    }
}
