<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TechnicalRecord extends Model
{
    protected $fillable = ['order_id', 'teeth', 'material', 'color', 'finish', 'occlusion', 'notes'];

    protected $casts = [
        'teeth' => 'array',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
