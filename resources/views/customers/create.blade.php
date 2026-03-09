@extends('layout')

@section('namepage')
    <div class="container">
        <h3>เพิ่มลูกค้า</h3>
    </div>
@endsection

@section('content')
    <div class="container py-4">

        <form method="POST" action="{{ route('customers.store') }}">
            @csrf

            <div class="mb-3">
                <label class="form-label">ชื่อลูกค้า</label>
                <input type="text" name="name_customer" class="form-control"
                    placeholder="เช่น บริษัท ทูแฟมิลี่ เอ็นจิเนียริ่ง จำกัด หรือ นายกรภัทร สิงวะราช" required>
            </div>

            <div class="mb-3">
                <label class="form-label">ประเภทลูกค้า</label>
                <select name="customer_type" class="form-select">
                    <option value="person">บุคคล</option>
                    <option value="company">บริษัท</option>
                </select>
                <div class="form-text text-muted">
                    เลือกประเภทของลูกค้า
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">เบอร์โทร (10 หลัก)</label>
                <input type="text" name="phone_customer" class="form-control" maxlength="10" pattern="[0-9]{10}"
                    inputmode="numeric" placeholder="เช่น 0812345678">
            </div>

            <div class="mb-3">
                <label class="form-label">อีเมล</label>
                <input type="email" name="email_customer" class="form-control" placeholder="เช่น example@email.com">
            </div>

            <div class="mb-3">
                <label class="form-label">บ้านเลขที่ / หมู่</label>
                <input type="text" name="address_detail" class="form-control" placeholder="เช่น 123/45 หมู่ 6">
            </div>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">ตำบล</label>
                    <input type="text" name="subdistrict" class="form-control" placeholder="เช่น ศิลา">
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">อำเภอ</label>
                    <input type="text" name="district" class="form-control" placeholder="เช่น เมืองขอนแก่น">
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">จังหวัด</label>
                    <select name="province" class="form-select">
                        <option value="">— เลือกจังหวัด —</option>
                        @foreach ($provinces as $p)
                            <option value="{{ $p }}">{{ $p }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">รหัสไปรษณีย์</label>
                <input type="text" name="zipcode" class="form-control" maxlength="5" pattern="[0-9]{5}"
                    inputmode="numeric" placeholder="เช่น 40000">
            </div>

            <button class="btn btn-dark">บันทึก</button>
            <a href="{{ route('customers.index') }}" class="btn btn-outline-secondary">ย้อนกลับ</a>

        </form>
    </div>
@endsection
