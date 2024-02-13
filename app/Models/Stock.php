<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;

    protected $fillable = [
        'commercant_id',
        'produit_id',
        'stock_status_id',
        'quantity',
        'status',
        'note',
        'scheduled_date',
    ];

    protected static ?string $tenantRelationshipName = 'produit';


    public function produit()
    {
        return $this->belongsTo(Produit::class, 'produit_id');
    }

    public function stockStatus()
    {
        return $this->belongsTo(StockStatus::class, 'stock_status_id');
    }

    public function commercant()
    {
        return $this->belongsTo(Commercant::class, 'commercant_id');
    }

    public function getScheduledDateAttribute($value)
    {
        return $value ? $this->asDateTime($value)->format('d/m/Y') : null;
    }
}
