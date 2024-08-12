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
        'shop_id',
    ];

    const TYPE_ENTREE = 'entrée';
    const TYPE_SORTIE = 'sortie';

    const STATUS_VENTE = 'Vente';
    const STATUS_LIVRAISON = 'Livraison';
    const STATUS_PERTE = 'Perte';

    const COLOR_VERTE = '#008000';
    const COLOR_ORANGE = '#FFA500';
    const COLOR_ROUGE = '#FF0000';

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    public function stocks()
    {
        return $this->hasMany(Stock::class);
    }
}
