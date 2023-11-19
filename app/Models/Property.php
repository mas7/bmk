<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    public function scopeDoesntHaveClient(Builder $query): Builder
    {
        return $query->whereDoesntHave('client');
    }
}
