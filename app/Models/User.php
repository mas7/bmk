<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Enums\ContractorStatus;
use App\Enums\TicketStatus;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

/** @property String $name */
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
        'password'          => 'hashed',
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

    public function scopeSuperAdmin(Builder $query): Builder
    {
        return $query->whereHas(
            'roles',
            fn(Builder $query) => $query->where('name', 'super_admin')
        );
    }

    public function scopeClients(Builder $query): void
    {
        $query->whereHas(
            'roles',
            fn(Builder $query) => $query->where('name', 'client')
        );
    }

    public function scopeContractors(Builder $query, int $serviceCategoryId = null): Builder
    {
        return $query->whereHas(
            'roles',
            fn(Builder $query) => $query->where('name', 'contractor')
        )
            ->when(
                $serviceCategoryId,
                fn(Builder $query) => $query->whereHas('contractor', fn(Builder $query) => $query->where('service_category_id', $serviceCategoryId))
            );
    }

    public function scopeNotContractor(Builder $query): Builder
    {
        return $query->whereRelation('roles', 'name', '!=', 'contractor');
    }

    public function scopeHasProperty(Builder $query): Builder
    {
        return $query->whereHas('properties');
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

        if ($panel->getId() === 'contractor' && $this->isContractorActive) {
            return $this->roles->contains('name', 'contractor');
        }

        return false;
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    public function getIsContractorActiveAttribute(): bool
    {
        return $this->contractor?->status === ContractorStatus::ACTIVE;
    }

    public function scopeDoesntHaveProperty(Builder $query): void
    {
        $query->doesntHave('properties');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class, 'client_id', 'id');
    }

    public function rentalPlans(): HasMany
    {
        return $this->hasMany(RentalPlan::class, 'client_id', 'id');
    }
}
