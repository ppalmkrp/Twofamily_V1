<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $primaryKey = 'id_invoice';

    protected $fillable = [
        'id_quotation',
        'id_customer',
        'total',
        'status'
    ];

    // 🔗 ความสัมพันธ์กับรายละเอียดสินค้า
    public function details()
    {
        return $this->hasMany(InvoiceDetail::class, 'id_invoice');
    }

    // 🔗 ลูกค้า
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'id_customer');
    }

    // 🔗 ใบเสนอราคา
    public function quotation()
    {
        return $this->belongsTo(Quotation::class, 'id_quotation', 'id_quot');
    }
}