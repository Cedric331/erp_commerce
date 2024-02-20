<?php

namespace App\Filament\Widgets;

use App\Models\CategorieProduit;
use App\Models\Produit;
use App\Models\StockStatus;
use Filament\Facades\Filament;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class BestCategorieChart extends ApexChartWidget
{
    protected static string $chartId = 'bestCategoryChart';

    protected static ?string $heading = 'Meilleures catégories';
    protected static bool $deferLoading = true;

    protected static ?string $loadingIndicator = 'Chargement des données...';

    /**
     * Prépare les données pour le graphique ApexCharts.
     *
     * @return array
     */
    protected function getOptions(): array
    {
        // Modifier ici pour agréger les ventes par catégorie
        $categories = CategorieProduit::query()
            ->where('commercant_id', Filament::getTenant()?->id)
            ->with(['produits.stocks' => function ($query) {
                $query->whereHas('stockStatus', function ($query) {
                    $query->where('name', 'Vente');
                });
            }])
            ->get()
            ->map(function ($categorie) {
                $totalVentes = $categorie->produits->flatMap(function ($produit) {
                    return $produit->stocks->pluck('quantity');
                })->sum();

                return [
                    'nom' => $categorie->name,
                    'totalVentes' => $totalVentes,
                ];
            })
            ->sortByDesc('totalVentes')
            ->take(5)
            ->values();

        $labels = $categories->pluck('nom')->toArray();
        $ventesTotales = $categories->pluck('totalVentes')->toArray();

        return [
            'chart' => [
                'type' => 'bar',
                'height' => 300,
            ],
            'series' => [
                [
                    'name' => 'Ventes Totales',
                    'data' => $ventesTotales,
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
