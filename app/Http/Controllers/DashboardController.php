<?php

namespace App\Http\Controllers;

use App\Models\Truck;
use App\Models\Driver;

class DashboardController extends Controller
{
    public function index()
    {
        $truckCounts = [
            'total'       => Truck::count(),
            'active'      => Truck::where('status_truck', 'active')->count(),
            'maintenance' => Truck::where('status_truck', 'maintenance')->count(),
            'retired'     => Truck::where('status_truck', 'retired')->count(),
        ];

        $driversTotal  = Driver::count();

        // ไม่พึ่ง created_at ของ trucks
        $latestTrucks  = Truck::select('id_truck', 'brand_truck', 'model_truck', 'status_truck')
            ->limit(5)->get();

        $latestDrivers = Driver::select('id_driver', 'name_driver', 'phone_driver')
            ->orderByDesc('id_driver')->limit(5)->get();

        return view('dashboard', compact('truckCounts', 'driversTotal', 'latestTrucks', 'latestDrivers'));
    }
}
