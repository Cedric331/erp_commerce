<?php

namespace App\Http\Middleware;

use App\Models\CategorieProduit;
use App\Models\Fournisseur;
use App\Models\Produit;
use App\Models\Stock;
use App\Models\Storage;
use Closure;
use Filament\Facades\Filament;
use Illuminate\Http\Request;
use App\Models\Role;
use Laravel\Cashier\Billable;
use Laravel\Cashier\Cashier;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Database\Eloquent\Builder;
class ApplyTenantScopes
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        Produit::addGlobalScope(
            fn (Builder $query) => $query->whereBelongsTo(Filament::getTenant()),
        );

        Stock::addGlobalScope(
            fn (Builder $query) => $query->whereBelongsTo(Filament::getTenant()),
        );

        CategorieProduit::addGlobalScope(
            fn (Builder $query) => $query->whereBelongsTo(Filament::getTenant()),
        );

        Fournisseur::addGlobalScope(
            fn (Builder $query) => $query->whereBelongsTo(Filament::getTenant()),
        );

        Role::addGlobalScope(
            fn (Builder $query) => $query->whereBelongsTo(Filament::getTenant())
                ->orWhere('name', '=', 'Administrateur')
                ->orWhere('name', '=', 'Gérant')
        );

        Storage::addGlobalScope(
            fn (Builder $query) => $query->whereBelongsTo(Filament::getTenant()),
        );

        return $next($request);
    }
}
