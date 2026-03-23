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

        $latestTrucks  = Truck::with(['brand', 'model'])
            ->select('id_truck', 'truck_brand_id', 'truck_model_id', 'status_truck')
            ->limit(5)->get();

        //  แก้ไขตรงนี้: เปลี่ยนมาดึง fname_driver และ lname_driver แทน
        $latestDrivers = Driver::select('id_driver', 'fname_driver', 'lname_driver', 'phone_driver')
            ->orderByDesc('id_driver')->limit(5)->get();

        return view('dashboard', compact('truckCounts', 'driversTotal', 'latestTrucks', 'latestDrivers'));
    }
}
