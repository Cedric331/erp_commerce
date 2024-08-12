<?php

namespace App\Policies;

use App\Models\StockStatus;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class StockStatusPolicy
{
    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('Créer statut stock') || $user->isAdministrateurOrGerant();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, StockStatus $stockStatus): bool
    {
        return $user->hasPermissionTo('Modifier statut stock') || $user->isAdministrateurOrGerant();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, StockStatus $stockStatus): bool
    {
        return $user->hasPermissionTo('Supprimer statut stock') || $user->isAdministrateurOrGerant();
    }
}
