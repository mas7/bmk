<?php

namespace App\Models;

use App\Enums\TicketImageType;
use App\Enums\TicketStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    public function images(): HasMany
    {
        return $this->hasMany(TicketImage::class)
            ->where('type', TicketImageType::IMAGE);
    }

    public function signature(): HasMany
    {
        return $this->hasMany(TicketImage::class)
            ->where('type', TicketImageType::SIGNATURE);
    }

    public function scopeOpen(Builder $query): Builder
    {
        return $query->where('status', TicketStatus::OPEN);
    }

    public function scopeReview(Builder $query): Builder
    {
        return $query->where('status', TicketStatus::REVIEW);
    }

    public function scopeResolved(Builder $query): Builder
    {
        return $query->where('status', TicketStatus::RESOLVED);
    }

    public function scopeOwner(Builder $query): void
    {
        $query->when(auth()->user()->isClient, fn(Builder $query) => $query->where('user_id', auth()->id()))
            ->when(auth()->user()->isContractor, fn(Builder $query) => $query->where('contractor_id', auth()->id()));
    }
}
