<?php

namespace App\Http\Middleware;

use Closure;
use Filament\Facades\Filament;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SyncSpatiePermissionsWithFilamentTenants
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $spatie = getPermissionsTeamId();
        $filament = Filament::getTenant();

        if ($filament && $filament->id !== $spatie) {
            setPermissionsTeamId($filament);
            if (Auth::check()) {
                Auth::user()->unsetRelation('roles')->unsetRelation('permissions');
            }
        }

        return $next($request);
    }
}
