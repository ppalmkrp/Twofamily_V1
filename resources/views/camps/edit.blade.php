@extends('layout')

@section('namepage')
    <div class="container">
        <h3>แก้ไขแคมป์ {{ $camp->name_camp }}</h3>
    </div>
@endsection

@section('content')
    <div class="container py-4">
        @if ($errors->any())
            <div class="alert alert-danger shadow-sm">
                <div class="fw-semibold mb-1">กรุณาตรวจสอบข้อมูล:</div>
                <ul class="mb-0 ps-3">
                    @foreach ($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card border-0 shadow-sm mx-auto" style="max-width: 900px;">
            <div class="card-body p-4 p-lg-5">
                <form method="POST" action="{{ route('camps.update', $camp->id_camp) }}" autocomplete="off">
                    @csrf @method('PUT')

                    @include('camps._form', [
                        'camp' => $camp,
                        'customers' => $customers,
                        'provinces' => config('provinces'),
                    ])

                    <div class="mt-4 d-flex gap-2">
                        <button class="btn btn-dark">บันทึก</button>
                        <a href="{{ route('camps.index') }}" class="btn btn-outline-secondary">ย้อนกลับ</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
