<?php

namespace App\Filament\Widgets;

use App\Models\Storage;
use Filament\Facades\Filament;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class BestStorageChart extends ApexChartWidget
{
    protected static ?string $chartId = 'bestStorageChart';

    protected static ?string $heading = 'Zone de stockage';

    protected static bool $deferLoading = true;

    protected static ?string $loadingIndicator = 'Chargement des données...';

    /**
     * Prépare les données pour le graphique ApexCharts.
     */
    protected function getOptions(): array
    {
        $storages = Storage::query()
            ->where('shop_id', Filament::getTenant()?->id)
            ->with('products')
            ->get()
            ->map(function ($store) {
                $totalProduit = $store->products->count();

                return [
                    'name' => $store->name,
                    'totalProduit' => $totalProduit,
                ];
            })
            ->sortByDesc('totalProduit')
            ->take(5)
            ->values();

        $labels = $storages->pluck('name')->toArray();
        $totalProduit = $storages->pluck('totalProduit')->toArray();

        return [
            'chart' => [
                'type' => 'bar',
                'height' => 300,
            ],
            'series' => [
                [
                    'name' => 'Zone de stockage',
                    'data' => $totalProduit,
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
