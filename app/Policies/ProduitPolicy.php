<?php

namespace App\Policies;

use App\Models\Produit;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Auth;

class ProduitPolicy
{

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
       return $user->hasPermissionTo('Créer produit') || $user->isAdministrateur();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Produit $produit): bool
    {
        return $user->hasPermissionTo('Créer produit') || $user->isAdministrateur();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Produit $produit): bool
    {
        return $user->hasPermissionTo('Supprimer produit') || $user->isAdministrateur();
    }
}
