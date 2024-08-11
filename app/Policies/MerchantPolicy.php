<?php

namespace App\Policies;

use App\Models\Merchant;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Auth;

class MerchantPolicy
{

    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('Gestion commerce') || $user->isAdministrateurOrGerant();
    }

    public function view(User $user, Merchant $merchant): bool
    {
        return $user->hasPermissionTo('Gestion commerce') || $user->isAdministrateurOrGerant();
    }
}
