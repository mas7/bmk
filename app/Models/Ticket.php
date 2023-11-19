<?php

namespace App\Models;

use App\Enums\TicketStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/** @property TicketStatus $status */
class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'service_category_id',
        'property_id',
        'contractor_id',
        'description',
        'status',
        'expected_visit_at',
        'resolution_at'
    ];

    protected $casts = [
        'status' => TicketStatus::class,
        'expected_visit_at' => 'datetime',
        'resolution_at' => 'datetime'
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function serviceCategory(): BelongsTo
    {
        return $this->belongsTo(ServiceCategory::class);
    }

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    public function contractor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'contractor_id', 'id');
    }
}
