<?php

namespace App\Models;

use App\Enums\RentalPlanStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RentalPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'property_id',
        'client_id',
        'start_date',
        'end_date',
        'monthly_rent',
        'status',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'status' => RentalPlanStatus::class,
    ];

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(User::class, 'client_id', 'id');
    }
}
