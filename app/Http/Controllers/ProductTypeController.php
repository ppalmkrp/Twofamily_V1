<?php

namespace App\Http\Controllers;

use App\Models\ProductType;
use Illuminate\Http\Request;

class ProductTypeController extends Controller
{
    public function index()
    {
        $types = \App\Models\ProductType::orderBy('id_product_type', 'desc')->paginate(10);
        return view('product_types.index', compact('types'));
    }

    public function create()
    {
        $types = \App\Models\ProductType::all();
        return view('product_types.create', compact('types'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'name_product_type' => 'required|string|max:100'
        ]);

        ProductType::create($request->only('name_product_type'));
        return redirect()->route('product_types.index')->with('ok', 'เพิ่มประเภทสินค้าเรียบร้อย');
    }

    public function edit(ProductType $product_type)
    {
        return view('product_types.edit', compact('product_type'));
    }

    public function update(Request $request, ProductType $product_type)
    {
        $request->validate([
            'name_product_type' => 'required|string|max:100'
        ]);

        $product_type->update($request->only('name_product_type'));
        return redirect()->route('product_types.index')->with('ok', 'แก้ไขข้อมูลเรียบร้อย');
    }

    public function destroy(ProductType $product_type)
    {
        $product_type->delete();
        return redirect()->route('product_types.index')->with('ok', 'ลบข้อมูลเรียบร้อย');
    }
}
