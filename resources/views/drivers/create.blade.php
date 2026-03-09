@extends('layout')

@section('content')
    <div class="container py-4">

        <h3 class="mb-3">เพิ่มข้อมูลคนขับรถ</h3>

        @if (session('ok'))
            <div class="alert alert-success">{{ session('ok') }}</div>
        @endif

        <form method="POST" action="{{ route('drivers.store') }}">
            @csrf

            <div class="mb-3">
                <label class="form-label">ชื่อคนขับ</label>
                <input type="text" name="name_driver" class="form-control" placeholder="เช่น นายกรภัทร สิงวะราช" required>
            </div>

            <div class="mb-3">
                <label class="form-label">บ้านเลขที่ / หมู่</label>
                <input type="text" name="address_detail" class="form-control" placeholder="เช่น 123/45 หมู่ 6">
            </div>

            <div class="mb-3">
                <label class="form-label">ตำบล</label>
                <input type="text" name="subdistrict" class="form-control" placeholder="เช่น ศิลา">
            </div>

            <div class="mb-3">
                <label class="form-label">อำเภอ</label>
                <input type="text" name="district" class="form-control" placeholder="เช่น เมืองขอนแก่น">
            </div>

            <div class="mb-3">
                <label class="form-label">จังหวัด</label>
                <select name="province" id="province" class="form-select" required>
                    <option value="">— เลือกจังหวัด —</option>
                    @foreach ($provinces as $p)
                        <option value="{{ $p }}">{{ $p }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">รหัสไปรษณีย์</label>
                <input type="text" name="zipcode" class="form-control" maxlength="5" pattern="[0-9]{5}"
                    inputmode="numeric" placeholder="เช่น 40000" value="{{ old('zipcode', $driver->zipcode ?? '') }}">
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
            <a href="{{ route('drivers.index') }}" class="btn btn-outline-secondary">ย้อนกลับ</a>
        </form>
    </div>

    {{-- Select2 --}}
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/js/select2.min.js"></script>
    <script>
        $(function() {
            $('#province').select2({
                placeholder: 'พิมพ์ชื่อจังหวัดเพื่อค้นหา',
                width: '100%'
            });
        });
    </script>
@endsection
