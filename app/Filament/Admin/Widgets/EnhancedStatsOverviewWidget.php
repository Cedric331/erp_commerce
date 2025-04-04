<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\Shop;
use App\Models\Storage;
use App\Models\User;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Laravel\Cashier\Subscription;

class EnhancedStatsOverviewWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        try {
            // Statistiques des commerces
            $totalShops = Shop::count();
            $newShopsThisMonth = Shop::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count();
            $newShopsLastMonth = Shop::whereMonth('created_at', now()->subMonth()->month)
                ->whereYear('created_at', now()->subMonth()->year)
                ->count();
            $shopGrowth = $this->calculateGrowthPercentage($newShopsLastMonth, $newShopsThisMonth);

            // Statistiques des utilisateurs
            $totalUsers = User::count();
            $newUsersThisMonth = User::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count();
            $newUsersLastMonth = User::whereMonth('created_at', now()->subMonth()->month)
                ->whereYear('created_at', now()->subMonth()->year)
                ->count();
            $userGrowth = $this->calculateGrowthPercentage($newUsersLastMonth, $newUsersThisMonth);

            // Statistiques des produits
            $totalProducts = Product::count();
            $newProductsThisMonth = Product::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count();
            $avgProductsPerShop = $totalShops > 0 ? round($totalProducts / $totalShops, 1) : 0;

            // Statistiques des catégories et marques
            $totalCategories = Category::count();
            $totalBrands = Brand::count();
            $totalStorages = Storage::count();

            // Statistiques des abonnements
            try {
                $activeSubscriptions = Subscription::where('stripe_status', 'active')
                    ->whereNull('ends_at')
                    ->count();
                $trialSubscriptions = Subscription::where('stripe_status', 'trialing')
                    ->count();
                $subscriptionRate = $totalShops > 0 ? round(($activeSubscriptions / $totalShops) * 100, 1) : 0;
            } catch (\Exception $e) {
                // En cas d'erreur avec les abonnements
                $activeSubscriptions = 0;
                $trialSubscriptions = 0;
                $subscriptionRate = 0;
            }

            return [
                Stat::make('Commerces', $totalShops)
                    ->description($newShopsThisMonth . ' nouveaux ce mois-ci' . ($shopGrowth !== null ? ' (' . $shopGrowth . '%)' : ''))
                    ->descriptionIcon($shopGrowth >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                    ->color($shopGrowth >= 0 ? 'success' : 'danger')
                    ->chart($this->getLastSixMonthsData(Shop::class)),

                Stat::make('Utilisateurs', $totalUsers)
                    ->description($newUsersThisMonth . ' nouveaux ce mois-ci' . ($userGrowth !== null ? ' (' . $userGrowth . '%)' : ''))
                    ->descriptionIcon($userGrowth >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                    ->color($userGrowth >= 0 ? 'success' : 'danger')
                    ->chart($this->getLastSixMonthsData(User::class)),

                Stat::make('Produits', $totalProducts)
                    ->description('Moyenne de ' . $avgProductsPerShop . ' produits par commerce')
                    ->descriptionIcon('heroicon-m-cube')
                    ->color('warning'),

                Stat::make('Taux d\'abonnement', $subscriptionRate . '%')
                    ->description($activeSubscriptions . ' abonnements actifs, ' . $trialSubscriptions . ' en essai')
                    ->descriptionIcon('heroicon-m-credit-card')
                    ->color('info'),

                Stat::make('Catégories', $totalCategories)
                    ->description('Moyenne de ' . ($totalShops > 0 ? round($totalCategories / $totalShops, 1) : 0) . ' par commerce')
                    ->descriptionIcon('heroicon-m-tag')
                    ->color('success'),

                Stat::make('Marques', $totalBrands)
                    ->description('Moyenne de ' . ($totalShops > 0 ? round($totalBrands / $totalShops, 1) : 0) . ' par commerce')
                    ->descriptionIcon('heroicon-m-building-storefront')
                    ->color('primary'),
            ];
        } catch (\Exception $e) {
            // En cas d'erreur générale, retourner des statistiques minimales
            return [
                Stat::make('Commerces', 0)
                    ->description('Erreur de chargement')
                    ->color('danger'),

                Stat::make('Utilisateurs', 0)
                    ->description('Erreur de chargement')
                    ->color('danger'),

                Stat::make('Produits', 0)
                    ->description('Erreur de chargement')
                    ->color('danger'),
            ];
        }
    }

    protected function calculateGrowthPercentage($previous, $current): ?float
    {
        if ($previous == 0) {
            return $current > 0 ? 100 : 0;
        }

        return round((($current - $previous) / $previous) * 100, 1);
    }

    protected function getLastSixMonthsData($modelClass): array
    {
        try {
            $data = [];

            for ($i = 5; $i >= 0; $i--) {
                $month = Carbon::now()->subMonths($i);
                $count = $modelClass::whereMonth('created_at', $month->month)
                    ->whereYear('created_at', $month->year)
                    ->count();
                $data[] = $count;
            }

            return $data;
        } catch (\Exception $e) {
            // En cas d'erreur, retourner des données par défaut
            return [0, 0, 0, 0, 0, 0];
        }
    }
}
