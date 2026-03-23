<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // 1. Use คลาส SoftDeletes

class TruckBrand extends Model
{
    use SoftDeletes; // 2. เรียกใช้งานตรงนี้

    protected $fillable = ['name_brand'];

    public function models() {
        return $this->hasMany(TruckModel::class, 'truck_brand_id');
    }
}
