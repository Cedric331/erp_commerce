<?php

namespace App\Providers;

use App\Models\Commercant;
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
        Cashier::useCustomerModel(Commercant::class);

    }
}
