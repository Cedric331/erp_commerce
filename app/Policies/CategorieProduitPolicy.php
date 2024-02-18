<?php

namespace App\Policies;

use App\Models\CategorieProduit;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CategorieProduitPolicy
{

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('Créer catégorie') || $user->isAdministrateur();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, CategorieProduit $categorie): bool
    {
        return $user->hasPermissionTo('Modifier catégorie') || $user->isAdministrateur();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, CategorieProduit $categorie): bool
    {
        return $user->hasPermissionTo('Supprimer catégorie') || $user->isAdministrateur();
    }
}
