<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Driver extends Model
{
    use SoftDeletes;

    protected $primaryKey = 'id_driver';

    protected $fillable = [
        'fname_driver',
        'lname_driver',
        'address_detail',
        'province',
        'district',
        'subdistrict',
        'zipcode',
        'phone_driver',
        'citizenid_driver',
        'citizen_image',
    ];
}
