<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockStatus extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'color',
        'merchant_id',
    ];

    const TYPE_ENTREE = 'entrée';
    const TYPE_SORTIE = 'sortie';

    const STATUS_VENTE = 'Vente';
    const STATUS_LIVRAISON = 'Livraison';
    const STATUS_PERTE = 'Perte';

    public function merchant()
    {
        return $this->belongsTo(Merchant::class);
    }

    public function stocks()
    {
        return $this->hasMany(Stock::class);
    }
}
