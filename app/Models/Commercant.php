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

class Commercant extends Model implements HasCurrentTenantLabel
{
    use HasFactory, HasName, Billable, Notifiable;

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

    public function users()
    {
        return $this->belongsToMany(User::class, 'commercant_users', 'commercant_id', 'user_id');
    }

    public function produits()
    {
        return $this->hasMany(Produit::class);
    }

    public function categorieProduits()
    {
        return $this->hasMany(CategorieProduit::class);
    }

    public function stocks()
    {
        return $this->hasMany(Stock::class);
    }

    public function fournisseurs()
    {
        return $this->hasMany(Fournisseur::class);
    }

    public function stockStatuses()
    {
        return $this->hasMany(StockStatus::class);
    }

    public function media()
    {
        return $this->morphMany(Media::class, 'model');
    }

    public function roles()
    {
        return $this->hasMany(Role::class);
    }

    public function permissions()
    {
        return $this->hasMany(Permission::class);
    }

    public function storages()
    {
        return $this->hasMany(Storage::class);
    }

}
