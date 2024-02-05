<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailsVente extends Model
{
    use HasFactory;

    protected $fillable = [
        'quantite',
        'prix_ht',
        'prix_ttc',
        'tva',
        'id_vente',
        'id_produit',
    ];
}
