<?php

namespace App\Models;

use App\Enums\ContractorStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Contractor extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'status',
    ];

    protected $casts = [
        'status' => ContractorStatus::class
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function serviceCategories(): HasManyThrough
    {
        return $this->hasManyThrough(
            ServiceCategory::class,
            ContractorService::class,
            'contractor_id',       // Foreign key on the ContractorService table
            'id',                  // Foreign key on the ServiceCategory table
            'id',                  // Local key on the Contractor table
            'service_category_id'  // Local key on the ContractorService table
        );
    }


    public function contractorServices(): HasMany
    {
        return $this->hasMany(ContractorService::class);
    }
}
