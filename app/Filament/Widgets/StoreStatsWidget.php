<?php

namespace App\Filament\Widgets;

use App\Models\Product;
use App\Models\Stock;
use App\Models\StockStatus;
use App\Models\Storage;
use Carbon\Carbon;
use Filament\Facades\Filament;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StoreStatsWidget extends BaseWidget
{
    protected static ?string $pollingInterval = '15s';

    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $tenant = Filament::getTenant();

        if (!$tenant) {
            return [];
        }

        // Récupérer le nombre total de produits
        $totalProducts = Product::where('shop_id', $tenant->id)->count();

        // Récupérer les ventes des 30 derniers jours
        $salesLast30Days = Stock::query()
            ->where('shop_id', $tenant->id)
            ->whereHas('stockStatus', function ($query) {
                $query->where('name', StockStatus::STATUS_VENTE);
            })
            ->whereBetween('created_at', [now()->subDays(30), now()])
            ->sum('quantity');

        // Récupérer les ventes des 30 jours précédents pour comparaison
        $salesPrevious30Days = Stock::query()
            ->where('shop_id', $tenant->id)
            ->whereHas('stockStatus', function ($query) {
                $query->where('name', StockStatus::STATUS_VENTE);
            })
            ->whereBetween('created_at', [now()->subDays(60), now()->subDays(30)])
            ->sum('quantity');

        // Calculer la variation en pourcentage
        $salesVariation = $salesPrevious30Days > 0
            ? round((($salesLast30Days - $salesPrevious30Days) / $salesPrevious30Days) * 100, 1)
            : 100;

        // Récupérer les livraisons programmées à venir
        $upcomingDeliveries = Stock::query()
            ->where('shop_id', $tenant->id)
            ->whereHas('stockStatus', function ($query) {
                $query->where('name', StockStatus::STATUS_LIVRAISON);
            })
            ->whereNotNull('scheduled_date')
            ->where('scheduled_date', '>=', now())
            ->where('date_process', null)
            ->count();

        // Récupérer la valeur marchande HT des ventes des 30 derniers jours
        $salesValueLast30Days = Stock::query()
            ->where('shop_id', $tenant->id)
            ->whereHas('stockStatus', function ($query) {
                $query->where('name', StockStatus::STATUS_VENTE);
            })
            ->whereBetween('created_at', [now()->subDays(30), now()])
            ->sum(\DB::raw('price_ht * quantity'));

        // Récupérer la valeur marchande HT des ventes des 30 jours précédents pour comparaison
        $salesValuePrevious30Days = Stock::query()
            ->where('shop_id', $tenant->id)
            ->whereHas('stockStatus', function ($query) {
                $query->where('name', StockStatus::STATUS_VENTE);
            })
            ->whereBetween('created_at', [now()->subDays(60), now()->subDays(30)])
            ->sum(\DB::raw('price_ht * quantity'));

        // Calculer la variation en pourcentage pour la valeur marchande
        $salesValueVariation = $salesValuePrevious30Days > 0
            ? round((($salesValueLast30Days - $salesValuePrevious30Days) / $salesValuePrevious30Days) * 100, 1)
            : 100;


        // Récupérer les données de vente des 7 derniers jours pour le graphique
        $salesChart = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $sales = Stock::query()
                ->where('shop_id', $tenant->id)
                ->whereHas('stockStatus', function ($query) {
                    $query->where('name', StockStatus::STATUS_VENTE);
                })
                ->whereDate('created_at', $date)
                ->sum('quantity');

            $salesChart[] = $sales;
        }

        return [
            Stat::make('Total Produits', $totalProducts)
                ->description('Produits actifs dans votre inventaire')
                ->descriptionIcon('heroicon-m-cube')
                ->color('primary'),

            Stat::make('Ventes (30 jours)', $salesLast30Days)
                ->description($salesVariation >= 0
                    ? "+{$salesVariation}% par rapport au mois précédent"
                    : "{$salesVariation}% par rapport au mois précédent")
                ->descriptionIcon($salesVariation >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($salesVariation >= 0 ? 'success' : 'danger')
                ->chart($salesChart),

            Stat::make('Valeur marchande HT (30 jours)', number_format($salesValueLast30Days, 2) . ' €')
                ->description($salesValueVariation >= 0
                    ? "+{$salesValueVariation}% par rapport au mois précédent"
                    : "{$salesValueVariation}% par rapport au mois précédent")
                ->descriptionIcon($salesValueVariation >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($salesValueVariation >= 0 ? 'success' : 'danger'),

            Stat::make('Livraisons à venir', $upcomingDeliveries)
                ->description('Livraisons programmées')
                ->descriptionIcon('heroicon-m-truck')
                ->color('warning'),
        ];
    }
}
