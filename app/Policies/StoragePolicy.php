<?php

namespace App\Policies;

use App\Models\User;

class StoragePolicy
{

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('Créer zone de stockage') || $user->isAdministrateurOrGerant();
    }

    public function update(User $user): bool
    {
        return $user->hasPermissionTo('Modifier zone de stockage') || $user->isAdministrateurOrGerant();
    }

    public function delete(User $user): bool
    {
        return $user->hasPermissionTo('Supprimer zone de stockage') || $user->isAdministrateurOrGerant();
    }
}
