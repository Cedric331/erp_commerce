<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('Créer Utilisateur') || $user->isAdministrateurOrGerant();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, User $model): bool
    {
        return $user->hasPermissionTo('Modifier Utilisateur') || $user->isAdministrateurOrGerant();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, User $model): bool
    {
        return $user->hasPermissionTo('Supprimer Utilisateur') || $user->isAdministrateurOrGerant();
    }
}
