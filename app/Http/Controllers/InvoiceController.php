<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Quotation;
use App\Models\Invoice;
use App\Models\InvoiceDetail;
use App\Models\Setting;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceController extends Controller
{
    public function createFromQuotation($id)
    {
        $q = Quotation::with('details')->findOrFail($id);

        $invoice = Invoice::create([
            'id_customer' => $q->id_customer,
            'id_quotation' => $q->id_quot,
            'discount' => $q->discount ?? 0,
            'total' => $q->total_amount,
            'status' => 'unpaid'
        ]);

        foreach ($q->details as $d) {
            InvoiceDetail::create([
                'id_invoice' => $invoice->id_invoice,
                'id_product' => $d->id_product,
                'quantity' => $d->quantity,
                'price' => $d->price_per_unit,
                'total' => $d->total_price
            ]);
        }

        return redirect()->route('invoices.show', $invoice->id_invoice);
    }

    public function show($id)
    {
        $invoice = Invoice::with('details.product', 'customer', 'quotation')
            ->findOrFail($id);

        return view('invoices.show', compact('invoice'));
    }

    public function pay($id)
    {
        $invoice = Invoice::findOrFail($id);
        $invoice->status = 'paid';
        $invoice->save();

        return back()->with('success', 'ชำระเงินแล้ว');
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
        $invoice = Invoice::with('details.product', 'customer', 'quotation')
            ->findOrFail($id);

        $settings = Setting::pluck('value', 'key');

        $pdf = Pdf::loadView('invoices.pdf', compact('invoice', 'settings'));

        return $pdf->stream('INV-' . $invoice->id_invoice . '.pdf');
    }

    public function destroy($id)
    {
        $invoice = Invoice::findOrFail($id);

        // ลบรายละเอียดใบแจ้งหนี้ก่อน
        InvoiceDetail::where('id_invoice', $invoice->id_invoice)->delete();

        // ลบใบแจ้งหนี้
        $invoice->delete();

        return redirect()
            ->route('invoices.index')
            ->with('success', 'ลบใบแจ้งหนี้เรียบร้อยแล้ว');
    }
}