@extends('layout')

@section('namepage')
    <div class="container">
        <h3>เพิ่มแคมป์งาน</h3>
    </div>
@endsection

@section('content')
    <div class="container py-4">

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0 ps-3">
                    @foreach ($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('camps.store') }}" autocomplete="off">
            @csrf

            @include('camps._form', [
                'camp' => $camp,
                'customers' => $customers,
            ])

            <button class="btn btn-dark">บันทึก</button>
            <a href="{{ route('camps.index') }}" class="btn btn-outline-secondary">ย้อนกลับ</a>

        </form>
    </div>
@endsection