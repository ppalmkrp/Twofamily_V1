<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TruckMaintenance extends Model
{
    protected $table      = 'truck_maintenances';
    protected $primaryKey = 'id_maintenance';

    protected $fillable = [
        'id_truck',
        'title',
        'detail',
        'garage',
        'cost',
        'start_date',
        'expected_return',
        'finished_date',
    ];

    protected $casts = [
        'start_date'      => 'date',
        'expected_return' => 'date',
        'finished_date'   => 'date',
        'cost'            => 'decimal:2',
    ];

    public function truck()
    {
        return $this->belongsTo(Truck::class, 'id_truck', 'id_truck');
    }

    public function isOngoing(): bool
    {
        return is_null($this->finished_date);
    }
}