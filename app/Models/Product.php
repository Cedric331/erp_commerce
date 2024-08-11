<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Product extends Model implements HasMedia
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
        'merchant_id',
        'brand_id',
        'category_id',
        'created_by',
        'updated_by',
    ];

    public function merchant()
    {
        return $this->belongsTo(Merchant::class);
    }

    public function storage()
    {
        return $this->belongsTo(Storage::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
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
