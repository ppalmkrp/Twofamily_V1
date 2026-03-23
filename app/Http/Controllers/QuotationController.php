<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Quotation;
use App\Models\QuotationDetail;
use App\Models\Customer;
use App\Models\Product;

use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class QuotationController extends Controller
{
    /* ====================== CRUD ====================== */

    public function index()
    {
        $quotations = Quotation::with('customer')
            ->latest('id_quot')
            ->paginate(10);

        return view('quotations.index', compact('quotations'));
    }

    public function create()
    {
        return view('quotations.create', [
            'customers' => Customer::all(),
            'products'  => Product::all(),

        ]);
    }

    public function store(Request $request)
    {
        $subtotal = 0;
        foreach ($request->items as $item) {
            $subtotal += $item['quantity'] * $item['price'];
        }

        $discount = $request->discount ?? 0;
        $total = max($subtotal - $discount, 0);

        $quotation = Quotation::create([
            'id_customer'  => $request->id_customer,
            'date_quot'    => now(),
            'subtotal'     => $subtotal,
            'discount'     => $discount,
            'total_amount' => $total,
        ]);

        foreach ($request->items as $item) {
            QuotationDetail::create([
                'id_quot'        => $quotation->id_quot,
                'id_product'     => $item['id_product'],
                'quantity'       => $item['quantity'],
                'price_per_unit' => $item['price'],
                'total_price'    => $item['quantity'] * $item['price'],
            ]);
        }

        return redirect()->route('quotations.show', $quotation);
    }

    public function show(Quotation $quotation)
    {
        $quotation->load('customer', 'details.product');
        return view('quotations.show', compact('quotation'));
    }

    public function edit(Quotation $quotation)
    {
        $quotation->load('details.product', 'customer');

        return view('quotations.edit', [
            'quotation' => $quotation,
            'customers' => Customer::all(),
            'products'  => Product::all(),
        ]);
    }

    public function update(Request $request, Quotation $quotation)
    {
        $subtotal = 0;
        foreach ($request->items as $item) {
            $subtotal += $item['quantity'] * $item['price'];
        }

        $discount = $request->discount ?? 0;
        $total = max($subtotal - $discount, 0);

        $quotation->update([
            'id_customer'  => $request->id_customer,
            'subtotal'     => $subtotal,
            'discount'     => $discount,
            'total_amount' => $total,
        ]);

        $quotation->details()->delete();

        foreach ($request->items as $item) {
            QuotationDetail::create([
                'id_quot'        => $quotation->id_quot,
                'id_product'     => $item['id_product'],
                'quantity'       => $item['quantity'],
                'price_per_unit' => $item['price'],
                'total_price'    => $item['quantity'] * $item['price'],
            ]);
        }

        return redirect()
            ->route('quotations.show', $quotation)
            ->with('ok', 'อัปเดตใบเสนอราคาเรียบร้อย');
    }

    public function downloadPDF(Quotation $quotation)
    {
        $quotation->load('customer', 'details.product');

        $pdf = Pdf::loadView('quotations.pdf', compact('quotation'))
            ->setPaper('A4', 'portrait');

        return $pdf->stream('quotation.pdf');
    }

    public function destroy($id)
    {
        $quotation = Quotation::findOrFail($id);

        // ถ้ามี relation เช่น details
        $quotation->details()->delete(); // ถ้ามี

        $quotation->delete();

        return redirect()->route('quotations.index')
            ->with('ok', 'ลบใบเสนอราคาสำเร็จ');
    }

    public function approve($id)
    {
        $q = Quotation::findOrFail($id);
        $q->status = 'approved';
        $q->save();

        return back()->with('success', 'อนุมัติแล้ว');
    }

    public function cancel($id)
{
    $q = Quotation::findOrFail($id);

    // ป้องกันยกเลิกซ้ำ
    if ($q->status != 'draft') {
        return back()->with('ok', 'ไม่สามารถยกเลิกได้');
    }

    $q->status = 'rejected';
    $q->save();

    return redirect()->route('quotations.show', $q->id_quot)
        ->with('ok', 'ยกเลิกใบเสนอราคาแล้ว');
}
}
