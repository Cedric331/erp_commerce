<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeclinationProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'reference',
        'stock',
        'stock_alert',
        'prix_ht',
        'prix_ttc',
        'prix_buy',
        'shop_id',
        'product_id',
    ];

    public function shop(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }

    public function product(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
