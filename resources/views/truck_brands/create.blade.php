@extends('layout')

@section('namepage')
    <div class="container">
        <h3>{{ isset($truck_brand) ? 'แก้ไขยี่ห้อรถบรรทุก' : 'เพิ่มยี่ห้อรถบรรทุก' }}</h3>
    </div>
@endsection

@section('content')
    <div class="container py-3">
        @if ($errors->any())
            <div class="alert alert-danger">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST"
            action="{{ isset($truck_brand) ? route('truck_brands.update', $truck_brand->id) : route('truck_brands.store') }}">
            @csrf
            @if (isset($truck_brand))
                @method('PUT')
            @endif

            <div class="mb-3">
                <label>ชื่อยี่ห้อรถบรรทุก</label>
                <input type="text" name="name_brand" class="form-control @error('name_brand') is-invalid @enderror"
                    pattern="[A-Za-zก-\s]+" title="กรอกได้เฉพาะตัวอักษรเท่านั้น" placeholder="เช่น HINO, ISUZU"
                    value="{{ old('name_brand', $truck_brand->name_brand ?? '') }}">

            </div>

            <button class="btn btn-success">บันทึก</button>
            <a href="{{ route('truck_brands.index') }}" class="btn btn-secondary">ยกเลิก</a>
        </form>
    </div>
@endsection
