<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class MerchantUser extends Pivot
{
    use HasFactory;

    protected $fillable = [
        'merchant_id',
        'user_id',
    ];
}
