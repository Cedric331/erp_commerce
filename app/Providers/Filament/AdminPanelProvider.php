<?php

namespace App\Providers\Filament;

use Althinect\FilamentSpatieRolesPermissions\FilamentSpatieRolesPermissionsPlugin;
use App\Filament\Pages\Support;
use App\Filament\Widgets\BestBrandChart;
use App\Filament\Widgets\BestCategoryChart;
use App\Filament\Widgets\BestProductChart;
use App\Filament\Widgets\BestStorageChart;
use App\Filament\Widgets\CalendarWidget;
use App\Http\Middleware\Administrateur;
use App\Http\Middleware\VerifyCsrfToken;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\MenuItem;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Hugomyb\FilamentErrorMailer\FilamentErrorMailerPlugin;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Leandrocfe\FilamentApexCharts\FilamentApexChartsPlugin;
use Saade\FilamentFullCalendar\FilamentFullCalendarPlugin;
use Swis\Filament\Backgrounds\FilamentBackgroundsPlugin;
use Swis\Filament\Backgrounds\ImageProviders\MyImages;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('admin')
            ->path('admin')
            ->login()
            ->loginRouteSlug('login')
            ->profile()
            ->registration(false)
            ->passwordReset(false)
            ->favicon(asset('/favicon.ico'))
            ->brandLogo(asset('images/logo.png'))
            ->darkModeBrandLogo(asset('images/logo-dark.png'))
            ->brandLogoHeight('5rem')
            ->viteTheme('resources/css/filament/app/theme.css')
            ->unsavedChangesAlerts()
//            ->userMenuItems([
//                MenuItem::make()
//                    ->label('Retour à l\'application')
//                    ->url(fn (): string => route('filament.app.pages.dashboard'))
//                    ->icon('heroicon-o-arrow-left-circle'),
//            ])
            ->globalSearch()
            ->globalSearchDebounce(100)
            ->sidebarCollapsibleOnDesktop()
            ->colors([
                'primary' => '#003366',
                'danger' => '#ff5f5f',
                'info' => '#5AB9EA',
                'success' => '#4279bc',
                'warning' => '#FFC107',
            ])
            ->discoverResources(in: app_path('Filament/Admin/Resources'), for: 'App\\Filament\\Admin\\Resources')
            ->discoverPages(in: app_path('Filament/Admin/Pages'), for: 'App\\Filament\\Admin\\Pages')
            ->discoverWidgets(in: app_path('Filament/Admin/Widgets'), for: 'App\\Filament\\Admin\\Widgets')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->databaseTransactions()
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->navigationGroups([
                'Gestion des utilisateurs',
                'Gestion des commerces',
                'Rôles et Permissions',
                'Abonnements',
            ])
            ->plugins([
                FilamentSpatieRolesPermissionsPlugin::make(),
                FilamentErrorMailerPlugin::make(),
                FilamentApexChartsPlugin::make(),
                FilamentBackgroundsPlugin::make()
                    ->imageProvider(
                        MyImages::make()
                            ->directory('images/backgrounds')
                    ),
                FilamentFullCalendarPlugin::make()
                    ->timezone('Europe/Paris')
                    ->locale('fr')
                    ->plugins([
                        'dayGrid',
                        'timeGrid',
                        'list',
                        'interaction',
                    ]),
            ]);
    }
}
