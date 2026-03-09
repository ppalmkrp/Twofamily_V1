@extends('layout')

@section('namepage')
    <div class="container">
        <h3>แก้ไขพนักงานขับรถ</h3>
    </div>
@endsection

@section('content')
    <div class="container py-4">

        <form method="POST" action="{{ route('drivers.update', $driver) }}">
            @csrf @method('PUT')

            <div class="mb-3">
                <label class="form-label">ชื่อ</label>
                <input type="text" name="name_driver" class="form-control"
                    value="{{ old('name_driver', $driver->name_driver) }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">บ้านเลขที่ / หมู่</label>
                <input type="text" name="address_detail" class="form-control"
                    value="{{ old('address_detail', $driver->address_detail) }}">
            </div>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">ตำบล</label>
                    <input type="text" name="subdistrict" class="form-control"
                        value="{{ old('subdistrict', $driver->subdistrict) }}">
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">อำเภอ</label>
                    <input type="text" name="district" class="form-control"
                        value="{{ old('district', $driver->district) }}">
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">จังหวัด</label>
                    <input type="text" name="province" class="form-control"
                        value="{{ old('province', $driver->province) }}">
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">รหัสไปรษณีย์</label>
                <input type="text" name="zipcode" class="form-control" maxlength="5" pattern="[0-9]{5}"
                    inputmode="numeric" placeholder="เช่น 10240" value="{{ old('zipcode', $driver->zipcode ?? '') }}">
            </div>


            <div class="mb-3">
                <label class="form-label">เบอร์โทร (10 หลัก)</label>
                <input type="text" name="phone_driver" class="form-control" maxlength="10" pattern="[0-9]{10}"
                    inputmode="numeric" placeholder="เช่น 0812345678"
                    value="{{ old('phone_driver', $driver->phone_driver ?? '') }}">
            </div>


            <div class="mb-3">
                <label class="form-label">เลขบัตรประชาชน (13 หลัก)</label>
                <input type="text" name="citizenid_driver" class="form-control" maxlength="13" pattern="[0-9]{13}"
                    inputmode="numeric" placeholder="เช่น 1234567890123"
                    value="{{ old('citizenid_driver', $driver->citizenid_driver ?? '') }}">
            </div>


            <button class="btn btn-dark">บันทึก</button>
            <a href="{{ route('drivers.index') }}" class="btn btn-outline-secondary">ยกเลิก</a>

        </form>
    </div>
@endsection
