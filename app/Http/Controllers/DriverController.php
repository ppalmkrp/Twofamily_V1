<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule; //  อย่าลืมบรรทัดนี้ ต้องใช้สำหรับทำเงื่อนไขเช็คชื่อซ้ำ

class DriverController extends Controller
{
    //  หน้า list
    public function index(Request $request)
    {
        $q = $request->q;

        $drivers = Driver::when($q, function ($query) use ($q) {
            //  แก้เป็นค้นหาจากชื่อ หรือ นามสกุล
            $query->where('fname_driver', 'like', "%{$q}%")
                ->orWhere('lname_driver', 'like', "%{$q}%")
                ->orWhere('phone_driver', 'like', "%{$q}%")
                ->orWhere('citizenid_driver', 'like', "%{$q}%");
        })
            ->orderBy('id_driver', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('drivers.index', compact('drivers', 'q'));
    }

    //  หน้า create
    public function create()
    {
        // (ย่อ array จังหวัดไว้เพื่อความสะอาดตาของโค้ด โค้ดเดิมของคุณใช้งานได้ปกติครับ)
        $provinces = ['กรุงเทพมหานคร', 'กระบี่', 'กาญจนบุรี', /*... ใส่ให้ครบเหมือนเดิมได้เลยครับ ...*/];

        return view('drivers.create', compact('provinces'));
    }

    //  บันทึกข้อมูลใหม่
    public function store(Request $request)
    {
        $request->validate([
            //  เช็คว่า ชื่อ+นามสกุล คู่นี้มีในตาราง drivers หรือยัง (ละเว้นคนที่ถูกลบ soft delete ไปแล้ว)
            'fname_driver' => [
                'required',
                'string',
                'max:255',
                Rule::unique('drivers', 'fname_driver')
                    ->where('lname_driver', $request->lname_driver)
                    ->whereNull('deleted_at')
            ],
            'lname_driver'  => 'required|string|max:255',
            'province'      => 'nullable|string|max:100', // ปรับเป็น nullable เผื่อ API โหลดช้า
            'phone_driver'  => 'nullable|digits:10',
            'citizenid_driver' => 'nullable|digits:13|unique:drivers,citizenid_driver', //  กันเลขบัตร ปชช. ซ้ำ
            'zipcode'       => 'nullable|digits:5',
        ], [
            //  ข้อความแจ้งเตือนเมื่อชื่อ-สกุลซ้ำ
            'fname_driver.unique' => 'ชื่อและนามสกุลนี้ มีอยู่ในระบบแล้ว กรุณาตรวจสอบอีกครั้ง!',
            'citizenid_driver.unique' => 'เลขบัตรประชาชนนี้ ถูกใช้งานไปแล้ว!'
        ]);

        Driver::create($request->only([
            'fname_driver',
            'lname_driver',
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

    //  หน้า edit
    public function edit(Driver $driver)
    {
        return view('drivers.edit', compact('driver'));
    }

    //  อัปเดตข้อมูล
    public function update(Request $request, Driver $driver)
    {
        $request->validate([
            //  เช็คชื่อซ้ำเหมือนหน้า Store แต่อนุญาตให้เป็นชื่อเดิมของตัวเองได้ (ignore)
            'fname_driver' => [
                'required',
                'string',
                'max:255',
                Rule::unique('drivers', 'fname_driver')
                    ->where('lname_driver', $request->lname_driver)
                    ->whereNull('deleted_at')
                    ->ignore($driver->id_driver, 'id_driver') // ข้ามการเช็คตัวเอง
            ],
            'lname_driver'  => 'required|string|max:255',
            'province'      => 'nullable|string|max:100',
            'phone_driver'  => 'nullable|digits:10',
            'citizenid_driver' => 'nullable|digits:13|unique:drivers,citizenid_driver,' . $driver->id_driver . ',id_driver',
            'zipcode'       => 'nullable|digits:5',
        ], [
            'fname_driver.unique' => 'ชื่อและนามสกุลนี้ มีอยู่ในระบบแล้ว กรุณาตรวจสอบอีกครั้ง!',
            'citizenid_driver.unique' => 'เลขบัตรประชาชนนี้ ถูกใช้งานไปแล้ว!'
        ]);

        $driver->update($request->only([
            'fname_driver',
            'lname_driver',
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

    //  ลบ (soft delete)
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
