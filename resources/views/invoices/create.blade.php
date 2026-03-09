@extends('layout')

@section('namepage')
    <div class="container">
        <h3>สร้างใบเสนอราคาใหม่</h3>
    </div>
@endsection

@section('content')
    <div class="container py-3">

        <form method="POST" action="{{ route('invoices.store') }}">
            @csrf

            <div class="mb-3">
                <label class="form-label fw-semibold">เลือกลูกค้า</label>
                <select name="customer_id" class="form-select" required>
                    <option value="">— เลือกลูกค้า —</option>
                    @foreach ($customers as $c)
                        <option value="{{ $c->id_customer }}">
                            {{ $c->name_customer }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">รายการสินค้า</label>

                <table class="table table-bordered align-middle" id="productTable">
                    <thead class="table-light">
                        <tr>
                            <th style="width:35%">สินค้า</th>
                            <th style="width:15%">จำนวน</th>
                            <th style="width:20%">ราคาต่อหน่วย (บาท / คิว)</th>
                            <th style="width:20%">ราคารวม</th>
                            <th style="width:10%"></th>
                        </tr>
                    </thead>

                    <tbody id="productRows">
                        <tr>
                            <td>
                                <select name="products[0][product_id]" class="form-select product-select" required>
                                    <option value="">— เลือกสินค้า —</option>
                                    @foreach ($products as $p)
                                        <option value="{{ $p->id_product }}" data-price="{{ $p->unit_price }}">
                                            {{ $p->name_product }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <input type="number" name="products[0][quantity]" class="form-control quantity"
                                    min="1" value="1">
                            </td>
                            <td>
                                <input type="number" name="products[0][total_price]" class="form-control unit-price"
                                    readonly>
                            </td>
                            <td>
                                <input type="number" class="form-control total-amount" readonly>
                            </td>
                            <td>
                                <button type="button" class="btn btn-outline-danger btn-sm remove-row">
                                    ลบ
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <button type="button" id="addRow" class="btn btn-sm btn-outline-dark">
                    + เพิ่มสินค้า
                </button>
            </div>

            <div class="row justify-content-end mt-4">
                <div class="col-md-5">

                    <table class="table table-borderless">
                        <tr>
                            <th class="text-end">ยอดรวมก่อนส่วนลด</th>
                            <td class="text-end">
                                <span id="subTotal">0.00</span> บาท
                            </td>
                        </tr>

                        {{-- <tr>
                            <th class="text-end align-middle">ส่วนลด</th>
                            <td>
                                <input type="number" name="discount" id="discount" class="form-control text-end"
                                    value="0" min="0">
                            </td>
                        </tr> --}}

                        <tr>
                            <th class="text-end">VAT 7%</th>
                            <td class="text-end">
                                <span id="vatAmount">0.00</span> บาท
                            </td>
                        </tr>

                        <tr class="table-primary fw-bold">
                            <th class="text-end">ยอดสุทธิ</th>
                            <td class="text-end">
                                <span id="grandTotal">0.00</span> บาท
                            </td>
                        </tr>
                    </table>

                </div>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-success">บันทึกใบแจ้งหนี้</button>
                <a href="{{ route('invoices.index') }}" class="btn btn-outline-secondary">ยกเลิก</a>
            </div>
        </form>
    </div>

    <script>
document.addEventListener('DOMContentLoaded', () => {

    const addRow = document.getElementById('addRow');
    const productRows = document.getElementById('productRows');
    const discountInput = document.getElementById('discount');

    const subTotalEl = document.getElementById('subTotal');
    const vatEl = document.getElementById('vatAmount');
    const grandTotalEl = document.getElementById('grandTotal');

    let index = 1;

    function calculateSummary() {
        let subTotal = 0;

        document.querySelectorAll('.total-amount').forEach(input => {
            subTotal += parseFloat(input.value || 0);
        });

        let discount = parseFloat(discountInput.value || 0);
        let afterDiscount = Math.max(subTotal - discount, 0);
        let vat = afterDiscount * 0.07;
        let grandTotal = afterDiscount + vat;

        subTotalEl.textContent = subTotal.toFixed(2);
        vatEl.textContent = vat.toFixed(2);
        grandTotalEl.textContent = grandTotal.toFixed(2);
    }

    addRow.addEventListener('click', () => {
        const newRow = productRows.children[0].cloneNode(true);
        newRow.querySelectorAll('input, select').forEach(input => {
            const name = input.getAttribute('name');
            if (name) input.setAttribute('name', name.replace(/\[\d+\]/, `[${index}]`));
            if (input.tagName === 'INPUT') {
                input.value = input.classList.contains('quantity') ? 1 : '';
            }
        });
        productRows.appendChild(newRow);
        index++;
    });

    productRows.addEventListener('input', (e) => {
        const row = e.target.closest('tr');
        const select = row.querySelector('.product-select');
        const quantity = row.querySelector('.quantity');
        const unit = row.querySelector('.unit-price');
        const total = row.querySelector('.total-amount');

        const price = select.selectedOptions[0]?.dataset.price || 0;
        unit.value = price;
        total.value = price * quantity.value;

        calculateSummary();
    });

    discountInput.addEventListener('input', calculateSummary);

    productRows.addEventListener('click', (e) => {
        if (e.target.classList.contains('remove-row')) {
            if (productRows.children.length > 1) {
                e.target.closest('tr').remove();
                calculateSummary();
            }
        }
    });
});
</script>
@endsection
