<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Produit extends Model implements HasMedia
{
    use InteractsWithMedia, HasFactory;

    protected $fillable = [
        'nom',
        'description',
        'reference',
        'ean',
        'prix_ht',
        'prix_ttc',
        'tva',
        'stock',
        'id_commercant',
        'created_by',
        'updated_by',
    ];

    public function commercant()
    {
        return $this->belongsTo(Commercant::class);
    }
}
