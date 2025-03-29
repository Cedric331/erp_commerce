<?php

namespace App\Filament\Widgets;

use App\Models\Category;
use Filament\Facades\Filament;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class BestCategoryChart extends ApexChartWidget
{
    protected static ?string $chartId = 'bestCategoryChart';

    protected static ?string $heading = 'Meilleures catégories';

    protected static bool $deferLoading = true;

    protected static ?string $loadingIndicator = 'Chargement des données...';

    /**
     * Prépare les données pour le graphique ApexCharts.
     */
    protected function getOptions(): array
    {
        // Modifier ici pour agréger les ventes par catégorie
        $categories = Category::query()
            ->where('shop_id', Filament::getTenant()?->id)
            ->with(['products.stocks' => function ($query) {
                $query->whereHas('stockStatus', function ($query) {
                    $query->where('name', 'Vente');
                });
            }])
            ->get()
            ->map(function ($category) {
                $salesTotals = $category->products->flatMap(function ($product) {
                    return $product->stocks->pluck('quantity');
                })->sum();

                return [
                    'nom' => $category->name,
                    'salesTotals' => $salesTotals,
                ];
            })
            ->sortByDesc('salesTotals')
            ->take(5)
            ->values();

        $labels = $categories->pluck('nom')->toArray();
        $salesTotals = $categories->pluck('salesTotals')->toArray();

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
            'colors' => ['#5aa192', '#f5a623', '#f8e71c', '#9b9b9b', '#e74c3c'],
            'plotOptions' => [
                'bar' => [
                    'horizontal' => false,
                ],
            ],
        ];
    }
}
