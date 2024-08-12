<?php

namespace App\Models;

use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Permission\Models\Permission as SpatiePermission;
use Spatie\Permission\PermissionRegistrar;

class Permission extends SpatiePermission
{

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

}

