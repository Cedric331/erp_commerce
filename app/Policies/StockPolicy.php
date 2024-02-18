<?php

namespace App\Policies;

use App\Models\Stock;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class StockPolicy
{

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('Créer Stock') || $user->isAdministrateur();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Stock $stock): bool
    {
        return $user->hasPermissionTo('Modifier Stock') || $user->isAdministrateur();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Stock $stock): bool
    {
        return $user->hasPermissionTo('Supprimer Stock') || $user->isAdministrateur();
    }
}
