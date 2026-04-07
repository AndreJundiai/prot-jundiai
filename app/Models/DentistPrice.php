<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DentistPrice extends Model
{
    protected $fillable = [
        'dentist_id',
        'service_id',
        'price'
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    public function dentist(): BelongsTo
    {
        return $this->belongsTo(Dentist::class);
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }
}
