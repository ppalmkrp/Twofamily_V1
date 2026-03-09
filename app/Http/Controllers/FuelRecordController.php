<?php

namespace App\Http\Controllers;

use App\Models\FuelRecord;
use App\Models\Truck;
use Illuminate\Http\Request;

class FuelRecordController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->q;
        $trucks_id = $request->trucks_id;

        $records = FuelRecord::with('truck')
            ->when($q, fn($query) => $query->where('start_point', 'like', "%$q%")
                ->orWhere('destination', 'like', "%$q%"))
            ->when($trucks_id, fn($query) => $query->where('trucks_id_truck', $trucks_id))
            ->latest()
            ->paginate(10);

        $trucks = Truck::where('status_truck', 'active')->get();

        return view('fuel_records.index', compact('records', 'trucks', 'q', 'trucks_id'));
    }

    public function create()
    {
        $trucks = Truck::where('status_truck', 'active')->get();
        return view('fuel_records.create', compact('trucks'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'date_record' => 'required|date',
            'start_point' => 'required',
            'destination' => 'required',
            'trucks_id_truck' => 'required|exists:trucks,id_truck',
            'distance' => 'nullable|numeric',
            'cost_fuel' => 'nullable|numeric',
            'cost_fuel_total' => 'nullable|numeric'
        ]);

        FuelRecord::create([
            'date_record' => $request->date_record,
            'start_point' => $request->start_point,
            'destination' => $request->destination,
            'trucks_id_truck' => $request->trucks_id_truck,
            'distance' => $request->distance ?: null,
            'cost_fuel' => $request->cost_fuel ?: null,
            'cost_fuel_total' => $request->cost_fuel_total ?: null,
        ]);

        return redirect()->route('fuel_records.index')->with('ok', 'บันทึกข้อมูลเรียบร้อย');
    }


    public function edit(FuelRecord $fuel_record)
    {
        $trucks = Truck::where('status_truck', 'active')->get();
        return view('fuel_records.create', compact('fuel_record', 'trucks'));
    }

    public function update(Request $request, FuelRecord $fuel_record)
    {
        $request->validate([
            'date_record' => 'required|date',
            'start_point' => 'required',
            'destination' => 'required',
            'trucks_id_truck' => 'required|exists:trucks,id_truck',
        ]);

        $fuel_record->update($request->all());
        return redirect()->route('fuel_records.index')->with('ok', 'แก้ไขข้อมูลเรียบร้อย');
    }

    public function destroy(FuelRecord $fuel_record)
    {
        $fuel_record->delete();
        return redirect()->route('fuel_records.index')->with('ok', 'ลบข้อมูลเรียบร้อย');
    }
}
