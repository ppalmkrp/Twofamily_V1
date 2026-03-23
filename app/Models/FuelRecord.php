<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FuelRecord extends Model
{
    use SoftDeletes;

    protected $primaryKey = 'id_fuel_record';

    protected $fillable = [
        'date_record',
        'start_point',
        'start_detail',
        'destination',
        'destination_detail',
        'age_truck',
        'depreciation',
        'current_weight',
        'max_load',
        'distance',
        'cost_fuel',
        'trucks_id_truck',
        'cost_fuel_total'
    ];

    // ความสัมพันธ์กับ Truck
    public function truck()
    {
        return $this->belongsTo(Truck::class, 'trucks_id_truck', 'id_truck');
    }
}
