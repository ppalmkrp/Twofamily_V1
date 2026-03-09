<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Quotation;
use App\Models\QuotationDetail;
use App\Models\Customer;
use App\Models\Product;

/* ⭐ เพิ่ม use เหล่านี้ */
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Shared\Converter;
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

    /* ====================== ⭐ EXPORT WORD ====================== */

    public function downloadWord(Quotation $quotation)
    {

        // ✅ โหลดเฉพาะ relation ที่มีจริง
        $quotation->load('customer', 'details.product');
        $customer = $quotation->customer;

        // ✅ รวมที่อยู่จากตาราง customers
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
        $leftCell->addText("ใบเสนอราคา", ['bold' => true, 'size' => 20]);

        // กลาง - โลโก้และชื่อบริษัท
        $centerCell = $headerTable->addCell(4000, ['valign' => 'center']);
        $logoPath = public_path('images/tfe-logo.png');
        if (file_exists($logoPath)) {
            $centerCell->addImage($logoPath, ['width' => 100, 'align' => 'center']);
        }
        $centerCell->addText("TWO FAMILY", ['bold' => true, 'size' => 14], ['align' => 'center']);
        $centerCell->addText("ENGINEERING CO., LTD.", ['size' => 10], ['align' => 'center']);

        // ขวา - เลขที่และวันที่

        $qtNo = 'QT' . str_pad($quotation->id_quot, 5, '0', STR_PAD_LEFT);

        // วันที่อัปเดต
        $date = Carbon::parse($quotation->updated_at)
            ->locale('th')
            ->translatedFormat('d F Y');
        $rightCell = $headerTable->addCell(3000);
        $rightCell->addText(
            "No.        {$qtNo}",
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

        foreach ($quotation->details as $d) {

            // ✅ คำนวณยอดบรรทัดให้ถูก
            $lineTotal = $d->price_per_unit * $d->quantity;

            $itemTable->addRow(300);

            $itemTable->addCell(1000)->addText(
                $no++,
                [],
                ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]
            );

            $itemTable->addCell(3500)->addText(
                $d->product->name_product ?? '-'
            );

            $itemTable->addCell(1500)->addText(
                $d->quantity,
                [],
                ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]
            );

            // ✅ ราคาต่อหน่วย
            $itemTable->addCell(1500)->addText(
                number_format($d->price_per_unit, 2),
                [],
                ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::END]
            );

            // ✅ รวมต่อรายการ
            $itemTable->addCell(2500)->addText(
                number_format($lineTotal, 2),
                [],
                ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::END]
            );

            // ✅ รวมยอดทั้งหมด
            $sum += $lineTotal;
        }

        $subtotal = $sum;
        $discount = $quotation->discount ?? 0;
        $netTotal = max($subtotal - $discount, 0);
        /* ================== สรุปราคา ================== */

        // รวมก่อนส่วนลด
        $itemTable->addRow(400);

        $cell1 = $itemTable->addCell(7500, ['gridSpan' => 4]);
        $cell1->addText('รวมก่อนส่วนลด', ['size' => 16]);

        $itemTable->addCell(2500)->addText(
            number_format($subtotal, 2),
            ['size' => 16],
            ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::END]
        );


        // ส่วนลด
        $itemTable->addRow(400);

        $cell2 = $itemTable->addCell(7500, ['gridSpan' => 4]);
        $cell2->addText('ส่วนลด', ['size' => 16]);

        $itemTable->addCell(2500)->addText(
            number_format($discount, 2),
            ['size' => 16],
            ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::END]
        );


        // ยอดสุทธิ
        $itemTable->addRow(400);

        $cell3 = $itemTable->addCell(7500, ['gridSpan' => 4]);
        $cell3->addText('ยอดสุทธิ', ['bold' => true, 'size' => 16]);

        $itemTable->addCell(2500)->addText(
            number_format($netTotal, 2) . ' บาท',
            ['bold' => true, 'size' => 16],
            ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::END]
        );

        // ================== หมายเหตุ ==================
        $section->addTextBreak(1);

        $section->addText("*หมายเหตุ", ['bold' => true], $compact);

        $notes = [
            "กรณีสั่งซื้อสินค้าดังกล่าว กรุณาลงชื่อหรือประทับตราบริษัท และส่งกลับมา",
            "ขอบพระคุณในความไว้วางใจ",
        ];

        foreach ($notes as $note) {
            $section->addText("- " . $note, [], $compact);
        }

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
        $leftSign->addText("ผู้สั่งซื้อ");

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

        /* ===== บันทึกไฟล์ ===== */
        $fileName = "quotation_{$qtNo}.docx";
        $path = storage_path($fileName);

        IOFactory::createWriter($phpWord, 'Word2007')->save($path);

        return response()->download($path)->deleteFileAfterSend(true);
    }
}
