<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class CommercantUser extends Pivot
{
    use HasFactory;

    protected $fillable = [
        'commercant_id',
        'user_id',
    ];
}
