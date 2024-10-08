<?php

namespace App\Http\Middleware;

use App\Models\Category;
use App\Models\Brand;
use App\Models\Product;
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

        Product::addGlobalScope(
            fn (Builder $query) => $query->whereBelongsTo(Filament::getTenant()),
        );

        Stock::addGlobalScope(
            fn (Builder $query) => $query->whereBelongsTo(Filament::getTenant()),
        );

        Category::addGlobalScope(
            fn (Builder $query) => $query->whereBelongsTo(Filament::getTenant()),
        );

        Brand::addGlobalScope(
            fn (Builder $query) => $query->whereBelongsTo(Filament::getTenant()),
        );

        Role::addGlobalScope(
            fn (Builder $query) => $query->whereBelongsTo(Filament::getTenant())
                ->orWhere('name', '=', Role::ROLE_ADMIN)
                ->orWhere('name', '=', Role::ROLE_GERANT)
        );

        Storage::addGlobalScope(
            fn (Builder $query) => $query->whereBelongsTo(Filament::getTenant()),
        );

        return $next($request);
    }
}
