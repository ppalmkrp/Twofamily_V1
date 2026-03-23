<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Quotation;
use App\Models\Invoice;
use App\Models\InvoiceDetail;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceController extends Controller
{
    // 🔥 สร้าง invoice จาก quotation
    public function createFromQuotation($id)
    {
        // 1. ดึงใบเสนอราคา + รายการสินค้า
        $q = Quotation::with('details')->findOrFail($id);

        // 2. สร้าง invoice
        $invoice = Invoice::create([
            'id_customer' => $q->id_customer,
            'id_quotation' => $q->id_quot,
            'total' => 0,
            'status' => 'unpaid'
        ]);

        $total = 0;

        // 3. วนลูป copy รายการสินค้า
        foreach ($q->details as $d) {

            $rowTotal = $d->quantity * $d->price_per_unit;

            InvoiceDetail::create([
                'id_invoice' => $invoice->id_invoice,
                'id_product' => $d->id_product,
                'quantity' => $d->quantity,
                'price' => $d->price_per_unit,
                'total' => $rowTotal
            ]);

            $total += $rowTotal;
        }

        // 4. อัปเดตราคารวม
        $invoice->total = $total;
        $invoice->save();

        // 5. ไปหน้า invoice
        return redirect()->route('invoices.show', $invoice->id_invoice);
    }

    // 🔥 แสดง invoice
    public function show($id)
    {
        $invoice = Invoice::with('details.product','customer')->findOrFail($id);

        return view('invoices.show', compact('invoice'));
    }

    // 🔥 กดชำระเงิน
    public function pay($id)
    {
        $invoice = Invoice::findOrFail($id);
        $invoice->status = 'paid';
        $invoice->save();

        return back()->with('success','ชำระเงินแล้ว');
    }
    public function index()
{
    $invoices = Invoice::with('customer')
        ->orderByDesc('id_invoice')
        ->paginate(10);

    return view('invoices.index', compact('invoices'));
}



public function pdf($id)
{
    $invoice = Invoice::with('details.product','customer')->findOrFail($id);

    $pdf = Pdf::loadView('invoices.pdf', compact('invoice'));

    return $pdf->stream('INV-'.$invoice->id_invoice.'.pdf');
}
}