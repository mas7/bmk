<?php

namespace App\Models;

use App\Enums\PaymentStatus;
use App\Enums\TicketPaymentStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TicketPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_id',
        'total',
        'status'
    ];

    protected $casts = [
        'status' => TicketPaymentStatus::class,
    ];

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }
}
