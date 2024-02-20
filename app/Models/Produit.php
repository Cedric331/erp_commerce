<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Produit extends Model implements HasMedia
{
    use InteractsWithMedia, HasFactory;

    protected $fillable = [
        'nom',
        'description',
        'reference',
        'stock',
        'stock_alert',
        'prix_ht',
        'prix_ttc',
        'tva',
        'stock',
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
}
