<?php

namespace App\Providers;

use App\Models\Shop;
use App\Policies\PermissionPolicy;
use App\Policies\RolePolicy;
use Filament\Support\Assets\Js;
use Filament\Support\Facades\FilamentAsset;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Laravel\Cashier\Cashier;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //        Cashier::keepPastDueSubscriptionsActive();
        //        Cashier::keepIncompleteSubscriptionsActive();
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Cashier::useCustomerModel(Shop::class);
        Gate::policy(Role::class, RolePolicy::class);
        Gate::policy(Permission::class, PermissionPolicy::class);

        //        FilamentAsset::register([
        //            Js::make('stripe-script', __DIR__ . '/../../resources/js/stripe.js'),
        //        ]);
        // Faire la commande php artisan filament:assets pour générer le fichier
    }
}
