@extends('layout')

@section('namepage')
    <div class="container">
        <h3>{{ isset($truck_brand) ? 'แก้ไขยี่ห้อรถบรรทุก' : 'เพิ่มยี่ห้อรถบรรทุก' }}</h3>
    </div>
@endsection

@section('content')
    <div class="container py-3">
        <form method="POST"
            action="{{ isset($truck_brand) ? route('truck_brands.update', $truck_brand->id) : route('truck_brands.store') }}">
            @csrf
            @if (isset($truck_brand))
                @method('PUT')
            @endif

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label>ชื่อยี่ห้อรถบรรทุก <span class="text-danger">*</span></label>
                        <input type="text" name="name_brand" class="form-control" placeholder="เช่น HINO, ISUZU"
                            value="{{ old('name_brand', $truck_brand->name_brand ?? '') }}" required>
                    </div>

                    <button class="btn btn-success">บันทึก</button>
                    <a href="{{ route('truck_brands.index') }}" class="btn btn-secondary">ยกเลิก</a>
                </div>
            </div>
        </form>
    </div>
@endsection
