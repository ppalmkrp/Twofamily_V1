<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use App\Models\FuelRecord;
use App\Models\Truck;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\Camp;

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

        $trucks = Truck::orderBy('id_truck')->get();

        return view('fuel_records.index', compact('records', 'trucks', 'q', 'trucks_id'));
    }

    public function create()
    {
        $trucks = Truck::available()->orderBy('id_truck')->get();

        // เฉพาะแคมป์ที่กรอกพิกัดไว้แล้ว
        $camps = Camp::active()
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->with('customer')
            ->orderBy('name_camp')
            ->get();

        $dieselPrice = '';

        try {
            $response = Http::get('https://oil-price.bangchak.co.th/apioilprice2/th');

            if ($response->successful()) {
                $data = $response->json();
                if (!empty($data) && isset($data[0]['OilList'])) {
                    $oilList = json_decode($data[0]['OilList'], true);

                    $diesel = collect($oilList)->firstWhere('OilName', 'ไฮดีเซล S');
                    if ($diesel) {
                        $dieselPrice = $diesel['PriceToday'] ?? $diesel['PriceTomorrow'];
                    }
                }
            }
        } catch (\Exception $e) {
        }

        return view('fuel_records.create', compact('trucks', 'camps', 'dieselPrice'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'date_record'     => 'required|date',
            'start_point'     => 'required',
            'destination'     => 'required',
            'trucks_id_truck' => [
                'required',
                Rule::exists('trucks', 'id_truck')->where('status_truck', 'active'),
            ],
            'distance'        => 'nullable|numeric',
            'cost_fuel'       => 'nullable|numeric',
            'cost_fuel_total' => 'nullable|numeric',
        ], [
            'trucks_id_truck.exists' => 'รถคันนี้ไม่พร้อมใช้งาน (อยู่ระหว่างซ่อมบำรุงหรือปลดประจำการแล้ว)',
        ]);

        FuelRecord::create([
            'date_record'     => $data['date_record'],
            'start_point'     => $data['start_point'],
            'destination'     => $data['destination'],
            'trucks_id_truck' => $data['trucks_id_truck'],
            'distance'        => $data['distance'] ?: null,
            'cost_fuel'       => $data['cost_fuel'] ?: null,
            'cost_fuel_total' => $data['cost_fuel_total'] ?: null,
        ]);

        return redirect()->route('fuel_records.index')->with('ok', 'บันทึกข้อมูลเรียบร้อย');
    }

    public function edit(FuelRecord $fuel_record)
    {
        $trucks = Truck::available()
            ->orWhere('id_truck', $fuel_record->trucks_id_truck)
            ->orderBy('id_truck')
            ->get();

        $camps = Camp::active()
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->with('customer')
            ->orderBy('name_camp')
            ->get();

        return view('fuel_records.create', compact('fuel_record', 'trucks', 'camps'));
    }

    public function update(Request $request, FuelRecord $fuel_record)
    {
        $data = $request->validate([
            'date_record'     => 'required|date',
            'start_point'     => 'required',
            'destination'     => 'required',
            'trucks_id_truck' => [
                'required',
                Rule::exists('trucks', 'id_truck')->where(
                    fn($query) => $query->where('status_truck', 'active')
                        ->orWhere('id_truck', $fuel_record->trucks_id_truck)
                ),
            ],
            'distance'        => 'nullable|numeric',
            'cost_fuel'       => 'nullable|numeric',
            'cost_fuel_total' => 'nullable|numeric',
        ], [
            'trucks_id_truck.exists' => 'รถคันนี้ไม่พร้อมใช้งาน (อยู่ระหว่างซ่อมบำรุงหรือปลดประจำการแล้ว)',
        ]);

        $fuel_record->update($data);

        return redirect()->route('fuel_records.index')->with('ok', 'แก้ไขข้อมูลเรียบร้อย');
    }

    public function destroy(FuelRecord $fuel_record)
    {
        $fuel_record->delete();
        return redirect()->route('fuel_records.index')->with('ok', 'ลบข้อมูลเรียบร้อย');
    }
}
