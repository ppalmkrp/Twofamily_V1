<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuotationDetail extends Model
{
    protected $primaryKey = 'id_detail';

    protected $fillable = [
        'id_quot',
        'id_product',
        'quantity',
        'price_per_unit',
        'total_price',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'id_product', 'id_product');
    }

    public function quotation()
    {
        return $this->belongsTo(Quotation::class, 'id_quot', 'id_quot');
    }
}