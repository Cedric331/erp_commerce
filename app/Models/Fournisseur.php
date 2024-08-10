<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fournisseur extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'city',
        'postal_code',
        'country',
        'note',
        'commercant_id',
    ];

    public function commercant()
    {
        return $this->belongsTo(Commercant::class);
    }

    public function produits()
    {
        return $this->hasMany(Produit::class);
    }
}
