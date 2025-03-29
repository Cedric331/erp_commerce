<?php

namespace App\Filament\Widgets;

use App\Models\Brand;
use App\Models\StockStatus;
use Filament\Facades\Filament;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class BestBrandChart extends ApexChartWidget
{
    protected static ?string $chartId = 'bestSupplierChart';

    protected static ?string $heading = 'Meilleurs fournisseurs';

    protected static bool $deferLoading = true;

    protected static ?string $loadingIndicator = 'Chargement des données...';

    /**
     * Prépare les données pour le graphique ApexCharts.
     */
    protected function getOptions(): array
    {
        // Modifier ici pour agréger les ventes par fournisseur
        $brands = Brand::query()
            ->where('shop_id', Filament::getTenant()?->id)
            ->with(['products.stocks' => function ($query) {
                $query->whereHas('stockStatus', function ($query) {
                    $query->where('name', StockStatus::STATUS_VENTE);
                });
            }])
            ->get()
            ->map(function ($brand) {
                $totalSales = $brand->products->flatMap(function ($product) {
                    return $product->stocks->pluck('quantity');
                })->sum();

                return [
                    'name' => $brand->name,
                    'totalSales' => $totalSales,
                ];
            })
            ->sortByDesc('totalSales')
            ->take(5)
            ->values();

        $labels = $brands->pluck('name')->toArray();
        $salesTotals = $brands->pluck('totalSales')->toArray();

        return [
            'chart' => [
                'type' => 'bar',
                'height' => 300,
            ],
            'series' => [
                [
                    'name' => 'Ventes Totales',
                    'data' => $salesTotals,
                ],
            ],
            'xaxis' => [
                'categories' => $labels,
            ],
            'colors' => ['#df45df', '#5aa192', '#f4b30d', '#f4503d', '#3d5af4'],
            'plotOptions' => [
                'bar' => [
                    'horizontal' => false,
                ],
            ],
        ];
    }
}
