<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = ['dentist_id', 'patient_id', 'status', 'delivery_date', 'price', 'service_name', 'is_invoiced', 'via'];

    protected $casts = [
        'delivery_date' => 'date',
        'price' => 'decimal:2',
        'is_invoiced' => 'boolean',
    ];

    public function financialRecords()
    {
        return $this->hasMany(FinancialRecord::class, 'order_id');
    }

    public function dentist()
    {
        return $this->belongsTo(Dentist::class);
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function technicalRecord()
    {
        return $this->hasOne(TechnicalRecord::class);
    }
}
