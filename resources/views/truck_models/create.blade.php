@extends('layout')

@section('namepage')
    <div class="container">
        <h3>{{ isset($truck_model) ? 'แก้ไขรุ่นรถบรรทุก' : 'เพิ่มรุ่นรถบรรทุก' }}</h3>
    </div>
@endsection

@section('content')
    <div class="container py-3">
        <form method="POST"
            action="{{ isset($truck_model) ? route('truck_models.update', $truck_model->id) : route('truck_models.store') }}">
            @csrf
            @if (isset($truck_model))
                @method('PUT')
            @endif

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label>เลือกยี่ห้อรถบรรทุก <span class="text-danger">*</span></label>
                        <select name="truck_brand_id" class="form-select" required>
                            <option value="" disabled selected>-- โปรดเลือกยี่ห้อ --</option>
                            @foreach ($brands as $b)
                                <option value="{{ $b->id }}" @selected(old('truck_brand_id', $truck_model->truck_brand_id ?? '') == $b->id)>
                                    {{ $b->name_brand }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label>ชื่อรุ่นรถบรรทุก <span class="text-danger">*</span></label>
                        <input type="text" name="name_model" class="form-control" placeholder="เช่น Victor 500"
                            value="{{ old('name_model', $truck_model->name_model ?? '') }}" required>
                    </div>

                    <button class="btn btn-success">บันทึก</button>
                    <a href="{{ route('truck_models.index') }}" class="btn btn-secondary">ยกเลิก</a>
                </div>
            </div>
        </form>
    </div>
@endsection
