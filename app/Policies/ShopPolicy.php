<?php

namespace App\Policies;

use App\Models\Shop;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Auth;

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
