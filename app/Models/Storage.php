<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Storage extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'note',
        'status',
        'commercant_id',
    ];

    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';

    public function products()
    {
        return $this->hasMany(Produit::class);
    }

    public function commercant()
    {
        return $this->belongsTo(Commercant::class);
    }
}
