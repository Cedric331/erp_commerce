<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockStatus extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'color',
        'commercant_id',
    ];

    // Status
    const STATUS_PROGRESS = 'En cours';
    const STATUS_COMPLETED = 'Traité';
    const STATUS_ERROR = 'Erreur';
    const STATUS_CANCELLED = 'Annulé';


    public function commercant()
    {
        return $this->belongsTo(Commercant::class);
    }

    public function stocks()
    {
        return $this->hasMany(Stock::class);
    }
}
