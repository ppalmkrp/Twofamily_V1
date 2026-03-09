<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InvoiceDetail extends Model
{
    use SoftDeletes;
    protected $primaryKey = 'id_iv_detail';
    protected $fillable = [
        'quantity', 'unit', 'total_price',
        'subtotal_quot', 'discount_quot',
        'tax_quot', 'total_amount',
        'payment_terms', 'Products_id_product', 'Invoice_id_iv'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'Products_id_product', 'id_product');
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'Invoice_id_iv', 'id_iv');
    }
}
