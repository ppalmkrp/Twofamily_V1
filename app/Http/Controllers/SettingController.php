<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    // หน้าแรก settings
    public function index()
    {
        return view('settings.index');
    }

    // หน้าเมนูเอกสาร
    public function documents()
    {
        return view('settings.documents');
    }

    // หน้าใบเสนอราคา
    public function quotation()
    {
        $settings = Setting::pluck('value','key');
        return view('settings.documents.quotation', compact('settings'));
    }

    // บันทึกค่า
    public function quotationUpdate(Request $request)
    {
        foreach ($request->except('_token') as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        return back()->with('success', 'บันทึกสำเร็จ');
    }
}