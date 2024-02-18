<?php

namespace App\Policies;

use App\Models\Commercant;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Auth;

class CommercantPolicy
{

    public function viewAny(User $user): bool
    {
        dd($user->hasPermissionTo('Gestion commerce') || $user->isAdministrateur());
        return $user->hasPermissionTo('Gestion commerce') || $user->isAdministrateur();
    }

    public function view(User $user, Commercant $commercant): bool
    {
        $user->hasPermissionTo('Gestion commerce') || $user->isAdministrateur();
        return $user->hasPermissionTo('Gestion commerce') || $user->isAdministrateur();
    }
}
