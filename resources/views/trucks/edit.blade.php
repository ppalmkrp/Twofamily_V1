@extends('layout')

@section('namepage')
    <div class="container">
        <h3>แก้ไขรถบรรทุก: {{ $truck->id_truck }}</h3>
    </div>
@endsection

@php
$provinces = [
    'กรุงเทพมหานคร','กระบี่','กาญจนบุรี','กาฬสินธุ์','กำแพงเพชร',
    'ขอนแก่น','จันทบุรี','ฉะเชิงเทรา','ชลบุรี','ชัยนาท',
    'ชัยภูมิ','ชุมพร','เชียงราย','เชียงใหม่','ตรัง',
    'ตราด','ตาก','นครนายก','นครปฐม','นครพนม',
    'นครราชสีมา','นครศรีธรรมราช','นครสวรรค์','นนทบุรี','นราธิวาส',
    'น่าน','บึงกาฬ','บุรีรัมย์','ปทุมธานี','ประจวบคีรีขันธ์',
    'ปราจีนบุรี','ปัตตานี','พระนครศรีอยุธยา','พะเยา','พังงา',
    'พัทลุง','พิจิตร','พิษณุโลก','เพชรบุรี','เพชรบูรณ์',
    'แพร่','ภูเก็ต','มหาสารคาม','มุกดาหาร','แม่ฮ่องสอน',
    'ยโสธร','ยะลา','ร้อยเอ็ด','ระนอง','ระยอง',
    'ราชบุรี','ลพบุรี','ลำปาง','ลำพูน','เลย',
    'ศรีสะเกษ','สกลนคร','สงขลา','สตูล','สมุทรปราการ',
    'สมุทรสงคราม','สมุทรสาคร','สระแก้ว','สระบุรี','สิงห์บุรี',
    'สุโขทัย','สุพรรณบุรี','สุราษฎร์ธานี','สุรินทร์','หนองคาย',
    'หนองบัวลำภู','อ่างทอง','อุดรธานี','อุตรดิตถ์',
    'อุทัยธานี','อุบลราชธานี','อำนาจเจริญ'
];
@endphp

@section('content')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .card-wrap {
            max-width: 900px;
            margin-inline: auto;
            border: 1px solid rgba(0, 0, 0, .08);
            box-shadow: 0 8px 30px rgba(0, 0, 0, .06);
            border-radius: 14px;
        }
    </style>

    <div class="container py-4">
        @if (session('ok'))
            <div class="alert alert-success shadow-sm">{{ session('ok') }}</div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger shadow-sm">
                <div class="fw-semibold mb-1">กรุณาตรวจสอบข้อมูล:</div>
                <ul class="mb-0">
                    @foreach ($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card card-wrap">
            <div class="card-body p-4 p-lg-5">
                <form method="POST" action="{{ route('trucks.update', $truck->id_truck) }}" autocomplete="off">
                    @csrf @method('PUT')
                    @include('trucks._form', ['truck' => $truck, 'mode' => 'edit'])

                    <div class="mt-4 d-flex gap-2">
                        <button class="btn btn-dark btn-lg px-4" type="submit">บันทึกการแก้ไข</button>
                        <a href="{{ route('trucks.index') }}" class="btn btn-outline-secondary btn-lg">ย้อนกลับ</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
