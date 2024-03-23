<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use App\Models\CategorieProduit;
use App\Models\Commercant;
use App\Models\Fournisseur;
use App\Models\Permission;
use App\Models\Produit;
use App\Models\Role;
use App\Models\Stock;
use App\Models\StockStatus;
use App\Models\Storage;
use App\Models\User;
use App\Policies\CategorieProduitPolicy;
use App\Policies\CommercantPolicy;
use App\Policies\ExportPolicy;
use App\Policies\FournisseurPolicy;
use App\Policies\PermissionPolicy;
use App\Policies\ProduitPolicy;
use App\Policies\RolePolicy;
use App\Policies\StockPolicy;
use App\Policies\StockStatusPolicy;
use App\Policies\StoragePolicy;
use App\Policies\UserPolicy;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Produit::class => ProduitPolicy::class,
        CategorieProduit::class => CategorieProduitPolicy::class,
        Fournisseur::class => FournisseurPolicy::class,
        Stock::class => StockPolicy::class,
        StockStatus::class => StockStatusPolicy::class,
        User::class => UserPolicy::class,
        Commercant::class => CommercantPolicy::class,
        Role::class => RolePolicy::class,
        Permission::class => PermissionPolicy::class,
        Storage::class => StoragePolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
    }
}
