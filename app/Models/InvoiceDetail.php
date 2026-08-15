<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceDetail extends Model
{
    protected $fillable = [
        'id_invoice',
        'id_product',
        'quantity',
        'price',
        'total'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'id_product');
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'id_invoice');
    }
}