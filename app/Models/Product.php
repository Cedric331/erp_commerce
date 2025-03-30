<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Product extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'name',
        'type',
        'description',
        'reference',
        'barcode',
        'price_buy',
        'price_ht',
        'price_ttc',
        'tva',
        'size',
        'color',
        'weight',
        'attributes',
        'stock',
        'stock_alert',
        'unit',
        'status',
        'storage_id',
        'shop_id',
        'brand_id',
        'category_id',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'price_ht' => 'decimal:2',
        'price_ttc' => 'decimal:2',
        'price_buy' => 'decimal:2',
        'tva' => 'decimal:2',
        'stock' => 'decimal:2',
        'stock_alert' => 'decimal:2',
        'attributes' => 'array',
    ];

    public function priceHistory(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ProductPriceHistory::class);
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function sales(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Stock::class)->whereHas('stockStatus', function ($query) {
            $query->where('name', 'Vente');
        });
    }

    public function brand(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function category(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function storage(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Storage::class);
    }

    public function shop(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }

    public function stocks(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Stock::class);
    }

    public function creator(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
