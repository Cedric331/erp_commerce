<?php

namespace App\Providers\Filament;

use Althinect\FilamentSpatieRolesPermissions\FilamentSpatieRolesPermissionsPlugin;
use App\Filament\Pages\Support;
use App\Filament\Resources\Tenancy\CommercantEdit;
use App\Filament\Resources\Tenancy\CommercantRegister;
use App\Http\Middleware\ApplyTenantScopes;
use App\Http\Middleware\VerifyCsrfToken;
use App\Http\Middleware\SyncSpatiePermissionsWithFilamentTenants;
use App\Models\Commercant;
use Filament\Facades\Filament;
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
            ->path('app')
            ->profile()
            ->login()
            ->loginRouteSlug('login')
            ->passwordReset()
            ->registration()
            ->favicon(asset('/favicon.ico'))
            ->brandLogo(asset('images/logo.png'))
            ->darkModeBrandLogo(asset('images/logo-dark.png'))
            ->viteTheme('resources/css/filament/app/theme.css')
            ->brandLogoHeight('3rem')
            ->font('Poppins')
            ->tenantBillingProvider(new BillingProvider())
//            ->requiresTenantSubscription()
            ->tenantMenuItems([
                'billing' => MenuItem::make()
                    ->visible(fn (): bool => auth()->user()->isAdministrateurOrGerant())
            ])
            ->userMenuItems([
                MenuItem::make()
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
                    \App\Filament\Resources\Tenancy\CommercantEdit::class,
                ],
            )
            ->sidebarCollapsibleOnDesktop()
            ->colors([
                'primary' => '#137863',
                'danger' => '#ff5f5f',
                'info' => '#08493b',
                'success' => '#4279bc',
                'warning' => '#f6c14d',
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
//                Widgets\AccountWidget::class,
//                Widgets\FilamentInfoWidget::class,
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
                'Rôles et Permissions'
            ])
            ->databaseNotifications()
            ->databaseNotificationsPolling('300s')
            ->tenant(Commercant::class, 'slug')
            ->tenantMenu(function () {
                if (Auth::user()->isAdministrateurOrGerant() || Auth::user()->commercant()->count() > 1) {
                    return true;
                }
                return false;
            })
            ->tenantRegistration(CommercantRegister::class)
            ->tenantProfile(CommercantEdit::class)
            ->tenantRoutePrefix('shop')
            ->tenantMiddleware([
                SyncSpatiePermissionsWithFilamentTenants::class,
                ApplyTenantScopes::class,
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
