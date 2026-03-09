<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->q;

        $customers = Customer::when($q, function ($query) use ($q) {
            $query->where('name_customer', 'like', "%{$q}%")
                ->orWhere('phone_customer', 'like', "%{$q}%")
                ->orWhere('email_customer', 'like', "%{$q}%");
        })
            ->orderBy('id_customer', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('customers.index', compact('customers', 'q'));
    }

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

        return view('customers.create', compact('provinces'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name_customer'  => 'required|string|max:255',
            'customer_type'  => 'required',
            'phone_customer' => 'nullable|digits:10',
            'zipcode'        => 'nullable|digits:5',
            'email_customer' => 'nullable|email',
        ]);

        Customer::create($request->only([
            'name_customer',
            'customer_type',
            'phone_customer',
            'email_customer',
            'address_detail',
            'subdistrict',
            'district',
            'province',
            'zipcode',
        ]));

        return redirect()
            ->route('customers.index')
            ->with('ok', 'เพิ่มข้อมูลลูกค้าเรียบร้อย');
    }

    public function edit(Customer $customer)
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


        return view('customers.edit', compact('customer', 'provinces'));
    }

    public function update(Request $request, Customer $customer)
    {
        $request->validate([
            'name_customer'  => 'required|string|max:255',
            'phone_customer' => 'nullable|digits:10',
            'zipcode'        => 'nullable|digits:5',
            'email_customer' => 'nullable|email',
        ]);

        $customer->update($request->only([
            'name_customer',
            'customer_type',
            'phone_customer',
            'email_customer',
            'address_detail',
            'subdistrict',
            'district',
            'province',
            'zipcode',
        ]));

        return redirect()
            ->route('customers.index')
            ->with('ok', 'แก้ไขข้อมูลเรียบร้อย');
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();

        return redirect()
            ->route('customers.index')
            ->with('ok', 'ลบข้อมูลเรียบร้อย');
    }
    public function show(Customer $customer)
    {
        return view('customers.show', compact('customer'));
    }
}
