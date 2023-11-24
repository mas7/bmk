<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/** @property String $name */
class Property extends Model
{
    use HasFactory;

    protected $fillable = [
        "name",
        "location",
        "rent_amount",
        "client_id",
    ];

    protected $casts = [
        "rent_amount" => "float",
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(User::class, 'client_id', 'id');
    }

    public function scopeDoesntHaveClient(Builder $query): void
    {
        $query->whereDoesntHave('client');
    }

    public function rentalPlan(): HasMany
    {
        return $this->hasMany(RentalPlan::class);
    }

    public function getHasClientAttribute(): bool
    {
        return isset($this->client_id);
    }

    public function scopeOwner(Builder $query): void
    {
        $query->when(auth()->user()->isClient, fn(Builder $query) => $query->where('client_id', auth()->id()));
    }
}
