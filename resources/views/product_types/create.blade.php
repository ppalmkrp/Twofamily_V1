@extends('layout')

@section('namepage')
    <div class="container">
        <h3>เพิ่มประเภทสินค้า</h3>
    </div>
@endsection

@section('content')
    <div class="container py-3">
        <form method="POST" action="{{ route('product_types.store') }}">
            @csrf

            <div class="mb-3">
                <label class="form-label">ชื่อประเภทสินค้า</label>
                <input type="text" name="name_product_type" class="form-control" placeholder="เช่น ดิน, ทราย, หิน" required>
            </div>

            <button type="submit" class="btn btn-dark">บันทึก</button>
            <a href="{{ route('product_types.index') }}" class="btn btn-outline-secondary">ย้อนกลับ</a>
        </form>
    </div>
@endsection
