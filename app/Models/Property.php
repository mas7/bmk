<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    use HasFactory;

    protected $fillable = [
        "name",
        "location",
        "rent_amount",
    ];

    protected $casts = [
        "rent_amount" => "float",
    ];
}
