@extends('layout')

@section('namepage')
    <div class="container">
        <h3>แก้ไขรถบรรทุก {{ $truck->id_truck }}</h3>
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

        <form method="POST" action="{{ route('trucks.update', $truck->id_truck) }}" autocomplete="off">
            @csrf @method('PUT')

            @include('trucks._form', [
                'truck' => $truck,
                'mode' => 'edit',
                'brands' => $brands,
                'provinces' => config('provinces'),
            ])

            <button class="btn btn-dark">บันทึกการแก้ไข</button>
            <a href="{{ route('trucks.index') }}" class="btn btn-outline-secondary">ย้อนกลับ</a>

        </form>
    </div>
@endsection