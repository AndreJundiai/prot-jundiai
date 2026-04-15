<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FinancialRecord extends Model
{
    protected $fillable = ['dentist_id', 'order_id', 'amount', 'type', 'description', 'patient_name', 'transaction_date'];

    protected $casts = [
        'transaction_date' => 'date',
        'amount' => 'decimal:2',
    ];

    public function dentist()
    {
        return $this->belongsTo(Dentist::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
