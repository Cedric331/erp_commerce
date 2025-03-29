<?php

namespace App\Providers\Filament;

use Althinect\FilamentSpatieRolesPermissions\FilamentSpatieRolesPermissionsPlugin;
use App\Filament\Pages\Support;
use App\Filament\Resources\Tenancy\ShopEdit;
use App\Filament\Resources\Tenancy\ShopRegister;
use App\Http\Middleware\ApplyTenantScopes;
use App\Http\Middleware\CheckTenantOwnership;
use App\Http\Middleware\SyncSpatiePermissionsWithFilamentTenants;
use App\Http\Middleware\VerifyCsrfToken;
use App\Models\Shop;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\MenuItem;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\View\PanelsRenderHook;
use Hugomyb\FilamentErrorMailer\FilamentErrorMailerPlugin;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Leandrocfe\FilamentApexCharts\FilamentApexChartsPlugin;
use Maartenpaauw\Filament\Cashier\Stripe\BillingProvider;
use Swis\Filament\Backgrounds\FilamentBackgroundsPlugin;
use Swis\Filament\Backgrounds\ImageProviders\MyImages;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('app')
            ->default()
            ->path('app')
            ->profile()
            ->login()
            ->loginRouteSlug('login')
            ->passwordReset()
            ->registration()
            ->favicon(asset('/favicon.ico'))
            ->brandLogo(asset('images/logo.png'))
            ->darkModeBrandLogo(asset('images/logo-dark.png'))
            ->brandLogoHeight('5rem')
            ->viteTheme('resources/css/filament/app/theme.css')
            ->unsavedChangesAlerts()
            ->tenantBillingProvider(new BillingProvider)
            ->requiresTenantSubscription()
            ->tenantMenuItems([
                'billing' => MenuItem::make()
                    ->visible(fn (): bool => auth()->user()->isAdministrateurOrGerant()),
            ])
            ->userMenuItems([
                MenuItem::make()
                    ->visible(function (): bool {
                        if (Route::currentRouteName() === 'filament.app.tenant.registration' || ! auth()->user()->hasTenant()) {
                            return false;
                        }

                        return true;
                    })
                    ->label('Contacter le support')
                    ->url(fn (): string => Support::getUrl())
                    ->icon('heroicon-o-envelope'),
            ])
            ->globalSearch()
            ->globalSearchDebounce(100)
            ->renderHook(
                PanelsRenderHook::GLOBAL_SEARCH_BEFORE,
                fn (): string => Blade::render('@livewire(\'create-product\')')
            )
            ->renderHook(
                PanelsRenderHook::GLOBAL_SEARCH_BEFORE,
                fn (): string => Blade::render('@livewire(\'create-stock\')')
            )
            ->renderHook(
                PanelsRenderHook::PAGE_START,
                fn (): string => Blade::render('@livewire(\'banner-subscrib\')')
            )
            ->renderHook(
                PanelsRenderHook::PAGE_END,
                fn (): string => Blade::render('@livewire(\'delete-tenant\')'),
                scopes: [
                    \App\Filament\Resources\Tenancy\ShopEdit::class,
                ],
            )
            ->sidebarCollapsibleOnDesktop()
            ->colors([
                'primary' => '#003366',
                'danger' => '#ff5f5f',
                'info' => '#5AB9EA',
                'success' => '#4279bc',
                'warning' => '#FFC107',
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                //
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
                'Gestion des produits',
                'Gestion des stocks',
                'Gestion des utilisateurs',
                'Rôles et Permissions',
            ])
            ->databaseNotifications()
            ->databaseNotificationsPolling('300s')
            ->tenant(Shop::class, 'slug')
            ->tenantMenu(function () {
                if (Auth::user()->isAdministrateurOrGerant() || Auth::user()->shop()->count() > 1) {
                    return true;
                }

                return false;
            })
            ->tenantRegistration(ShopRegister::class)
            ->tenantProfile(ShopEdit::class)
            ->tenantRoutePrefix('shop')
            ->tenantMiddleware([
                SyncSpatiePermissionsWithFilamentTenants::class,
                ApplyTenantScopes::class,
                CheckTenantOwnership::class,
            ], isPersistent: true)
            ->plugins([
                FilamentSpatieRolesPermissionsPlugin::make(),
                FilamentErrorMailerPlugin::make(),
                FilamentApexChartsPlugin::make(),
                FilamentBackgroundsPlugin::make()
                    ->imageProvider(
                        MyImages::make()
                            ->directory('images/backgrounds')
                    ),
            ]);
    }
}
