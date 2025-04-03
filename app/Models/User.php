<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasTenants;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\PermissionRegistrar;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements FilamentUser, HasTenants
{
    use HasApiTokens, HasFactory, HasRoles, Notifiable, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function canAccessPanel(Panel $panel): bool
    {
        // check panel id
        return true;
    }

    public function getTenants(Panel $panel): Collection
    {
        return $this->getAccessibleShops();
    }

    public function hasTenant(): bool
    {
        return $this->shops->count() > 0;
    }

    public function isAdministrateur(): bool
    {
        $userRoles = Auth::user()->rolesAllTenant()->pluck('name');

        if ($userRoles->contains(Role::ROLE_ADMIN)) {
            return true;
        } else {
            return false;
        }
    }

    public function isAdministrateurOrGerant(): bool
    {
        $userRoles = Auth::user()->rolesAllTenant()->pluck('name');

        if ($userRoles->contains(Role::ROLE_ADMIN) || $userRoles->contains(Role::ROLE_GERANT)) {
            return true;
        } else {
            return false;
        }
    }

    public function isGerant(): bool
    {
        $userRoles = Auth::user()->rolesAllTenant()->pluck('name');

        if ($userRoles->contains(Role::ROLE_GERANT)) {
            return true;
        } else {
            return false;
        }
    }

    public function rolesAllTenant(): BelongsToMany
    {
        $relation = $this->morphToMany(
            config('permission.models.role'),
            'model',
            config('permission.table_names.model_has_roles'),
            config('permission.column_names.model_morph_key'),
            app(PermissionRegistrar::class)->pivotRole
        );

        if (! app(PermissionRegistrar::class)->teams) {
            return $relation;
        }

        return $relation->wherePivotNotNull('shop_id');
    }

    public function shops(): BelongsToMany
    {
        return $this->belongsToMany(Shop::class, 'shop_users', 'user_id', 'shop_id');
    }

    public function getAccessibleShops()
    {
        if ($this->isAdministrateur()) {
            return Shop::all();
        } else {
            return $this->shops;
        }
    }

    public function canAccessTenant(Model $tenant): bool
    {
        if ($this->isAdministrateurOrGerant()) {
            return true;
        }

        return $this->shops->contains('id', $tenant->id);
    }
}
