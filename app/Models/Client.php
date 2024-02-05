<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'telephone',
        'adresse',
        'adresse_2',
        'code_postal',
        'ville',
        'pays',
        'commercant_id',
        'created_by',
        'updated_by',
    ];
}
