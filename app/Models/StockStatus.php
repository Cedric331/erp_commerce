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
        'commercant_id',
    ];

    const TYPE_ENTREE = 'entrée';
    const TYPE_SORTIE = 'sortie';

    public function commercant()
    {
        return $this->belongsTo(Commercant::class);
    }

    public function stocks()
    {
        return $this->hasMany(Stock::class);
    }
}
