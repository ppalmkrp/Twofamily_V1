<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
    use SoftDeletes;

    protected $primaryKey = 'id_iv';

    // ✅ เพิ่มส่วนนี้
    protected $fillable = [
        'date_iv',
        'end_iv',
        'status',
        'Customers_id_customer',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'Customers_id_customer', 'id_customer');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'Users_id_user');
    }

    public function details()
    {
        return $this->hasMany(InvoiceDetail::class, 'Invoice_id_iv', 'id_iv');
    }
}