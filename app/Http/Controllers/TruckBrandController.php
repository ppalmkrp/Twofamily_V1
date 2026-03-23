<?php

namespace App\Http\Controllers;

use App\Models\TruckBrand;
use Illuminate\Http\Request;

class TruckBrandController extends Controller
{
    public function index()
    {
        // ดึงข้อมูลยี่ห้อทั้งหมด เรียงจากใหม่ไปเก่า
        $brands = TruckBrand::latest()->paginate(10);
        return view('truck_brands.index', compact('brands'));
    }

    public function create()
    {
        return view('truck_brands.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name_brand' => 'required|unique:truck_brands,name_brand|max:255',
        ], [
            'name_brand.required' => 'กรุณากรอกชื่อยี่ห้อ',
            'name_brand.unique' => 'ชื่อยี่ห้อนี้มีในระบบแล้ว',
        ]);

        TruckBrand::create($request->all());

        return redirect()->route('truck_brands.index')->with('ok', 'เพิ่มยี่ห้อรถบรรทุกสำเร็จ!');
    }

    public function edit($id)
    {
        $truck_brand = TruckBrand::findOrFail($id);
        // ใช้หน้า create.blade.php ร่วมกันสำหรับฟอร์มแก้ไข
        return view('truck_brands.create', compact('truck_brand'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name_brand' => 'required|max:255|unique:truck_brands,name_brand,' . $id,
        ]);

        $brand = TruckBrand::findOrFail($id);
        $brand->update($request->all());

        return redirect()->route('truck_brands.index')->with('ok', 'อัปเดตข้อมูลสำเร็จ!');
    }

    public function destroy($id)
    {
        TruckBrand::destroy($id);
        return redirect()->route('truck_brands.index')->with('ok', 'ลบข้อมูลสำเร็จ!');
    }
}
