<?php

namespace App\Providers;

use App\Models\Merchant;
use Filament\Support\Assets\Js;
use Filament\Support\Facades\FilamentAsset;
use Illuminate\Support\ServiceProvider;
use Laravel\Cashier\Cashier;

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
        Cashier::useCustomerModel(Merchant::class);

//        FilamentAsset::register([
//            Js::make('stripe-script', __DIR__ . '/../../resources/js/stripe.js'),
//        ]);
        // Faire la commande php artisan filament:assets pour générer le fichier
    }
}
