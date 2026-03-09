@extends('layout')

@section('namepage')
    <div class="container">
        <h3>เพิ่มแผนงานขนส่ง</h3>
    </div>
@endsection

@section('content')
    <div class="container py-4">

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('transport-jobs.store') }}">
            @csrf

            <div class="card shadow-sm">
                <div class="card-body row g-3">

                    {{-- ลูกค้า --}}
                    <div class="col-md-6">
                        <label class="form-label">ลูกค้า</label>
                        <select name="customer_id" class="form-select" required>
                            <option value="">— เลือกลูกค้า —</option>
                            @foreach ($customers as $c)
                                <option value="{{ $c->id_customer }}">
                                    {{ $c->name_customer }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- รถ --}}
                    <div class="col-md-3">
                        <label class="form-label">รถบรรทุก</label>
                        <select name="truck_id" class="form-select" required>
                            <option value="">— เลือกรถ —</option>
                            @foreach ($trucks as $t)
                                <option value="{{ $t->id_truck }}">
                                    {{ $t->id_truck }} ({{ $t->brand_truck }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- คนขับ --}}
                    <div class="col-md-3">
                        <label class="form-label">พนักงานขับรถ</label>
                        <select name="driver_id" class="form-select" required>
                            <option value="">— เลือกคนขับ —</option>
                            @foreach ($drivers as $d)
                                <option value="{{ $d->id_driver }}">
                                    {{ $d->name_driver }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- วันที่ --}}
                    <div class="col-md-3">
                        <label class="form-label">วันเริ่มต้น</label>
                        <input type="date" name="start_date" class="form-control" required>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">วันสิ้นสุด</label>
                        <input type="date" name="end_date" class="form-control">
                    </div>

                    {{-- เส้นทาง --}}
                    <div class="col-md-6">
                        <label class="form-label">ต้นทาง</label>
                        <input type="text" id="start_point" name="start_point" class="form-control" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">ปลายทาง</label>
                        <input type="text" id="destination" name="destination" class="form-control" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">ระยะทาง (กม.)</label>
                        <input type="number" step="0.01" id="distance_km" name="distance_km" class="form-control"
                            readonly>
                    </div>

                </div>
            </div>

            <div class="mt-3 d-flex gap-2">
                <button class="btn btn-dark">บันทึก</button>
                <a href="{{ route('transport-jobs.index') }}" class="btn btn-outline-secondary">ย้อนกลับ</a>
            </div>

        </form>
    </div>

    {{-- Distance API --}}
    <script>
        async function getCoordinates(place) {
            const r = await fetch(
                `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(place)}`
            );
            const d = await r.json();
            return d.length ? [d[0].lat, d[0].lon] : null;
        }

        async function calcDistance() {
            if (!start_point.value || !destination.value) return;

            const s = await getCoordinates(start_point.value);
            const d = await getCoordinates(destination.value);
            if (!s || !d) return;

            const url =
                `https://router.project-osrm.org/route/v1/driving/${s[1]},${s[0]};${d[1]},${d[0]}?overview=false`;

            const r = await fetch(url);
            const j = await r.json();

            distance_km.value = (j.routes[0].distance / 1000).toFixed(2);
        }

        start_point.addEventListener('change', calcDistance);
        destination.addEventListener('change', calcDistance);
    </script>
@endsection
