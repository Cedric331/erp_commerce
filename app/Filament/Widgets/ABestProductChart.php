<?php

namespace App\Filament\Widgets;

use App\Models\Produit;
use App\Models\StockStatus;
use Filament\Facades\Filament;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Get;
use Filament\Widgets\Widget;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class ABestProductChart extends ApexChartWidget
{
    protected static string $chartId = 'bestProductChart';

    protected static ?string $heading = 'Meilleurs produits par Statut';
    protected static bool $deferLoading = true;

    protected static ?string $loadingIndicator = 'Chargement des données...';

    public ?string $filter = StockStatus::STATUS_VENTE;
    public ?int $filterStatusId = null;

    protected function getFormSchema(): array
    {
        $status = StockStatus::query()
            ->where('commercant_id', Filament::getTenant()?->id)
            ->pluck('name' , 'id')
            ->toArray();

        return [
            Select::make('Statut')
                ->label('Filtre par statut')
                ->default(function () {
                    return StockStatus::where('name', StockStatus::STATUS_VENTE)->first()?->id;
                })
                ->options($status)
                ->live()
                ->afterStateUpdated(fn ($state) => $this->filterStatusId = $state),

        ];
    }

    /**
     * Prépare les données pour le graphique ApexCharts.
     *
     * @return array
     */
    protected function getOptions(): array
    {
        $activeFilter = $this->filterStatusId ? StockStatus::find($this->filterStatusId)?->name : StockStatus::STATUS_VENTE;

        $produits = Produit::query()
            ->where('commercant_id', Filament::getTenant()?->id)
            ->withSum(['stocks as ventes_total' => function ($query) use ($activeFilter) {
                $query->whereHas('stockStatus', function ($query) use ($activeFilter) {
                    $query->where('name', $activeFilter);
                });
            }], 'quantity')
            ->orderByDesc('ventes_total')
            ->limit(5)
            ->get();

        $labels = $produits->pluck('nom')->toArray();
        $ventesTotales = $produits->pluck('ventes_total')->toArray();

        return [
            'chart' => [
                'type' => 'bar',
                'height' => 300
            ],
            'series' => [
                [
                    'name' => 'Totales',
                    'data' => $ventesTotales,
                ],
            ],
            'xaxis' => [
                'categories' => $labels,
                'labels' => [
                    'style' => [
                        'fontFamily' => 'inherit',
                    ],
                ],
            ],
            'yaxis' => [
                'labels' => [
                    'style' => [
                        'fontFamily' => 'inherit',
                    ],
                ],
            ],
            'colors' => ['#5aa192', '#f4b30d', '#f4503d', '#3d5af4', '#df45df'],
            'plotOptions' => [
                'bar' => [
                    'borderRadius' => 1,
                    'horizontal' => false,
                    'barHeight' => '100%',
                ],
            ],
        ];
    }
}

