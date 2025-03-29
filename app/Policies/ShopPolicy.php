<?php

namespace App\Policies;

use App\Models\Shop;
use App\Models\User;

class ShopPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('Gestion commerce') || $user->isAdministrateurOrGerant();
    }

    public function view(User $user, Shop $shop): bool
    {
        return $user->hasPermissionTo('Gestion commerce') || $user->isAdministrateurOrGerant();
    }
}
