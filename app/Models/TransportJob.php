<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransportJob extends Model
{
    protected $fillable = [
        'job_name',
        'start_date',
        'end_date',
        'start_point',
        'destination',
        'distance_km',
        'truck_id',
        'driver_id',
        'customer_id',
    ];

    // ✅ ลูกค้า (รวม soft delete)
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id_customer')
            ->withTrashed();
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class, 'driver_id', 'id_driver');
    }

    public function truck()
    {
        return $this->belongsTo(Truck::class, 'truck_id', 'id_truck');
    }
}
