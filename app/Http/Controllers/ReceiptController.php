<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\Receipt;
use App\Models\Setting;
use Barryvdh\DomPDF\Facade\Pdf;

class ReceiptController extends Controller
{
    public function createFromInvoice($id)
    {
        $invoice = Invoice::with('quotation')->findOrFail($id);

        $subTotal = $invoice->quotation->subtotal ?? 0;
        $discount = $invoice->quotation->discount ?? 0;

        $afterDiscount = max($subTotal - $discount, 0);
        $vat = $afterDiscount * 0.07;

        $grandTotal = $afterDiscount + $vat;

        $receipt = Receipt::create([
            'id_invoice' => $invoice->id_invoice,
            'id_customer' => $invoice->id_customer,
            'total' => $grandTotal,
            'date_receipt' => now(),
        ]);

        return redirect()->route('receipts.show', $receipt->id_receipt);
    }

    public function show($id)
    {
        $receipt = Receipt::with('invoice.customer', 'invoice.details.product', 'invoice.quotation')
            ->findOrFail($id);

        return view('receipts.show', compact('receipt'));
    }

    public function index()
{
    $receipts = Receipt::with('invoice.customer')
        ->orderByDesc('id_receipt')
        ->paginate(10);

    return view('receipts.index', compact('receipts'));
}

public function pdf($id)
{
    $receipt = Receipt::with('invoice.customer', 'invoice.details.product', 'invoice.quotation')
        ->findOrFail($id);

    $settings = Setting::pluck('value', 'key');

    $pdf = Pdf::loadView('receipts.pdf', compact('receipt', 'settings'));

    return $pdf->stream('RC-' . $receipt->id_receipt . '.pdf');
}

public function destroy($id)
{
    $receipt = Receipt::findOrFail($id);
    $receipt->delete();

    return redirect()->route('receipts.index')
        ->with('success', 'ลบใบเสร็จเรียบร้อย');
}
}
