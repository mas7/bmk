<?php

namespace App\Models;

use App\Enums\PaymentStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'rental_plan_id',
        'parent_id',
        'amount',
        'paid_amount',
        'payment_date',
        'status',
        'type',
    ];

    protected $casts = [
        'payment_date' => 'datetime',
        'status'       => PaymentStatus::class,
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(User::class, 'client_id', 'id');
    }

    public function rentalPlan(): BelongsTo
    {
        return $this->belongsTo(RentalPlan::class);
    }

    public function scopeOwner(Builder $query): void
    {
        $query->when(auth()->user()->isClient, fn(Builder $query) => $query->where('client_id', auth()->id()));
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Payment::class, 'parent_id');
    }

    public function partials(): HasMany
    {
        return $this->hasMany(Payment::class, 'parent_id');
    }

    public function scopeParents(Builder $query): void
    {
        $query->whereDoesntHave('parent');
    }
}
