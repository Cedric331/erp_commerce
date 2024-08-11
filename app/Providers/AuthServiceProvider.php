<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use App\Models\Category;
use App\Models\Merchant;
use App\Models\Brand;
use App\Models\Permission;
use App\Models\Product;
use App\Models\Role;
use App\Models\Stock;
use App\Models\StockStatus;
use App\Models\Storage;
use App\Models\User;
use App\Policies\CategoryPolicy;
use App\Policies\MerchantPolicy;
use App\Policies\ExportPolicy;
use App\Policies\BrandPolicy;
use App\Policies\PermissionPolicy;
use App\Policies\ProductPolicy;
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
        Product::class => ProductPolicy::class,
        Category::class => CategoryPolicy::class,
        Brand::class => BrandPolicy::class,
        Stock::class => StockPolicy::class,
        StockStatus::class => StockStatusPolicy::class,
        User::class => UserPolicy::class,
        Merchant::class => MerchantPolicy::class,
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
