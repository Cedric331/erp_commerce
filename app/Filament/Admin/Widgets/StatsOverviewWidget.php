<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Shop;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Laravel\Cashier\Subscription;

class StatsOverviewWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        // Nombre total d'utilisateurs
        $totalUsers = User::count();
        $newUsersThisMonth = User::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
        $userIncrease = $this->calculateIncrease(
            User::whereMonth('created_at', now()->subMonth()->month)
                ->whereYear('created_at', now()->subMonth()->year)
                ->count(),
            $newUsersThisMonth
        );

        // Nombre total de commerces
        $totalShops = Shop::count();
        $newShopsThisMonth = Shop::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
        $shopIncrease = $this->calculateIncrease(
            Shop::whereMonth('created_at', now()->subMonth()->month)
                ->whereYear('created_at', now()->subMonth()->year)
                ->count(),
            $newShopsThisMonth
        );

        // Nombre d'abonnements actifs
        $activeSubscriptions = Subscription::where('stripe_status', 'active')->count();
        $trialSubscriptions = Subscription::where('stripe_status', 'trialing')->count();
        $totalSubscriptions = $activeSubscriptions + $trialSubscriptions;

        return [
            Stat::make('Utilisateurs', $totalUsers)
                ->description($newUsersThisMonth . ' nouveaux ce mois-ci')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->chart([7, 2, 10, 3, 15, 4, $newUsersThisMonth])
                ->color($userIncrease >= 0 ? 'success' : 'danger'),

            Stat::make('Commerces', $totalShops)
                ->description($newShopsThisMonth . ' nouveaux ce mois-ci')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->chart([2, 1, 3, 5, 4, 7, $newShopsThisMonth])
                ->color($shopIncrease >= 0 ? 'success' : 'danger'),

            Stat::make('Abonnements', $totalSubscriptions)
                ->description($trialSubscriptions . ' en période d\'essai')
                ->descriptionIcon('heroicon-m-credit-card')
                ->color('info'),
        ];
    }

    private function calculateIncrease(int $previous, int $current): float
    {
        if ($previous === 0) {
            return $current > 0 ? 100 : 0;
        }

        return round((($current - $previous) / $previous) * 100, 2);
    }
}
