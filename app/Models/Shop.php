<?php

namespace App\Models;

use Filament\Actions\Concerns\HasName;
use Filament\Models\Contracts\HasCurrentTenantLabel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Cashier\Billable;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class Shop extends Model implements HasCurrentTenantLabel
{
    use Billable, HasFactory, HasName, Notifiable;

    protected $fillable = [
        'enseigne',
        'email',
        'slug',
        'siret',
        'telephone',
        'adresse',
        'adresse_2',
        'code_postal',
        'ville',
        'pays',
        'user_id',
    ];

    public function getCurrentTenantLabel(): string
    {
        return $this->subscribed('default') ? 'Abonnement activé' : 'Abonnement inactif';
    }

    public function getNameAttribute(): string
    {
        return $this->enseigne;
    }

    public function getFilamentName(): string
    {
        return strtolower($this->enseigne);
    }

    public function users(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(User::class, 'shop_users', 'shop_id', 'user_id');
    }

    public function products(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function categories(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Category::class);
    }

    public function stocks(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Stock::class);
    }

    public function brands(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Brand::class);
    }

    public function stockStatuses(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(StockStatus::class);
    }

    public function media(): \Illuminate\Database\Eloquent\Relations\MorphMany
    {
        return $this->morphMany(Media::class, 'model');
    }

    public function roles(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Role::class);
    }

    public function permissions(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Permission::class);
    }

    public function storages(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Storage::class);
    }
}
