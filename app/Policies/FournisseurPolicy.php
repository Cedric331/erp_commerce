<?php

namespace App\Policies;

use App\Models\Fournisseur;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class FournisseurPolicy
{

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('Créer Fournisseur') || $user->isAdministrateurOrGerant();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Fournisseur $fournisseur): bool
    {
        return $user->hasPermissionTo('Modifier Fournisseur') || $user->isAdministrateurOrGerant();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Fournisseur $fournisseur): bool
    {
        return $user->hasPermissionTo('Supprimer Fournisseur') || $user->isAdministrateurOrGerant();
    }
}
