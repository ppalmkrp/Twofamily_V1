<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

class DriverController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->q;

        $drivers = Driver::when($q, function ($query) use ($q) {
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

    public function create()
    {
        $provinces = ['กรุงเทพมหานคร', 'กระบี่', 'กาญจนบุรี'];

        return view('drivers.create', compact('provinces'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'fname_driver' => [
                'required',
                'string',
                'max:255',
                Rule::unique('drivers', 'fname_driver')
                    ->where('lname_driver', $request->lname_driver)
                    ->whereNull('deleted_at')
            ],
            'lname_driver' => 'required|string|max:255',
            'address_detail' => 'nullable|string',
            'province' => 'nullable|string|max:100',
            'district' => 'nullable|string|max:100',
            'subdistrict' => 'nullable|string|max:100',
            'phone_driver' => 'nullable|digits:10',
            'citizenid_driver' => 'nullable|digits:13|unique:drivers,citizenid_driver',
            'zipcode' => 'nullable|digits:5',

            'citizen_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',

        ], [
            'fname_driver.unique' => 'ชื่อและนามสกุลนี้ มีอยู่ในระบบแล้ว กรุณาตรวจสอบอีกครั้ง!',
            'citizenid_driver.unique' => 'เลขบัตรประชาชนนี้ ถูกใช้งานไปแล้ว!'
        ]);

        $citizenPath = null;

        if ($request->hasFile('citizen_image')) {
            $citizenPath = $request->file('citizen_image')
                ->store('citizens', 'public');
        }

        Driver::create([
            'fname_driver' => $request->fname_driver,
            'lname_driver' => $request->lname_driver,
            'address_detail' => $request->address_detail,
            'province' => $request->province,
            'district' => $request->district,
            'subdistrict' => $request->subdistrict,
            'zipcode' => $request->zipcode,
            'phone_driver' => $request->phone_driver,
            'citizenid_driver' => $request->citizenid_driver,
            'citizen_image' => $citizenPath,
        ]);

        return redirect()
            ->route('drivers.index')
            ->with('ok', 'เพิ่มข้อมูลพนักงานขับรถเรียบร้อย');
    }


    public function edit(Driver $driver)
    {
        return view('drivers.edit', compact('driver'));
    }

    public function update(Request $request, Driver $driver)
    {
        $request->validate([
            'fname_driver' => [
                'required',
                'string',
                'max:255',
                Rule::unique('drivers', 'fname_driver')
                    ->where('lname_driver', $request->lname_driver)
                    ->whereNull('deleted_at')
                    ->ignore($driver->id_driver, 'id_driver')
            ],
            'lname_driver'  => 'required|string|max:255',
            'province'      => 'nullable|string|max:100',
            'phone_driver'  => 'nullable|digits:10',
            'citizenid_driver' => 'nullable|digits:13|unique:drivers,citizenid_driver,' . $driver->id_driver . ',id_driver',
            'zipcode'       => 'nullable|digits:5',

            'citizen_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ], [
            'fname_driver.unique' => 'ชื่อและนามสกุลนี้ มีอยู่ในระบบแล้ว กรุณาตรวจสอบอีกครั้ง!',
            'citizenid_driver.unique' => 'เลขบัตรประชาชนนี้ ถูกใช้งานไปแล้ว!'
        ]);

        if ($request->hasFile('citizen_image')) {

            if ($driver->citizen_image && Storage::disk('public')->exists($driver->citizen_image)) {
                Storage::disk('public')->delete($driver->citizen_image);
            }

            $citizenPath = $request->file('citizen_image')->store('citizens', 'public');
        }

        $driver->update([
            'fname_driver' => $request->fname_driver,
            'lname_driver' => $request->lname_driver,
            'address_detail' => $request->address_detail,
            'subdistrict' => $request->subdistrict,
            'district' => $request->district,
            'province' => $request->province,
            'zipcode' => $request->zipcode,
            'phone_driver' => $request->phone_driver,
            'citizenid_driver' => $request->citizenid_driver,

            'citizen_image' => $citizenPath ?? $driver->citizen_image,
        ]);

        return redirect()
            ->route('drivers.index')
            ->with('ok', 'แก้ไขข้อมูลเรียบร้อย');
    }

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
