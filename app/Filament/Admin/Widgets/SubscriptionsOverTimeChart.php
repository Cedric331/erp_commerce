<?php

namespace App\Filament\Admin\Widgets;

use Carbon\Carbon;
use Laravel\Cashier\Subscription;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class SubscriptionsOverTimeChart extends ApexChartWidget
{
    protected static ?string $chartId = 'subscriptionsOverTimeChart';
    protected static ?string $heading = 'Évolution des abonnements';
    protected static ?int $sort = 2;
    protected static bool $deferLoading = true;
    protected static ?string $loadingIndicator = 'Chargement des données...';

    public ?string $filter = 'year';

    protected function getFormSchema(): array
    {
        return [
            \Filament\Forms\Components\Select::make('filter')
                ->label('Période')
                ->options([
                    'year' => 'Année en cours',
                    'quarter' => 'Trimestre en cours',
                    'month' => 'Mois en cours',
                    'all' => 'Tout le temps',
                ])
                ->default('year')
                ->live(),
        ];
    }

    protected function getOptions(): array
    {
        // Débogage des abonnements en essai
        $trialSubscriptions = \Laravel\Cashier\Subscription::where(function ($query) {
            $query->where('stripe_status', 'trialing')
                ->orWhereNotNull('trial_ends_at');
        })
            ->where(function ($query) {
                $query->whereNull('trial_ends_at')
                    ->orWhere('trial_ends_at', '>', now());
            })
            ->get();

        // Ajouter des informations de débogage au titre du widget
        static::$heading = 'Évolution des abonnements (Essais: ' . $trialSubscriptions->count() . ')';

        $data = $this->getData();

        return [
            'chart' => [
                'type' => 'area',
                'height' => 300,
                'toolbar' => [
                    'show' => true,
                ],
                'zoom' => [
                    'enabled' => true,
                ],
            ],
            'series' => [
                [
                    'name' => 'Abonnements actifs',
                    'data' => $data['active'],
                ],
                [
                    'name' => 'Abonnements en essai',
                    'data' => $data['trial'],
                ],
                [
                    'name' => 'Abonnements annulés',
                    'data' => $data['canceled'],
                ],
            ],
            'xaxis' => [
                'categories' => $data['dates'],
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
            'colors' => ['#4279bc', '#5AB9EA', '#ff5f5f'],
            'stroke' => [
                'curve' => 'smooth',
                'width' => 2,
            ],
            'dataLabels' => [
                'enabled' => false,
            ],
            'tooltip' => [
                'shared' => true,
                'intersect' => false,
            ],
            'grid' => [
                'show' => true,
                'borderColor' => '#90A4AE',
                'strokeDashArray' => 0,
                'position' => 'back',
            ],
            'fill' => [
                'type' => 'gradient',
                'gradient' => [
                    'shadeIntensity' => 1,
                    'opacityFrom' => 0.7,
                    'opacityTo' => 0.9,
                    'stops' => [0, 90, 100],
                ],
            ],
        ];
    }

    private function getData(): array
    {
        try {
            $dates = [];
            $activeData = [];
            $trialData = [];
            $canceledData = [];

            $startDate = $this->getStartDate();
            $endDate = now();
            $interval = $this->getDateInterval();

            $period = new \DatePeriod(
                $startDate,
                $interval,
                $endDate
            );

            foreach ($period as $date) {
                $dates[] = $date->format($this->getDateFormat());

                try {
                    // Abonnements actifs
                    $activeData[] = Subscription::where('stripe_status', 'active')
                        ->whereNull('ends_at')
                        ->where('created_at', '<=', $date)
                        ->count();

                    // Abonnements en essai
                    $trialData[] = Subscription::where(function ($query) use ($date) {
                        $query->where('stripe_status', 'trialing')
                            ->orWhereNotNull('trial_ends_at');
                    })
                        ->where('created_at', '<=', $date)
                        ->where(function ($query) use ($date) {
                            $query->whereNull('trial_ends_at')
                                ->orWhere('trial_ends_at', '>', $date);
                        })
                        ->count();

                    // Abonnements annulés
                    $canceledData[] = Subscription::where(function ($query) use ($date) {
                        $query->where('stripe_status', 'canceled')
                            ->orWhereNotNull('ends_at');
                    })
                        ->where('created_at', '<=', $date)
                        ->count();
                } catch (\Exception $e) {
                    // En cas d'erreur sur une date spécifique, mettre des valeurs par défaut
                    $activeData[] = 0;
                    $trialData[] = 0;
                    $canceledData[] = 0;
                }
            }

            return [
                'dates' => $dates,
                'active' => $activeData,
                'trial' => $trialData,
                'canceled' => $canceledData,
            ];
        } catch (\Exception $e) {
            // En cas d'erreur générale, retourner des données minimales
            return [
                'dates' => ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin'],
                'active' => [0, 0, 0, 0, 0, 0],
                'trial' => [0, 0, 0, 0, 0, 0],
                'canceled' => [0, 0, 0, 0, 0, 0],
            ];
        }
    }

    private function getStartDate(): Carbon
    {
        return match ($this->filter) {
            'year' => now()->startOfYear(),
            'quarter' => now()->startOfQuarter(),
            'month' => now()->startOfMonth(),
            'all' => now()->subYear(2), // Limite à 2 ans en arrière pour éviter trop de données
            default => now()->startOfYear(),
        };
    }

    private function getDateInterval(): \DateInterval
    {
        return match ($this->filter) {
            'year' => new \DateInterval('P1M'), // Mensuel
            'quarter' => new \DateInterval('P1W'), // Hebdomadaire
            'month' => new \DateInterval('P1D'), // Journalier
            'all' => new \DateInterval('P1M'), // Mensuel
            default => new \DateInterval('P1M'),
        };
    }

    private function getDateFormat(): string
    {
        return match ($this->filter) {
            'year' => 'M Y',
            'quarter' => 'd M',
            'month' => 'd',
            'all' => 'M Y',
            default => 'M Y',
        };
    }
}
