<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Truck extends Model
{
    use SoftDeletes;

    protected $table = 'trucks';
    protected $primaryKey = 'id_truck';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_truck',
        'brand_truck',
        'model_truck',
        'year_truck',
        'province_truck',
        'fuel_rate',
        'weight_truck',
        'fuelfactory_truck',
        'status_truck',
    ];

    // ให้ Route Model Binding ใช้ id_truck
    public function getRouteKeyName()
    {
        return 'id_truck';
    }

    public function fuelRecords()
    {
        return $this->hasMany(FuelRecord::class, 'trucks_id_truck', 'id_truck');
    }
}
