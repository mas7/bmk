<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements FilamentUser
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone_number'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function properties(): HasMany
    {
        return $this->hasMany(Property::class, 'client_id', 'id');
    }

    public function getIsAdminAttribute(): bool
    {
        return $this->roles->contains('name', 'super_admin');
    }

    public function getIsClientAttribute(): bool
    {
        return $this->roles->contains('name', 'client');
    }

    public function getIsContractorAttribute(): bool
    {
        return $this->roles->contains('name', 'contractor');
    }

    public function contractor(): HasOne
    {
        return $this->hasOne(Contractor::class);
    }

    // TODO: redirect to roles route
    public function canAccessPanel(Panel $panel): bool
    {
        if ($panel->getId() === 'admin') {
            return $this->roles->contains('name', 'super_admin');
        }

        if ($panel->getId() === 'client') {
            return $this->roles->contains('name', 'client');
        }

        if ($panel->getId() === 'contractor') {
            return $this->roles->contains('name', 'contractor');
        }

        return false;
    }
}
