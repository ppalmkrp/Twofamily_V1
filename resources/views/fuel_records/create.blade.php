@extends('layout')

@section('namepage')
    <div class="container">
        <h3>เพิ่มบันทึกน้ำมัน</h3>
    </div>
@endsection

@section('content')
    <div class="container py-3">
        <form method="POST"
            action="{{ isset($fuel_record)
                ? route('fuel_records.update', $fuel_record->id_fuel_record)
                : route('fuel_records.store') }}">
            @csrf
            @if (isset($fuel_record))
                @method('PUT')
            @endif

            <div class="row">
                <!-- ช่องกรอกข้อมูล -->
                <div class="col-md-6">
                    <div class="mb-3">
                        <label>วันที่</label>
                        <input type="date" name="date_record" class="form-control"
                            value="{{ old('date_record', $fuel_record->date_record ?? '') }}">
                    </div>

                    <div class="mb-3">
                        <label>รถบรรทุก</label>
                        <select name="trucks_id_truck" class="form-select">
                            @foreach ($trucks as $t)
                                <option value="{{ $t->id_truck }}" @selected(old('trucks_id_truck', $fuel_record->trucks_id_truck ?? '') == $t->id_truck)>
                                    {{ $t->brand_truck }} ({{ $t->id_truck }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label>จุดเริ่มต้น</label>
                        <input type="text" id="start_point" name="start_point" class="form-control"
                            placeholder="กรอกสถานที่เริ่มต้น"
                            value="{{ old('start_point', $fuel_record->start_point ?? '') }}">
                    </div>

                    <div class="mb-3">
                        <label>ปลายทาง</label>
                        <input type="text" id="destination" name="destination" class="form-control"
                            placeholder="กรอกสถานที่ปลายทาง"
                            value="{{ old('destination', $fuel_record->destination ?? '') }}">
                    </div>

                    <div class="mb-3">
                        <label>ระยะทาง (กม.)</label>
                        <input type="number" id="distance" name="distance" class="form-control" readonly>
                    </div>

                    <div class="mb-3">
                        <label>ราคาน้ำมัน (บาท)</label>
                        <input type="number" id="cost_fuel" name="cost_fuel" class="form-control" placeholder="เช่น 32"
                            value="{{ old('cost_fuel', $fuel_record->cost_fuel ?? '') }}">
                    </div>


                    <div class="mb-3">
                        <label>ค่าน้ำมันรวม (บาท)</label>
                        <input type="number" name="cost_fuel_total" id="cost_fuel_total" class="form-control" readonly
                            value="{{ old('cost_fuel_total', $fuel_record->cost_fuel_total ?? '') }}">
                    </div>
                    <button class="btn btn-success">บันทึก</button>
                </div>

                <!-- แผนที่ -->
                <div class="col-md-6">
                    <div id="map" style="width:100%;height:500px;border-radius:10px;"></div>
                </div>
            </div>
        </form>
    </div>

    <!-- โหลดแผนที่จาก Leaflet -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

    <script>
        let map, routeLine;

        // สร้างแผนที่เริ่มต้น
        map = L.map('map').setView([13.7367, 100.5231], 6);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        async function getCoordinates(place) {
            const res = await fetch(
                `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(place)}`
            );
            const data = await res.json();
            return data.length ? [parseFloat(data[0].lat), parseFloat(data[0].lon)] : null;
        }

        async function calculateRoute() {
            const start = document.getElementById('start_point').value;
            const end = document.getElementById('destination').value;

            if (!start || !end) return;

            const startCoord = await getCoordinates(start);
            const endCoord = await getCoordinates(end);
            if (!startCoord || !endCoord) {
                alert("ไม่พบตำแหน่ง กรุณากรอกชื่อสถานที่ให้ชัดเจน");
                return;
            }

            const routeURL =
                `https://router.project-osrm.org/route/v1/driving/${startCoord[1]},${startCoord[0]};${endCoord[1]},${endCoord[0]}?overview=full&geometries=geojson`;
            const routeRes = await fetch(routeURL);
            const routeData = await routeRes.json();

            if (routeData.routes && routeData.routes[0]) {
                const distanceKm = routeData.routes[0].distance / 1000;
                document.getElementById('distance').value = distanceKm.toFixed(2);

                if (routeLine) map.removeLayer(routeLine);
                routeLine = L.geoJSON(routeData.routes[0].geometry, {
                    style: {
                        color: 'blue',
                        weight: 4
                    }
                }).addTo(map);

                map.fitBounds(routeLine.getBounds());
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            const startInput = document.getElementById('start_point');
            const endInput = document.getElementById('destination');

            startInput.addEventListener('change', calculateRoute);
            endInput.addEventListener('change', calculateRoute);

            // ✅ จุดสำคัญ: ถ้ามีค่าอยู่แล้ว ให้คำนวณทันที
            if (startInput.value && endInput.value) {
                calculateRoute();
            }
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const distanceInput = document.getElementById('distance');
            const priceInput = document.getElementById('cost_fuel');
            const totalInput = document.getElementById('cost_fuel_total');

            // อัตราสิ้นเปลืองจาก backend
            const fuelRate = {{ $t->fuel_rate ?? 0 }};

            const calculateFuelCost = () => {
                const distance = parseFloat(distanceInput.value);
                const price = parseFloat(priceInput.value);

                if (!distance || !price || fuelRate <= 0) {
                    totalInput.value = '';
                    return;
                }

                const total = (distance / fuelRate) * price;

                // ปัดทศนิยม 2 ตำแหน่ง
                totalInput.value = total.toFixed(2);
            };

            distanceInput.addEventListener('input', calculateFuelCost);
            priceInput.addEventListener('input', calculateFuelCost);
        });
    </script>
@endsection
