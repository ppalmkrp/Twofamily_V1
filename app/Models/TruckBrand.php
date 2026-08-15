<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TruckBrand extends Model
{
    use SoftDeletes;

    protected $fillable = ['name_brand'];

    public function models() {
        return $this->hasMany(TruckModel::class, 'truck_brand_id');
    }
}
