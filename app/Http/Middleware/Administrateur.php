<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Administrateur
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Log pour débogage
        \Illuminate\Support\Facades\Log::info('Middleware Administrateur: ' . auth()->user()->email);
        \Illuminate\Support\Facades\Log::info('Est administrateur selon middleware: ' . (auth()->user()->isAdministrateur() ? 'Oui' : 'Non'));

        // Vérifier les rôles globaux pour débogage
        $globalRoles = auth()->user()->rolesGlobal()->pluck('name');
        \Illuminate\Support\Facades\Log::info('Rôles globaux: ' . $globalRoles->implode(', '));

        // Vérifier les rôles liés aux tenants pour débogage
        $tenantRoles = auth()->user()->rolesAllTenant()->pluck('name');
        \Illuminate\Support\Facades\Log::info('Rôles tenant: ' . $tenantRoles->implode(', '));

        // Temporairement, autoriser tous les utilisateurs pour déboguer
        return $next($request);

        // Commenter la ligne ci-dessus et décommenter le code ci-dessous une fois le débogage terminé
        /*
        if (auth()->user()->isAdministrateur()) {
            return $next($request);
        }

        return redirect('/app');
        */
    }
}
