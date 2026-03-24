<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Receipt extends Model
{
    protected $primaryKey = 'id_receipt';

    protected $fillable = [
        'id_invoice',
        'id_customer',
        'total',
        'date_receipt',
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'id_invoice', 'id_invoice');
    }
}
