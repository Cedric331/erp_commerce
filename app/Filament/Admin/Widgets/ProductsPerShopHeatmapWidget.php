<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Product;
use App\Models\Shop;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class ProductsPerShopHeatmapWidget extends ApexChartWidget
{
    protected static ?string $chartId = 'productsPerShopHeatmap';
    protected static ?string $heading = 'Répartition des produits par commerce';
    protected static ?int $sort = 3;
    protected static bool $deferLoading = true;
    protected static ?string $loadingIndicator = 'Chargement des données...';

    public ?int $limit = 10;

    protected function getFormSchema(): array
    {
        return [
            \Filament\Forms\Components\Select::make('limit')
                ->label('Nombre de commerces')
                ->options([
                    5 => 'Top 5',
                    10 => 'Top 10',
                    15 => 'Top 15',
                    20 => 'Top 20',
                    -1 => 'Tous',
                ])
                ->default(10)
                ->live(),
        ];
    }

    protected function getOptions(): array
    {
        $data = $this->getData();

        return [
            'chart' => [
                'type' => 'heatmap',
                'height' => 350,
                'toolbar' => [
                    'show' => true,
                ],
            ],
            'series' => $data['series'],
            'xaxis' => [
                'categories' => $data['categories'],
                'labels' => [
                    'style' => [
                        'fontFamily' => 'inherit',
                        'cssClass' => 'text-xs font-medium',
                    ],
                    'rotate' => -45,
                ],
            ],
            'dataLabels' => [
                'enabled' => false,
            ],
            'colors' => ['#003366'],
            'title' => [
                'text' => 'Nombre de produits par catégorie et par commerce',
                'align' => 'center',
                'style' => [
                    'fontFamily' => 'inherit',
                ],
            ],
            'tooltip' => [
                'enabled' => true,
                'y' => [
                    'formatter' => 'function(value) { return value + " produits" }',
                ],
            ],
            'plotOptions' => [
                'heatmap' => [
                    'radius' => 3,
                    'enableShades' => true,
                    'shadeIntensity' => 0.5,
                    'colorScale' => [
                        'ranges' => [
                            [
                                'from' => 0,
                                'to' => 10,
                                'color' => '#5AB9EA',
                                'name' => 'Faible',
                            ],
                            [
                                'from' => 11,
                                'to' => 30,
                                'color' => '#4279bc',
                                'name' => 'Moyen',
                            ],
                            [
                                'from' => 31,
                                'to' => 50,
                                'color' => '#003366',
                                'name' => 'Élevé',
                            ],
                            [
                                'from' => 51,
                                'to' => 1000,
                                'color' => '#001F3F',
                                'name' => 'Très élevé',
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }

    private function getData(): array
    {
        try {
            // Récupérer les commerces avec le plus de produits
            $shops = Shop::withCount('products')
                ->orderByDesc('products_count')
                ->when($this->limit > 0, function ($query) {
                    return $query->limit($this->limit);
                })
                ->get();

            // Récupérer les catégories les plus utilisées
            $categories = \App\Models\Category::withCount('products')
                ->orderByDesc('products_count')
                ->limit(5)
                ->get();

            $series = [];
            $categoryNames = $categories->pluck('name')->toArray();

            // Si aucune catégorie n'est trouvée, retourner des données vides
            if ($categories->isEmpty()) {
                return [
                    'series' => [],
                    'categories' => ['Aucune catégorie'],
                ];
            }

            // Pour chaque commerce, créer une série de données
            foreach ($shops as $shop) {
                $data = [];

                // Pour chaque catégorie, compter le nombre de produits
                foreach ($categories as $category) {
                    $count = Product::where('shop_id', $shop->id)
                        ->where('category_id', $category->id)
                        ->count();

                    $data[] = $count;
                }

                $series[] = [
                    'name' => $shop->enseigne,
                    'data' => $data,
                ];
            }

            return [
                'series' => $series,
                'categories' => $categoryNames,
            ];
        } catch (\Exception $e) {
            // En cas d'erreur, retourner des données vides
            return [
                'series' => [],
                'categories' => ['Erreur de chargement'],
            ];
        }
    }
}
