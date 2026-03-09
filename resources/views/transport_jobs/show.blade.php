@extends('layout')

@section('namepage')
    <div class="container">
        <h3>รายละเอียดแผนงานขนส่ง</h3>
    </div>
@endsection

@section('content')
    <div class="container py-4">

        <div class="card shadow-sm" style="max-width:900px;margin:auto">
            <div class="card-body p-4">

                <div class="mb-3">
                    <strong>ลูกค้า:</strong><br>
                    {{ $job->customer?->name_customer ?? '-' }}
                </div>

                <div class="mb-3">
                    <strong>ช่วงวันที่:</strong><br>
                    {{ $job->start_date }}
                    @if ($job->end_date)
                        – {{ $job->end_date }}
                    @endif
                </div>

                <div class="mb-3">
                    <strong>เส้นทาง:</strong><br>
                    {{ $job->start_point }} → {{ $job->destination }}
                </div>

                <div class="mb-3">
                    <strong>ระยะทาง:</strong><br>
                    {{ number_format($job->distance_km, 2) }} กม.
                </div>

                <div class="mb-3">
                    <strong>รถบรรทุก:</strong><br>
                    {{ $job->truck?->id_truck ?? '-' }}
                </div>

                <div class="mb-3">
                    <strong>พนักงานขับรถ:</strong><br>
                    {{ $job->driver?->name_driver ?? '-' }}
                </div>

                <div class="d-flex gap-2 pt-3">
                    <a href="{{ route('transport-jobs.index') }}" class="btn btn-outline-secondary">
                        ย้อนกลับ
                    </a>

                    <a href="{{ route('transport-jobs.edit', $job) }}" class="btn btn-outline-primary">
                        แก้ไข
                    </a>
                </div>

            </div>
        </div>

    </div>
@endsection
