<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_produit',
        'quantity',
        'status',
        'note',
        'date_add',
    ];

    // arrivage, vente, perte, retour client, retour fournisseur, sortie
    const STATUS_ARRIVAL = 'arrival';
    const STATUS_SALE = 'sale';
    const STATUS_LOSS = 'loss';
    const STATUS_BACK_CUSTOMER = 'back_customer';
    const STATUS_BACK_BRAND = 'back_brand';
    const STATUS_OUTPUT = 'output';


    public function produit()
    {
        return $this->belongsTo(Produit::class, 'id_produit');
    }
}
