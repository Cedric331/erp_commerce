<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\ABestProductChart;
use App\Filament\Widgets\BestBrandChart;
use App\Filament\Widgets\BestCategoryChart;
use App\Filament\Widgets\BestStorageChart;
use App\Filament\Widgets\CalendarWidget;
use App\Filament\Widgets\StoreStatsWidget;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-home';

    protected static ?string $navigationLabel = 'Tableau de bord';

    protected static ?string $title = 'Tableau de bord';

    protected static ?int $navigationSort = 1;

    public function getWidgets(): array
    {
        return [
            StoreStatsWidget::class,
            CalendarWidget::class,
            ABestProductChart::class,
            BestCategoryChart::class,
            BestBrandChart::class,
            BestStorageChart::class,
        ];
    }
}
