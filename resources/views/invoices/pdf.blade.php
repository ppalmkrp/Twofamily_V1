<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">

    <style>
        @font-face {
            font-family: 'sarabun';
            src: url("{{ public_path('fonts/Sarabun-Regular.ttf') }}") format('truetype');
        }

        body {
            font-family: 'sarabun';
            font-size: 13px;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .title {
            font-size: 20px;
            font-weight: bold;
        }

        .line {
            border-bottom: 1px solid #000;
            margin: 8px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 5px;
            border: 1px solid #999;
        }

        .no-border td {
            border: none;
        }
    </style>
</head>

<body>

    <!-- HEADER -->
    <table class="no-border">
        <tr>
            <td>
                <div class="title">ใบแจ้งหนี้</div>
                <div class="sub-title">Invoice</div>
            </td>


            <td class="text-right">
                เลขที่: INV{{ str_pad($invoice->id_invoice, 5, '0', STR_PAD_LEFT) }}<br>
                วันที่: {{ now()->format('d/m/Y') }}<br>
                ครบกำหนด: {{ now()->addDays(7)->format('d/m/Y') }}
            </td>
        </tr>
    </table>

    <div class="line"></div>

    <!-- CUSTOMER -->
    <table class="no-border">
        <tr>
            <td width="60%">
                <b>ลูกค้า:</b> {{ $invoice->customer->name_customer }}<br>
                <b>ที่อยู่:</b> {{ $invoice->customer->address_detail }}<br>
                <b>โทร:</b> {{ $invoice->customer->phone_customer }}<br>
            </td>

            <td width="40%">
                <b>ผู้ออก:</b> {{ setting('company_name') }}<br>
                <b>ที่อยู่:</b> {{ setting('company_address') }}<br>
                <b>โทร:</b> {{ setting('company_phone') }}<br>
                <b>เลขผู้เสียภาษี:</b> {{ setting('tax_id') }}
            </td>
        </tr>
    </table>

    <div class="line"></div>

    <!-- ITEMS -->
    <table>
        <thead>
            <tr>
                <th>ลำดับ</th>
                <th>รายละเอียด</th>
                <th>จำนวน</th>
                <th>ราคา</th>
                <th>รวม</th>
            </tr>
        </thead>

        <tbody>
            @php
                $sum = 0;
                $i = 1;
            @endphp

            @foreach ($invoice->details as $d)
                @php
                    $total = $d->quantity * $d->price;
                    $sum += $total;
                @endphp

                <tr>
                    <td class="text-center">{{ $i++ }}</td>
                    <td>{{ $d->product->name_product }}</td>
                    <td class="text-center">{{ $d->quantity }}</td>
                    <td class="text-right">{{ number_format($d->price, 2) }}</td>
                    <td class="text-right">{{ number_format($total, 2) }}</td>
                </tr>
            @endforeach

        </tbody>
    </table>

    <br>

    @php
        $vat = $sum * 0.07;
        $grand = $sum + $vat;
    @endphp

    <!-- TOTAL -->
    <table class="no-border">
        <tr>
            <td width="60%">
                <b>หมายเหตุ:</b><br>
                {!! nl2br(e(setting('invoice_note'))) !!}
            </td>

            <td width="40%">
                <table>
                    <tr>
                        <td>รวม</td>
                        <td class="text-right">{{ number_format($sum, 2) }}</td>
                    </tr>

                    <tr>
                        <td>VAT 7%</td>
                        <td class="text-right">{{ number_format($vat, 2) }}</td>
                    </tr>

                    <tr>
                        <td><b>รวมสุทธิ</b></td>
                        <td class="text-right"><b>{{ number_format($grand, 2) }}</b></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <div class="line"></div>

    <!-- PAYMENT -->
    <table class="no-border">
        <tr>
            <td width="50%">
                <b>การชำระเงิน</b><br>
                ธนาคาร: {{ setting('bank_name') }}<br>
                เลขบัญชี: {{ setting('bank_number') }}<br>
            </td>

            <td width="50%" class="text-center">
                _________________________<br>
                {{ setting('invoice_sign_name') }}
            </td>
        </tr>
    </table>

</body>

</html>
