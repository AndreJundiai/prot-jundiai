<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Dentist extends Model
{
    protected $fillable = ['name', 'cro', 'phone', 'email', 'address', 'specialty', 'notes'];

    public function prices(): HasMany
    {
        return $this->hasMany(DentistPrice::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function financialRecords(): HasMany
    {
        return $this->hasMany(FinancialRecord::class);
    }
}
