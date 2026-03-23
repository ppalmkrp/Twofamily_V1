<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; //  1. Use คลาส SoftDeletes

class TruckModel extends Model
{
    use SoftDeletes; //  2. เรียกใช้งานตรงนี้

    protected $fillable = ['truck_brand_id', 'name_model'];

    public function brand() {
        return $this->belongsTo(TruckBrand::class, 'truck_brand_id');
    }
}
