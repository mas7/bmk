<?php

namespace App\Models;

use App\Enums\RentalPlanStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/** @property Property $property */

/** @property User $client */
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

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function scopeOwner(Builder $query): void
    {
        $query->when(auth()->user()->isClient, fn(Builder $query) => $query->where('client_id', auth()->id()));
    }
}
