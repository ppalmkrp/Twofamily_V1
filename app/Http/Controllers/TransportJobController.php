<?php

namespace App\Http\Controllers;

use App\Models\TransportJob;
use App\Models\Customer;
use App\Models\Truck;
use App\Models\Driver;
use Illuminate\Http\Request;

class TransportJobController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->q;

        $jobs = TransportJob::with(['customer', 'truck', 'driver'])
            ->when($q, function ($query) use ($q) {
                $query->whereHas('customer', function ($c) use ($q) {
                    $c->where('name_customer', 'like', "%{$q}%");
                })
                    ->orWhere('start_point', 'like', "%{$q}%")
                    ->orWhere('destination', 'like', "%{$q}%");
            })
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('transport_jobs.index', compact('jobs'));
    }

    public function create()
    {
        return view('transport_jobs.create', [
            'customers' => Customer::all(),
            'trucks'    => Truck::all(),
            'drivers'   => Driver::all(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_id'  => 'required|exists:customers,id_customer',
            'truck_id'     => 'required|exists:trucks,id_truck',
            'driver_id'    => 'required|exists:drivers,id_driver',
            'start_date'   => 'required|date',
            'end_date'     => 'nullable|date|after_or_equal:start_date',
            'start_point'  => 'required|string|max:255',
            'destination'  => 'required|string|max:255',
            'distance_km'  => 'required|numeric|min:0',
        ]);

        TransportJob::create($request->only([
            'customer_id',
            'truck_id',
            'driver_id',
            'start_date',
            'end_date',
            'start_point',
            'destination',
            'distance_km',
        ]));

        return redirect()
            ->route('transport-jobs.index')
            ->with('ok', 'เพิ่มแผนงานเรียบร้อย');
    }

    public function show(TransportJob $transport_job)
    {
        $transport_job->load(['customer', 'truck', 'driver']);

        return view('transport_jobs.show', [
            'job' => $transport_job
        ]);
    }

    public function edit(TransportJob $transport_job)
    {
        return view('transport_jobs.edit', [
            'job'       => $transport_job,
            'customers' => Customer::all(),
            'trucks'    => Truck::all(),
            'drivers'   => Driver::all(),
        ]);
    }

    public function update(Request $request, TransportJob $transport_job)
    {
        $request->validate([
            'customer_id'  => 'required|exists:customers,id_customer',
            'truck_id'     => 'required|exists:trucks,id_truck',
            'driver_id'    => 'required|exists:drivers,id_driver',
            'start_date'   => 'required|date',
            'end_date'     => 'nullable|date|after_or_equal:start_date',
            'start_point'  => 'required|string|max:255',
            'destination'  => 'required|string|max:255',
            'distance_km'  => 'required|numeric|min:0',
        ]);

        $transport_job->update($request->only([
            'customer_id',
            'truck_id',
            'driver_id',
            'start_date',
            'end_date',
            'start_point',
            'destination',
            'distance_km',
        ]));

        return redirect()
            ->route('transport-jobs.index')
            ->with('ok', 'แก้ไขแผนงานเรียบร้อย');
    }

    public function destroy(TransportJob $transport_job)
    {
        $transport_job->delete();

        return redirect()
            ->route('transport-jobs.index')
            ->with('ok', 'ลบแผนงานแล้ว');
    }
}
