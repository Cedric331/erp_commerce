<?php

namespace App\Filament\Widgets;

use App\Models\Product;
use App\Models\StockStatus;
use Filament\Facades\Filament;
use Filament\Forms\Components\Select;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class ABestProductChart extends ApexChartWidget
{
    protected static ?string $chartId = 'bestProductChart';

    protected static ?string $heading = 'Meilleurs produits';

    protected static bool $deferLoading = true;

    protected static ?string $loadingIndicator = 'Chargement des données...';

    public ?string $filter = StockStatus::STATUS_VENTE;

    public ?int $filterStatusId = null;

    protected function getFormSchema(): array
    {
        $status = StockStatus::query()
            ->where('shop_id', Filament::getTenant()?->id)
            ->pluck('name', 'id')
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
     */
    protected function getOptions(): array
    {
        $activeFilter = $this->filterStatusId ? StockStatus::find($this->filterStatusId)?->name : StockStatus::STATUS_VENTE;

        $products = Product::query()
            ->where('shop_id', Filament::getTenant()?->id)
            ->withSum(['stocks as sales_total' => function ($query) use ($activeFilter) {
                $query->whereHas('stockStatus', function ($query) use ($activeFilter) {
                    $query->where('name', $activeFilter);
                });
            }], 'quantity')
            ->orderByDesc('sales_total')
            ->limit(5)
            ->get();

        $labels = $products->pluck('name')->toArray();
        $salesTotals = $products->pluck('sales_total')->toArray();

        return [
            'chart' => [
                'type' => 'bar',
                'height' => 300,
            ],
            'series' => [
                [
                    'name' => 'Totales',
                    'data' => $salesTotals,
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
