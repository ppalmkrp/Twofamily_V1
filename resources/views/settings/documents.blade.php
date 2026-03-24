@extends('layout')

@section('namepage')
ตั้งค่าเอกสาร
@endsection

@section('content')

<div class="mb-3">
    <a href="{{ route('settings.index') }}" class="btn btn-secondary">
        ย้อนกลับ
    </a>
</div>

<div class="row g-3">

    <div class="col-md-4">
        <a href="{{ route('settings.quotation') }}" class="text-decoration-none">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body text-center">
                    <h5>ใบเสนอราคา</h5>
                </div>
            </div>
        </a>
    </div>

    <div class="col-md-4">
        <a href="{{ route('settings.invoice') }}" class="text-decoration-none">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body text-center">
                    <h5>ใบแจ้งหนี้</h5>
                </div>
            </div>
        </a>
    </div>

    <div class="col-md-4">
        <a href="{{ route('settings.documents.receipt') }}" class="text-decoration-none">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body text-center">
                    <h5>ใบเสร็จ</h5>
                </div>
            </div>
        </a>
    </div>

</div>
@endsection