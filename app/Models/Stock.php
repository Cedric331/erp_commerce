<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;

    protected $fillable = [
        'merchant_id',
        'product_id',
        'stock_status_id',
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


    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function stockStatus()
    {
        return $this->belongsTo(StockStatus::class, 'stock_status_id');
    }

    public function merchant()
    {
        return $this->belongsTo(Merchant::class, 'merchant_id');
    }

    public function getFormattedScheduledDateAttribute()
    {
        return $this->scheduled_date ? $this->scheduled_date->format('d/m/Y') : null;
    }
}
