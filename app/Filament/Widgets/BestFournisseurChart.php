<?php

namespace App\Filament\Widgets;

use App\Models\Fournisseur;
use App\Models\Produit;
use App\Models\Stock;
use App\Models\StockStatus;
use Filament\Facades\Filament;
use Filament\Forms\Components\Select;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class BestfournisseurChart extends ApexChartWidget
{
    protected static string $chartId = 'bestSupplierChart';

    protected static ?string $heading = 'Meilleurs fournisseurs';
    protected static bool $deferLoading = true;

    protected static ?string $loadingIndicator = 'Chargement des données...';



    /**
     * Prépare les données pour le graphique ApexCharts.
     *
     * @return array
     */
    protected function getOptions(): array
    {
        // Modifier ici pour agréger les ventes par fournisseur
        $fournisseurs = Fournisseur::query()
            ->where('commercant_id', Filament::getTenant()?->id)
            ->with(['produits.stocks' => function ($query) {
                $query->whereHas('stockStatus', function ($query) {
                    $query->where('name', StockStatus::STATUS_VENTE);
                });
            }])
            ->get()
            ->map(function ($fournisseur) {
                $totalVentes = $fournisseur->produits->flatMap(function ($produit) {
                    return $produit->stocks->pluck('quantity');
                })->sum();

                return [
                    'name' => $fournisseur->name,
                    'totalVentes' => $totalVentes,
                ];
            })
            ->sortByDesc('totalVentes')
            ->take(5)
            ->values();

        $labels = $fournisseurs->pluck('name')->toArray();
        $ventesTotales = $fournisseurs->pluck('totalVentes')->toArray();

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
            'colors' => ['#df45df', '#5aa192', '#f4b30d', '#f4503d', '#3d5af4'],
            'plotOptions' => [
                'bar' => [
                    'horizontal' => false,
                ],
            ],
        ];
    }
}
