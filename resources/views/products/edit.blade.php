@extends('layout')

@section('namepage')
    <div class="container">
        <h3>แก้ไขสินค้า</h3>
    </div>
@endsection

@section('content')
    <div class="container py-3">
        <form method="POST" action="{{ route('products.update', $product) }}">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">ชื่อสินค้า</label>
                <input type="text" name="name_product" class="form-control"
                    value="{{ old('name_product', $product->name_product) }}" placeholder="เช่น หินกรวด" required>
            </div>

            <div class="mb-3">
                <label class="form-label">รายละเอียด</label>
                <textarea name="detail_product" class="form-control" rows="3" placeholder="เช่น ใช้สำหรับงานก่อสร้าง หรือถนน">{{ old('detail_product', $product->detail_product) }}</textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">ราคาต่อหน่วย (บาท / คิว)</label>
                <input type="number" name="unit_price" class="form-control"
                    value="{{ old('unit_price', $product->unit_price) }}" placeholder="เช่น 390" min="0" required>
            </div>

            <div class="mb-3">
                <label class="form-label">ประเภทสินค้า</label>

                <select name="product_type_id" class="form-select">
                    <option value="">— เลือกประเภทสินค้า —</option>
                    @foreach ($types as $t)
                        <option value="{{ $t->id_product_type }}" @selected(old('product_type_id', $product->product_type_id) == $t->id_product_type)>
                            {{ $t->name_product_type }}
                        </option>
                    @endforeach
                </select>
                <div class="form-text text-muted">
                    เลือกประเภทของสินค้า เช่น ดิน, ทราย, หิน
                </div>
            </div>

            <button type="submit" class="btn btn-dark">อัปเดต</button>
            <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">ย้อนกลับ</a>
        </form>
    </div>
@endsection
