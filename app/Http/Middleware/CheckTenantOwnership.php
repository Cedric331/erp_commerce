<?php

namespace App\Http\Middleware;

use Closure;
use Filament\Facades\Filament;
use Illuminate\Http\Request;

class CheckTenantOwnership
{
    /**
     * Handle an incoming request.
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $tenant = Filament::getTenant();

        if ($tenant && ! auth()->user()->shops->contains($tenant)) {
            return redirect()->back();
        }

        return $next($request);
    }
}
