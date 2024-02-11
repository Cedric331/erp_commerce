<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategorieProduit extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'alert_stock',
        'commercant_id',
    ];

    public function produits()
    {
        return $this->hasMany(Produit::class, 'categorie_id');
    }

    public function commercant()
    {
        return $this->belongsTo(Commercant::class);
    }
}
