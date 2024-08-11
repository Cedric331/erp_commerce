<?php

namespace App\Models;

use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Permission\Models\Role as SpatieRole;
use Spatie\Permission\PermissionRegistrar;

class Role extends SpatieRole
{
    const ROLE_ADMIN = 'Administrateur';
    const ROLE_MANAGER = 'Manager';
    const ROLE_GERANT = 'Gerant';
    const ROLE_SERVEUR = 'Serveur';

    protected $fillable = ['name', 'guard_name', 'merchant_id'];

    public function merchant()
    {
        return $this->belongsTo(Merchant::class);
    }

    public function users(): BelongsToMany
    {
        if (Filament::getTenant()) {
            return $this->morphedByMany(
                getModelForGuard($this->attributes['guard_name'] ?? config('auth.defaults.guard')),
                'model',
                config('permission.table_names.model_has_roles'),
                app(PermissionRegistrar::class)->pivotRole,
                config('permission.column_names.model_morph_key')
            )->withPivotValue('merchant_id', Filament::getTenant()->id);
        } else {
            return $this->morphedByMany(
                getModelForGuard($this->attributes['guard_name'] ?? config('auth.defaults.guard')),
                'model',
                config('permission.table_names.model_has_roles'),
                app(PermissionRegistrar::class)->pivotRole,
                config('permission.column_names.model_morph_key')
            );
        }
    }
}

