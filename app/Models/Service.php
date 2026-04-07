<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = ['name', 'category', 'base_price'];

    protected $casts = [
        'base_price' => 'decimal:2',
    ];
}
