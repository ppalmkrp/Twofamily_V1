<?php

namespace App\Http\Controllers;

use App\Models\TruckModel;
use App\Models\TruckBrand;
use Illuminate\Http\Request;

class TruckModelController extends Controller
{
    public function index()
    {
        // ดึงข้อมูลรุ่น พร้อมกับชื่อยี่ห้อ (Relationship)
        $models = TruckModel::with('brand')->latest()->paginate(10);
        return view('truck_models.index', compact('models'));
    }

    public function create()
    {
        // ต้องส่งข้อมูลยี่ห้อไปให้หน้าเว็บทำ Dropdown ด้วย
        $brands = TruckBrand::orderBy('name_brand')->get();
        return view('truck_models.create', compact('brands'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'truck_brand_id' => 'required|exists:truck_brands,id',
            'name_model' => 'required|max:255',
        ], [
            'truck_brand_id.required' => 'กรุณาเลือกยี่ห้อรถ',
            'name_model.required' => 'กรุณากรอกชื่อรุ่น',
        ]);

        TruckModel::create($request->all());

        return redirect()->route('truck_models.index')->with('ok', 'เพิ่มรุ่นรถบรรทุกสำเร็จ!');
    }

    public function edit($id)
    {
        $truck_model = TruckModel::findOrFail($id);
        $brands = TruckBrand::orderBy('name_brand')->get();
        return view('truck_models.create', compact('truck_model', 'brands'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'truck_brand_id' => 'required|exists:truck_brands,id',
            'name_model' => 'required|max:255',
        ]);

        $model = TruckModel::findOrFail($id);
        $model->update($request->all());

        return redirect()->route('truck_models.index')->with('ok', 'อัปเดตข้อมูลสำเร็จ!');
    }

    public function destroy($id)
    {
        TruckModel::destroy($id);
        return redirect()->route('truck_models.index')->with('ok', 'ลบข้อมูลสำเร็จ!');
    }
}
