<?php

namespace App\Policies;

use App\Models\Stock;
use App\Models\User;

class StockPolicy
{
    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->isAdministrateurOrGerant() || $user->hasPermissionTo('Créer stock');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Stock $stock): bool
    {
        return $user->isAdministrateurOrGerant() || $user->hasPermissionTo('Modifier stock');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Stock $stock): bool
    {
        return $user->isAdministrateurOrGerant() || $user->hasPermissionTo('Créer stock');
    }
}
