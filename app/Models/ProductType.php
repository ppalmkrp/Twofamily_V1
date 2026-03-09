<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductType extends Model
{
    use SoftDeletes;

    protected $table = 'product_types';
    protected $primaryKey = 'id_product_type';

    protected $fillable = [
        'name_product_type',
    ];

    public function products()
    {
        return $this->hasMany(
            Product::class,
            'product_type_id',   // FK ใน products
            'id_product_type'    // PK ใน product_types
        );
    }
}
