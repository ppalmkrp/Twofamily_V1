<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quotation extends Model
{
    protected $primaryKey = 'id_quot';

    protected $fillable = [
        'id_customer',
        'date_quot',
        'subtotal',
        'discount',
        'total_amount',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'id_customer', 'id_customer');
    }

    public function details()
    {
        return $this->hasMany(QuotationDetail::class, 'id_quot', 'id_quot');
    }
}