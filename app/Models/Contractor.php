<?php

namespace App\Models;

use App\Enums\ContractorStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Contractor extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'service_category_id',
        'status',
    ];

    protected $casts = [
        'status'    => ContractorStatus::class
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function serviceCategory(): BelongsTo
    {
        return $this->belongsTo(ServiceCategory::class);
    }
}
