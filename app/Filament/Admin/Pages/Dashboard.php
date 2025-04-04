<?php

namespace App\Filament\Admin\Pages;

use App\Filament\Admin\Widgets\EnhancedStatsOverviewWidget;
use App\Filament\Admin\Widgets\LatestActivitiesWidget;
use App\Filament\Admin\Widgets\LatestShopsWidget;
use App\Filament\Admin\Widgets\ProductsPerShopHeatmapWidget;
use App\Filament\Admin\Widgets\SubscriptionsOverTimeChart;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-home';

    protected static string $view = 'filament.admin.pages.dashboard';

    public static function getNavigationLabel(): string
    {
        return 'Tableau de bord';
    }

    public static function getNavigationGroup(): ?string
    {
        return null;
    }

    public function getTitle(): string
    {
        return 'Tableau de bord administrateur';
    }

    public function getWidgets(): array
    {
        return [
            EnhancedStatsOverviewWidget::class,
            SubscriptionsOverTimeChart::class,
            ProductsPerShopHeatmapWidget::class,
            LatestShopsWidget::class,
            LatestActivitiesWidget::class,
        ];
    }
}
