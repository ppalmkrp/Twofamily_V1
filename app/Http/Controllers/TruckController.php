<?php

namespace App\Http\Controllers;

use App\Models\Truck;
use App\Models\TruckBrand;
use Illuminate\Http\Request;
use App\Http\Requests\TruckStoreRequest;
use App\Http\Requests\TruckUpdateRequest;

class TruckController extends Controller
{
   public function index(Request $request)
    {
        $q = $request->q;
        $status = $request->status;

        //  ใช้ with() เพื่อดึงข้อมูลยี่ห้อและรุ่นมาพร้อมกันเลย (ลดการคิวรีซ้ำซ้อน)
        $query = Truck::with(['brand', 'model']);

        if ($q) {
            $query->where(function ($x) use ($q) {
                // ค้นหาจากทะเบียน
                $x->where('id_truck', 'like', "%$q%")
                    //  ค้นหาจากชื่อยี่ห้อ (ทะลุไปตาราง truck_brands)
                    ->orWhereHas('brand', function ($b) use ($q) {
                        $b->where('name_brand', 'like', "%$q%");
                    })
                    //  ค้นหาจากชื่อรุ่น (ทะลุไปตาราง truck_models)
                    ->orWhereHas('model', function ($m) use ($q) {
                        $m->where('name_model', 'like', "%$q%");
                    });
            });
        }

        if ($status) {
            $query->where('status_truck', $status);
        }

        $trucks = $query->orderByDesc('created_at')->paginate(10);

        return view('trucks.index', compact('trucks', 'q', 'status'));
    }

    // public function create()
    // {
    //     $truck = new Truck();
    //     return view('trucks.create', compact('truck'));
    // }
    // ตัวอย่างใน TruckController
    public function create()
    {
        $truck = new Truck();
        $brands = TruckBrand::with('models')->orderBy('name_brand')->get();
        return view('trucks.create', compact('truck', 'brands'));
    }

    public function edit($id)
    {
        $truck = Truck::findOrFail($id);
        $brands = TruckBrand::with('models')->orderBy('name_brand')->get();
        return view('trucks.edit', compact('truck', 'brands'));
    }
    public function store(TruckStoreRequest $request)
    {
        Truck::create($request->validated());
        return redirect()->route('trucks.index')->with('ok', 'เพิ่มรถเรียบร้อย');
    }

    // public function edit(Truck $truck)
    // {
    //     return view('trucks.edit', compact('truck'));
    // }

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
