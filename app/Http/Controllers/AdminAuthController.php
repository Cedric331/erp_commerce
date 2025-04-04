<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminAuthController extends Controller
{
    /**
     * Rediriger vers le panneau d'administration
     */
    public function redirectToAdmin()
    {
        if (Auth::check() && Auth::user()->isAdministrateur()) {
            return redirect('/admin');
        }
        
        return redirect('/app');
    }
}
