<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceDetail;
use App\Models\Customer;
use App\Models\Product;
use Illuminate\Http\Request;
use PDF;
use Carbon\Carbon;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Shared\Converter;

class InvoiceController extends Controller
{
    public function index()
    {
        $invoices = Invoice::with('customer')->latest()->paginate(10);
        return view('invoices.index', compact('invoices'));
    }

    public function create()
    {
        $customers = Customer::all();
        $products  = Product::all();

        return view('invoices.create', compact('customers', 'products'));
    }

    public function store(Request $request)
    {
        $invoice = Invoice::create([
            'date_iv' => now(),
            'status' => 'ค้างชำระ',
            'Customers_id_customer' => $request->customer_id,
        ]);

        foreach ($request->products as $item) {
            InvoiceDetail::create([
                'Invoice_id_iv' => $invoice->id_iv,
                'Products_id_product' => $item['product_id'],
                'quantity' => $item['quantity'],
                'total_price' => $item['total_price'],
                'total_amount' => $item['total_price'] * $item['quantity'],
            ]);
        }

        return redirect()->route('invoices.index')->with('ok', 'สร้างใบแจ้งหนี้สำเร็จ');
    }

    public function show(Invoice $invoice)
    {
        $invoice->load('customer', 'details.product');
        return view('invoices.show', compact('invoice'));
    }

    public function edit(Invoice $invoice)
    {
        $customers = Customer::all();
        $products  = Product::all();

        $invoice->load('details.product', 'customer');

        return view('invoices.edit', compact('invoice', 'customers', 'products'));
    }

    public function update(Request $request, Invoice $invoice)
    {
        $invoice->update([
            'Customers_id_customer' => $request->customer_id,
        ]);

        $invoice->details()->delete();

        foreach ($request->products as $item) {
            InvoiceDetail::create([
                'Invoice_id_iv' => $invoice->id_iv,
                'Products_id_product' => $item['product_id'],
                'quantity' => $item['quantity'],
                'total_price' => $item['total_price'],
                'total_amount' => $item['total_price'] * $item['quantity'],
            ]);
        }

        return redirect()->route('invoices.index')->with('ok', 'อัปเดตสำเร็จ');
    }

    public function destroy(Invoice $invoice)
    {
        $invoice->delete();
        return redirect()->route('invoices.index')->with('ok', 'ลบสำเร็จ');
    }

    public function downloadWord(Invoice $invoice)
    {

        $invoice->load('customer', 'details.product');
        $customer = $invoice->customer;

        $fullAddress = collect([
            $customer->address_detail ?? null,
            $customer->subdistrict ?? null,
            $customer->district ?? null,
            $customer->province ?? null,
            $customer->zipcode ?? null,
        ])->filter()->implode(' ');

        // ================== ตั้งค่าเอกสาร ==================
        $phpWord = new PhpWord();
        $phpWord->setDefaultFontName('TH Sarabun New');
        $phpWord->setDefaultFontSize(16);

        $section = $phpWord->addSection([
            'marginTop'    => Converter::cmToTwip(1.5),
            'marginBottom' => Converter::cmToTwip(1.5),
            'marginLeft'   => Converter::cmToTwip(2.5),
            'marginRight'  => Converter::cmToTwip(2.5),
        ]);
        $compact = [
            'spaceAfter'  => 0,
            'spaceBefore' => 0,
            'lineHeight'  => 1.0,
        ];

        // ================== ส่วนหัว ==================
        $headerTable = $section->addTable([
            'borderSize' => 0,
            'borderColor' => 'FFFFFF',
        ]);
        $headerTable->addRow();

        // ซ้าย - ใบเสนอราคา
        $leftCell = $headerTable->addCell(3000);
        $leftCell->addText("ใบแจ้งหนี้", ['bold' => true, 'size' => 20]);

        // กลาง - โลโก้และชื่อบริษัท
        $centerCell = $headerTable->addCell(4000, ['valign' => 'center']);
        $logoPath = public_path('images/tfe-logo.png');
        if (file_exists($logoPath)) {
            $centerCell->addImage($logoPath, ['width' => 100, 'align' => 'center']);
        }
        $centerCell->addText("TWO FAMILY", ['bold' => true, 'size' => 14], ['align' => 'center']);
        $centerCell->addText("ENGINEERING CO., LTD.", ['size' => 10], ['align' => 'center']);

        // ขวา - เลขที่และวันที่

        $invoiceNo = 'IV' . str_pad($invoice->id_iv, 5, '0', STR_PAD_LEFT);

        // วันที่อัปเดต
        $date = Carbon::parse($invoice->updated_at)
            ->locale('th')
            ->translatedFormat('d F Y');
        $rightCell = $headerTable->addCell(3000);
        $rightCell->addText(
            "No.        {$invoiceNo}",
            ['size' => 16],
            ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::END]
        );

        $rightCell->addText(
            "Date:  {$date}",
            ['size' => 16],
            ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::END]
        );
        // ข้อมูลติดต่อทางซ้าย
        $contactTable = $section->addTable([
            'borderSize' => 0,
            'borderColor' => 'FFFFFF',
        ]);
        $contactTable->addRow();
        $contactLeft = $contactTable->addCell(5000);
        $contactLeft->addText("Two Family Engineering", [], $compact);
        $contactLeft->addText("123 อ.เมือง จ.โคราช 00000", [], $compact);
        $contactLeft->addText("Phone: 092-648-2929", [], $compact);
        $contactLeft->addText("Email: TwoFam.contact@gmail.com", [], $compact);

        // ผู้จัดการทางขวา
        $contactRight = $contactTable->addCell(5000);
        $contactRight->addText("สุพิชญา ขันตรีกรม", ['size' => 16], ['align' => 'right']);
        $contactRight->addText("ผู้จัดการ", ['size' => 14], ['align' => 'right']);

        $section->addTextBreak(1);

        // ================== ข้อมูลลูกค้า ==================
        $infoTable = $section->addTable([
            'borderSize' => 0,
            'borderColor' => 'FFFFFF',
        ]);
        $infoTable->addRow();
        $infoTable->addCell(5000)
            ->addText(
                "ชื่อลูกค้า: " . ($customer->name_customer ?? '-'),
                [],
                $compact
            );

        $infoTable->addCell(5000)
            ->addText(
                "ที่อยู่ลูกค้า: " . ($fullAddress ?: '-'),
                [],
                $compact
            );
        $section->addTextBreak(1);

        // ================== ตารางรายการ ==================
        $itemTable = $section->addTable([
            'borderSize'  => 0,
            'cellMargin'  => 80
        ]);

        // หัวตาราง
        $itemTable->addRow(400);
        $itemTable->addCell(1000, ['bgColor' => 'FFFFFF'])->addText('ลำดับที่', ['bold' => true], ['align' => 'center']);
        $itemTable->addCell(3500, ['bgColor' => 'FFFFFF'])->addText('รายละเอียดงาน', ['bold' => true], ['align' => 'center']);
        $itemTable->addCell(1500, ['bgColor' => 'FFFFFF'])->addText('จำนวน', ['bold' => true], ['align' => 'center']);
        $itemTable->addCell(1500, ['bgColor' => 'FFFFFF'])->addText('ราคา/คิว', ['bold' => true], ['align' => 'center']);
        $itemTable->addCell(2500, ['bgColor' => 'FFFFFF'])->addText('รวมเป็นเงิน', ['bold' => true], ['align' => 'center']);

        // รายการสินค้า

        // $items = $invoice->details->map(function ($detail, $index) {
        //     return [
        //         'no'    => $index + 1,
        //         'name'  => $detail->product->name ?? '-',
        //         'qty'   => $detail->qty,
        //         'price' => $detail->price,
        //         'total' => $detail->qty * $detail->price,
        //     ];
        // })->toArray();
        $sum = 0;
        $no  = 1;

        foreach ($invoice->details as $d) {

            $itemTable->addRow(300);
            $itemTable->addCell(1000)->addText(
                $no++,
                [],
                ['alignment' => 'center']
            );
            $itemTable->addCell(3500)->addText(
                $d->product->name_product ?? '-'
            );

            $itemTable->addCell(1500)->addText(
                $d->quantity,
                [],
                ['alignment' => 'center']
            );

            $itemTable->addCell(1500)->addText(
                number_format($d->total_price, 2),
                [],
                ['alignment' => 'right']
            );

            $itemTable->addCell(2500)->addText(
                number_format($d->total_amount, 2),
                [],
                ['alignment' => 'right']
            );

            // รวมยอด
            $sum += $d->total_amount;
        }


        /* ================== สรุปราคา ================== */

        $subtotal = $sum; // รวมก่อนส่วนลด
        $discount = $invoice->discount ?? 0;
        $afterDiscount = max($subtotal - $discount, 0);
        $vat = $afterDiscount * 0.07;
        $grandTotal = $afterDiscount + $vat;


        // ---------------- รวมก่อนส่วนลด ----------------
        $itemTable->addRow(400);

        $itemTable->addCell(7500, ['gridSpan' => 4])
            ->addText('รวมก่อนส่วนลด', ['size' => 16]);

        $itemTable->addCell(2500)->addText(
            number_format($subtotal, 2),
            ['size' => 16],
            ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::END]
        );


        // ---------------- ส่วนลด ----------------
        // $itemTable->addRow(400);

        // $itemTable->addCell(7500, ['gridSpan' => 4])
        //     ->addText('ส่วนลด', ['size' => 16]);

        // $itemTable->addCell(2500)->addText(
        //     number_format($discount, 2),
        //     ['size' => 16],
        //     ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::END]
        // );


        // ---------------- VAT 7% ----------------
        $itemTable->addRow(400);

        $itemTable->addCell(7500, ['gridSpan' => 4])
            ->addText('VAT 7%', ['size' => 16]);

        $itemTable->addCell(2500)->addText(
            number_format($vat, 2),
            ['size' => 16],
            ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::END]
        );


        // ---------------- ยอดสุทธิ ----------------
        $itemTable->addRow(400);

        $itemTable->addCell(7500, [
            'gridSpan' => 4,
            'bgColor' => 'D9E2F3'
        ])->addText('ยอดสุทธิ', ['bold' => true, 'size' => 16]);

        $itemTable->addCell(2500)->addText(
            number_format($grandTotal, 2),
            ['bold' => true, 'size' => 16],
            ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::END]
        );


        // $rightAlignTable = $section->addTable(['borderSize' => 0]);
        // $rightAlignTable->addRow();
        // $rightAlignTable->addCell(5000)->addText('');
        // $rightAlignTable->addCell(5000)->addText("****หมายเหตุ*****", ['bold' => true, 'size' => 16], ['align' => 'right']);

        // $sum = array_sum(array_column($items, 'total'));
        $pay60 = round($sum * 0.60);
        $pay40 = $sum - $pay60;

        // ================== หมายเหตุ ==================
        $section->addTextBreak(1);

        $section->addText("*หมายเหตุ", ['bold' => true], $compact);

        $notes = [
            "ในวันทำสัญญาบุคคลทำสัญญาชำระเงิน 60% เป็นจำนวนเงิน "
                . number_format($pay60, 0) . ".-",
            "หลังจากเสร็จสิ้นงาน ลูกค้าชำระเงินส่วนที่เหลือ 40% เป็นจำนวนเงิน "
                . number_format($pay40, 0) . ".-",
            "ทางร้านจะจัดส่งไฟล์งาน หลังจากที่ได้รับการชำระเงินครบถ้วน",
        ];

        foreach ($notes as $note) {
            $section->addText("- " . $note, [], $compact);
        }

        // ================== ข้อมูลการชำระเงิน ==================
        $section->addTextBreak(0.5);
        $section->addText("ข้อมูลการชำระเงิน", ['bold' => true], $compact);
        $section->addText("- ชื่อบัญชี บริษัททูแฟมิลี่ จำกัด", [], $compact);
        $section->addText("- ธนาคาร กสิกรไทย เลขที่บัญชี 233-8-79905-6", [], $compact);

        // ================== ลายเซ็น ==================
        $section->addTextBreak(1);

        $signTable = $section->addTable([
            'borderSize' => 0,
            'borderColor' => 'FFFFFF',
        ]);
        $signTable->addRow();

        // 🔹 ฝั่งซ้าย
        $leftSign = $signTable->addCell(5000);
        $leftSign->addText("______________________");
        $leftSign->addText("ผู้ว่าจ้าง");

        // 🔹 ฝั่งขวา
        $rightSign = $signTable->addCell(5000);
        $rightSign->addText(
            "______________________",
            [],
            ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::END]
        );
        $rightSign->addText(
            "ออกเอกสารโดย",
            [],
            ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::END]
        );

        // ================== บันทึกไฟล์ ==================
        $fileName = "invoice_{$invoiceNo}.docx";
        $path = storage_path($fileName);

        IOFactory::createWriter($phpWord, 'Word2007')->save($path);

        return response()->download($path)->deleteFileAfterSend(true);
    }
}
