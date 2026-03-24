<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        return view('settings.index');
    }

    public function documents()
    {
        return view('settings.documents');
    }

    public function quotation()
    {
        $settings = Setting::pluck('value', 'key');
        return view('settings.documents.quotation', compact('settings'));
    }

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
    public function invoice()
    {
        $settings = Setting::pluck('value', 'key');
        return view('settings.documents.invoice', compact('settings'));
    }

    public function invoiceUpdate(Request $request)
    {
        foreach ($request->except('_token') as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        return back()->with('success', 'บันทึกเรียบร้อย');
    }
    public function update(Request $request)
    {
        foreach ($request->except('_token') as $key => $value) {
            \App\Models\Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        return back()->with('success', 'บันทึกสำเร็จ');
    }
}
