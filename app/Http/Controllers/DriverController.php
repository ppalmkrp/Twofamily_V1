<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use Illuminate\Http\Request;

class DriverController extends Controller
{
    // 📌 หน้า list
    public function index(Request $request)
    {
        $q = $request->q;

        $drivers = Driver::when($q, function ($query) use ($q) {
            $query->where('name_driver', 'like', "%{$q}%")
                ->orWhere('phone_driver', 'like', "%{$q}%")
                ->orWhere('citizenid_driver', 'like', "%{$q}%");
        })
            ->orderBy('id_driver', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('drivers.index', compact('drivers', 'q'));
    }

    // 📌 หน้า create
    public function create()
    {
        $provinces = [
            'กรุงเทพมหานคร',
            'กระบี่',
            'กาญจนบุรี',
            'กาฬสินธุ์',
            'กำแพงเพชร',
            'ขอนแก่น',
            'จันทบุรี',
            'ฉะเชิงเทรา',
            'ชลบุรี',
            'ชัยนาท',
            'ชัยภูมิ',
            'ชุมพร',
            'เชียงราย',
            'เชียงใหม่',
            'ตรัง',
            'ตราด',
            'ตาก',
            'นครนายก',
            'นครปฐม',
            'นครพนม',
            'นครราชสีมา',
            'นครศรีธรรมราช',
            'นครสวรรค์',
            'นนทบุรี',
            'นราธิวาส',
            'น่าน',
            'บึงกาฬ',
            'บุรีรัมย์',
            'ปทุมธานี',
            'ประจวบคีรีขันธ์',
            'ปราจีนบุรี',
            'ปัตตานี',
            'พระนครศรีอยุธยา',
            'พะเยา',
            'พังงา',
            'พัทลุง',
            'พิจิตร',
            'พิษณุโลก',
            'เพชรบุรี',
            'เพชรบูรณ์',
            'แพร่',
            'ภูเก็ต',
            'มหาสารคาม',
            'มุกดาหาร',
            'แม่ฮ่องสอน',
            'ยโสธร',
            'ยะลา',
            'ร้อยเอ็ด',
            'ระนอง',
            'ระยอง',
            'ราชบุรี',
            'ลพบุรี',
            'ลำปาง',
            'ลำพูน',
            'เลย',
            'ศรีสะเกษ',
            'สกลนคร',
            'สงขลา',
            'สตูล',
            'สมุทรปราการ',
            'สมุทรสงคราม',
            'สมุทรสาคร',
            'สระแก้ว',
            'สระบุรี',
            'สิงห์บุรี',
            'สุโขทัย',
            'สุพรรณบุรี',
            'สุราษฎร์ธานี',
            'สุรินทร์',
            'หนองคาย',
            'หนองบัวลำภู',
            'อ่างทอง',
            'อำนาจเจริญ',
            'อุดรธานี',
            'อุตรดิตถ์',
            'อุทัยธานี',
            'อุบลราชธานี',
        ];


        return view('drivers.create', compact('provinces'));
    }

    // 📌 บันทึกข้อมูลใหม่
    public function store(Request $request)
    {
        $request->validate([
            'name_driver' => 'required|string|max:255',
            'province'    => 'required|string|max:100',
        ]);

        Driver::create($request->only([
            'name_driver',
            'address_detail',
            'subdistrict',
            'district',
            'province',
            'zipcode',
            'phone_driver',
            'citizenid_driver',
        ]));

        return redirect()
            ->route('drivers.index')
            ->with('ok', 'เพิ่มข้อมูลพนักงานขับรถเรียบร้อย');
    }

    // 📌 หน้า edit
    public function edit(Driver $driver)
    {
        return view('drivers.edit', compact('driver'));
    }

    // 📌 อัปเดตข้อมูล
    public function update(Request $request, Driver $driver)
    {
        $request->validate([
            'name_driver'   => 'required|string|max:255',
            'province'      => 'required|string|max:100',
            'phone_driver'  => 'nullable|digits:10', // ✅ เบอร์โทร 10 หลัก
            'citizenid_driver' => 'nullable|digits:13',
            'zipcode' => 'nullable|digits:5', // ✅ รหัสไปรษณีย์ 5 หลัก
        ]);


        $driver->update($request->only([
            'name_driver',
            'address_detail',
            'subdistrict',
            'district',
            'province',
            'zipcode',
            'phone_driver',
            'citizenid_driver',
        ]));

        return redirect()
            ->route('drivers.index')
            ->with('ok', 'แก้ไขข้อมูลเรียบร้อย');
    }

    // 📌 ลบ (soft delete)
    public function destroy(Driver $driver)
    {
        $driver->delete();

        return redirect()
            ->route('drivers.index')
            ->with('ok', 'ลบข้อมูลเรียบร้อย');
    }
    public function show(\App\Models\Driver $driver)
    {
        return view('drivers.show', compact('driver'));
    }
}
