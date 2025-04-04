<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;

    protected $fillable = [
        'shop_id',
        'product_id',
        'stock_status_id',
        'price_buy',
        'price_ht',
        'price_ttc',
        'quantity',
        'date_process',
        'note',
        'scheduled_date',
    ];

    protected static ?string $tenantRelationshipName = 'product';

    protected $casts = [
        'scheduled_date' => 'datetime',
        'date_process' => 'datetime',
    ];

    public function product(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function stockStatus(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(StockStatus::class, 'stock_status_id');
    }

    public function shop(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Shop::class, 'shop_id');
    }

    public function getFormattedScheduledDateAttribute()
    {
        return $this->scheduled_date ? $this->scheduled_date->format('d/m/Y') : null;
    }
}
