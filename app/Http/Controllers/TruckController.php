<?php

namespace App\Http\Controllers;

use App\Models\Truck;
use Illuminate\Http\Request;
use App\Http\Requests\TruckStoreRequest;
use App\Http\Requests\TruckUpdateRequest;

class TruckController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->q;
        $status = $request->status;

        $query = Truck::query();

        if ($q) {
            $query->where(function ($x) use ($q) {
                $x->where('id_truck', 'like', "%$q%")
                    ->orWhere('brand_truck', 'like', "%$q%")
                    ->orWhere('model_truck', 'like', "%$q%");
            });
        }

        if ($status) {
            $query->where('status_truck', $status);
        }

        $trucks = $query->orderByDesc('created_at')->paginate(10);

        return view('trucks.index', compact('trucks', 'q', 'status'));
    }

    public function create()
    {
        $truck = new Truck();
        return view('trucks.create', compact('truck'));
    }

    public function store(TruckStoreRequest $request)
    {
        Truck::create($request->validated());
        return redirect()->route('trucks.index')->with('ok', 'เพิ่มรถเรียบร้อย');
    }

    public function edit(Truck $truck)
    {
        return view('trucks.edit', compact('truck'));
    }

    public function update(TruckUpdateRequest $request, Truck $truck)
    {
        $oldKey = $truck->id_truck;
        $truck->fill($request->validated());

        if ($oldKey !== $truck->id_truck) {
            $truck->save();
        } else {
            $truck->save();
        }

        return redirect()->route('trucks.index')->with('ok', 'อัปเดตรถเรียบร้อย');
    }

    public function destroy(Truck $truck)
    {
        $truck->delete();
        return redirect()->route('trucks.index')->with('ok', 'ลบข้อมูลรถแล้ว');
    }
}
