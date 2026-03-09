@extends('layout')

@section('namepage')
    <div class="container">
        <h3>ข้อมูลพนักงานขับรถ</h3>
    </div>
@endsection

@section('content')
    <style>
        .detail-card {
            max-width: 800px;
            margin-inline: auto;
            border: 1px solid rgba(0, 0, 0, .08);
            box-shadow: 0 8px 30px rgba(0, 0, 0, .06);
            border-radius: 14px;
        }

        .label {
            font-weight: 600;
            color: #555
        }

        .value {
            font-size: 1.05rem
        }
    </style>

    <div class="container py-4">
        <div class="card detail-card">
            <div class="card-body p-4 p-lg-5">

                <div class="mb-3">
                    <div class="label">ชื่อ-สกุล</div>
                    <div class="value">{{ $driver->name_driver }}</div>
                </div>

                <div class="mb-3">
                    <div class="label">เบอร์โทร</div>
                    <div class="value">{{ $driver->phone_driver ?: '-' }}</div>
                </div>

                <div class="mb-3">
                    <div class="label">เลขบัตรประชาชน</div>
                    <div class="value">{{ $driver->citizenid_driver ?: '-' }}</div>
                </div>

                <div class="mb-3">
                    <div class="label">ที่อยู่</div>
                    <div class="value">
                        {{ $driver->address_detail }}
                        {{ $driver->subdistrict }}
                        {{ $driver->district }}
                        {{ $driver->province }}
                        {{ $driver->zipcode }}
                    </div>
                </div>

                <div class="pt-3 d-flex gap-2">
                    <a href="{{ route('drivers.index') }}" class="btn btn-outline-secondary">
                        ย้อนกลับ
                    </a>

                    <a href="{{ route('drivers.edit', $driver) }}" class="btn btn-outline-primary">
                        แก้ไข
                    </a>
                </div>

            </div>
        </div>
    </div>
@endsection
