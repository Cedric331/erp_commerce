<?php

namespace App\Filament\Resources\ProduitResource\Widgets;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Illuminate\Support\HtmlString;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class ProfitabilityProduct extends ApexChartWidget
{
    /**
     * Chart Id
     *
     * @var string
     */
    protected static string $chartId = 'profitabilityProduct';

    /**
     * Widget Title
     *
     * @var string|null
     */
    protected static ?string $heading = 'Rentabilité du produit (Bénéfices par mois)';


    protected $data = [];

    public $product = null;

    protected function getFooter(): string
    {
        if ($this->product->ventes->count() > 0) {
            return '<p class="dark:text-info-300 text-info-800 text-sm">Ce graphique nécessite d\'avoir renseigné des ventes.</p>';
        } else {
            return '';
        }
    }


    // Filter
    protected function getFormSchema(): array
    {
        return [
            TextInput::make('year')
                ->label('Année')
                ->placeholder('Exemple : 2024')
                ->required()
                ->integer()
                ->live()
                ->default(now()->year)
                ->rule('digits:4')
                ->rule('min:2024')
                ->rule('max:' . (now()->year))
                ->afterStateUpdated(function () {
                    $this->getData();
                }),
        ];
    }

    /**
     * Chart options (series, labels, types, size, animations...)
     * https://apexcharts.com/docs/options
     *
     * @return array
     */
    protected function getOptions(): array
    {
        $this->getData();

        return [
            'chart' => [
                'type' => 'bar',
                'height' => 300,
            ],
            'series' => [
                [
                    'name' => 'Rentabilité',
                    'data' => $this->data,
                ],
            ],
            'xaxis' => [
                'categories' => ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Aout', 'Septembre', 'Octobre', 'Novembe', 'Décembre'],
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
                        'fontSize' => '12px',
                    ],
                ],
            ],
            'colors' => ['#f59e0b', '#10b981', '#3b82f6', '#ef4444', '#6366f1', '#8b5cf6', '#ec4899', '#f43f5e', '#f87171', '#fbbf24', '#34d399', '#6ee7b7'],
            'plotOptions' => [
                'bar' => [
                    'borderRadius' => 3,
                    'horizontal' => false,
                ],
            ],
        ];
    }

    protected function getData()
    {
        $year = $this->filterFormData['year'];

        $product = $this->product;

        // Initialisation d'un tableau pour stocker les bénéfices par mois
        $beneficesParMois = [];

        // Boucler sur chaque mois de l'année
        for ($mois = 1; $mois <= 12; $mois++) {
            // Calculer les dates de début et de fin pour le mois courant
            $dateDebut = now()->month($mois)->year($year)->startOfMonth();
            $dateFin = now()->month($mois)->year($year)->endOfMonth();

            // Récupération des ventes pour le mois courant
            $ventes = $product->ventes()
                ->whereBetween('date_process', [$dateDebut, $dateFin])
                ->get();

            // Calculer le bénéfice pour chaque vente et les additionner pour obtenir le bénéfice total du mois
            $beneficeTotalMois = $ventes->reduce(function ($carry, $vente) {
                $beneficeVente = ($vente->prix_product_ht - $vente->prix_product_buy) * $vente->quantity;
                return $carry + $beneficeVente;
            }, 0);

            // Stocker le bénéfice total du mois dans le tableau
            $beneficesParMois[$mois] = $beneficeTotalMois;
        }


        $this->data = array_values($beneficesParMois);
    }
}
