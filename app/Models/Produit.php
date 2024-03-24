<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Produit extends Model implements HasMedia
{
    use InteractsWithMedia, HasFactory
;

    protected $fillable = [
        'nom',
        'type',
        'description',
        'reference',
        'stock',
        'stock_alert',
        'prix_buy',
        'prix_ht',
        'prix_ttc',
        'tva',
        'stock',
        'storage_id',
        'commercant_id',
        'fournisseur_id',
        'categorie_id',
        'created_by',
        'updated_by',
    ];

    public function commercant()
    {
        return $this->belongsTo(Commercant::class);
    }

    public function storage()
    {
        return $this->belongsTo(Storage::class);
    }

    public function fournisseur()
    {
        return $this->belongsTo(Fournisseur::class);
    }

    public function categorie()
    {
        return $this->belongsTo(CategorieProduit::class, 'categorie_id', 'id');
    }

    public function stocks()
    {
        return $this->hasMany(Stock::class);
    }

    public function ventes()
    {
        return $this->stocks()
            ->whereHas('stockStatus', function ($query) {
                $query->where('name', StockStatus::STATUS_VENTE);
            });
    }
}
