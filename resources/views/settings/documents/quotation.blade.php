@extends('layout')

@section('namepage')
    ⚙️ ตั้งค่าใบเสนอราคา
@endsection

@section('content')
    <div class="card shadow-sm border-0">
        <div class="card-body">

            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('settings.quotation.update') }}">
                @csrf

                <div class="row g-3">

                    <!-- ชื่อบริษัท -->
                    <div class="col-md-6">
                        <label class="form-label">ชื่อบริษัท (ผู้ออกเอกสาร)</label>
                        <input type="text" class="form-control" name="company_name"
                            value="{{ $settings['company_name'] ?? '' }}">
                    </div>

                    <!-- ที่อยู่ -->
                    <div class="col-md-12">
                        <label class="form-label">ที่อยู่บริษัท</label>
                        <textarea class="form-control" rows="2" name="company_address">{{ $settings['company_address'] ?? '' }}</textarea>
                    </div>

                    <!-- เบอร์โทร -->
                    <div class="col-md-6">
                        <label class="form-label">เบอร์โทร</label>
                        <input type="text" class="form-control" name="company_phone"
                            value="{{ $settings['company_phone'] ?? '' }}">
                    </div>

                    <!-- เลขผู้เสียภาษี -->
                    <div class="col-md-6">
                        <label class="form-label">เลขผู้เสียภาษี</label>
                        <input type="text" class="form-control" name="tax_id" value="{{ $settings['tax_id'] ?? '' }}">
                    </div>

                    <!-- เครดิต -->
                    <div class="col-md-4">
                        <label class="form-label">เครดิต (วัน)</label>
                        <input type="number" class="form-control" name="credit_term"
                            value="{{ $settings['credit_term'] ?? 14 }}">
                    </div>

                    <!-- หมายเหตุ -->
                    <div class="col-12">
                        <label class="form-label">หมายเหตุ</label>
                        <textarea class="form-control" rows="3" name="quotation_note">{{ $settings['quotation_note'] ?? '' }}</textarea>
                    </div>

                </div>

                <div class="mt-4 d-flex justify-content-between">
                    <a href="{{ route('settings.documents') }}" class="btn btn-secondary">
                        ⬅ กลับ
                    </a>

                    <button type="submit" class="btn btn-primary">
                        💾 บันทึก
                    </button>
                </div>

            </form>

        </div>
    </div>
@endsection
