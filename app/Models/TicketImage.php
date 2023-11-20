<?php

namespace App\Models;

use App\Enums\TicketImageType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TicketImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_id',
        'path',
        'type'
    ];

    protected $casts = [
        'type' => TicketImageType::class,
    ];

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }
}
