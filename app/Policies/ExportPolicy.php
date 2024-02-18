<?php

namespace App\Policies;

use App\Models\User;
use Filament\Actions\Exports\Models\Export;

class ExportPolicy
{

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('Exporter des données') || $user->isAdministrateur();
    }
}
