<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ShopUser extends Pivot
{
    use HasFactory;

    protected $fillable = [
        'shop_id',
        'user_id',
    ];
}
