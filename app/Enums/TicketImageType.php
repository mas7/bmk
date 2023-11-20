<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum TicketImageType: int implements HasLabel
{
    case IMAGE = 1;
    case SIGNATURE = 2;

    public function getLabel(): ?string
    {
        return $this->name;
    }
}
