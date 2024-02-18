<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Filament\Facades\Filament;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasTenants;
use Filament\Panel;
use Filament\Panel\Concerns\HasTenancy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Collection;
use Spatie\Permission\PermissionRegistrar;
use Spatie\Permission\Traits\HasPermissions;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements FilamentUser, HasTenants
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, HasPermissions, Notifiable;

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
        return $this->commercant;
    }

    public function isAdministrateur()
    {
        $userRoles = Auth::user()->rolesAllTenant()->pluck('name');

        if ($userRoles->contains('Administrateur')) {
            return true;
        } else {
            return false;
        }    }

    public function isAdministrateurOrGérant()
    {
        $userRoles = Auth::user()->rolesAllTenant()->pluck('name');

        if ($userRoles->contains('Administrateur') || $userRoles->contains('Gérant')) {
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

        return $relation->wherePivotNotNull('commercant_id');
    }

    public function commercant()
    {
        return $this->belongsToMany(Commercant::class, 'commercant_users', 'user_id', 'commercant_id');
    }

    public function canAccessTenant(Model $tenant): bool
    {
        // Assurez-vous que $tenant->id correspond à l'ID du commerçant. Ajustez si nécessaire.
        return $this->commercant->contains('id', $tenant->id);
    }

}
