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
                        <select name="trucks_id_truck" id="truck_select" class="form-select">
                            @foreach ($trucks as $t)
                                <option value="{{ $t->id_truck }}" data-fuel-rate="{{ $t->fuel_rate }}"
                                    @selected(old('trucks_id_truck', $fuel_record->trucks_id_truck ?? '') == $t->id_truck)>
                                    {{ $t->brand_truck }} ({{ $t->id_truck }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label>จุดเริ่มต้น <span class="text-danger">*</span> <small
                                class="text-muted">(ใช้คำนวณแผนที่)</small></label>
                        <input type="text" id="start_point" name="start_point" class="form-control"
                            placeholder="เช่น อำเภอเมือง ขอนแก่น หรือคลิกบนแผนที่"
                            value="{{ old('start_point', $fuel_record->start_point ?? '') }}">
                    </div>

                    <div class="mb-3">
                        <label>รายละเอียดต้นทาง <small class="text-muted">(บ้านเลขที่ / ซอย / จุดสังเกต)</small></label>
                        <textarea name="start_detail" class="form-control" rows="2" placeholder="เช่น 123/4 ซอยหน้าป้าย โรงงาน ABC">{{ old('start_detail', $fuel_record->start_detail ?? '') }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label>ปลายทาง <span class="text-danger">*</span> <small
                                class="text-muted">(ใช้คำนวณแผนที่)</small></label>
                        <input type="text" id="destination" name="destination" class="form-control"
                            placeholder="เช่น จังหวัดเลย หรือคลิกบนแผนที่"
                            value="{{ old('destination', $fuel_record->destination ?? '') }}">
                    </div>

                    <div class="mb-3">
                        <label>รายละเอียดปลายทาง <small class="text-muted">(บ้านเลขที่ / ซอย / จุดสังเกต)</small></label>
                        <textarea name="destination_detail" class="form-control" rows="2" placeholder="เช่น โกดังสีแดง ตรงข้ามตลาด">{{ old('destination_detail', $fuel_record->destination_detail ?? '') }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label>ระยะทาง (กม.)</label>
                        <input type="number" id="distance" name="distance" class="form-control" readonly>
                    </div>

                    <div class="mb-3">
                        <label>ราคาน้ำมัน (บาท)</label>
                        <input type="number" id="cost_fuel" name="cost_fuel" class="form-control bg-light"
                            placeholder="เช่น 32" readonly
                            value="{{ old('cost_fuel', $fuel_record->cost_fuel ?? ($dieselPrice ?? '')) }}">
                    </div>

                    <div class="mb-3">
                        <label>อัตราสิ้นเปลือง (กม./ลิตร)</label>
                        <input type="number" id="show_fuel_rate" class="form-control bg-light" readonly
                            placeholder="เลือกตัวรถเพื่อแสดงค่า">
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
    {{-- <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
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


                // เพิ่มบรรทัดนี้ลงไป: พอแผนที่หาระยะทางเสร็จ ให้สั่งฟังก์ชันคำนวณเงินทันที
                if (window.calculateFuelCost) window.calculateFuelCost();


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

            //  จุดสำคัญ: ถ้ามีค่าอยู่แล้ว ให้คำนวณทันที
            if (startInput.value && endInput.value) {
                calculateRoute();
            }
        });
    </script> --}}


    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

    <script>
        let map, routeLine, startMarker, endMarker;
        let startCoord = null;
        let endCoord = null;

        // สร้างแผนที่เริ่มต้น (ซูมเข้าไทย)
        map = L.map('map').setView([13.7367, 100.5231], 6);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        // 1. ระบบคลิกบนแผนที่เพื่อปักหมุด (Click to Set Markers)
        map.on('click', function(e) {
            // ถ้ายังไม่มีจุดเริ่มต้น หรือ มีครบ 2 จุดแล้วเริ่มใหม่
            if (!startCoord || (startCoord && endCoord)) {
                startCoord = [e.latlng.lat, e.latlng.lng];
                endCoord = null;

                // นำพิกัดไปใส่ในช่องกรอกข้อมูล
                document.getElementById('start_point').value = startCoord[0].toFixed(6) + ',' + startCoord[1]
                    .toFixed(6);
                document.getElementById('destination').value = ''; // เคลียร์ปลายทางรอการคลิกครั้งที่ 2

                // ล้างหมุดเส้นทางเก่าออก
                if (startMarker) map.removeLayer(startMarker);
                if (endMarker) map.removeLayer(endMarker);
                if (routeLine) map.removeLayer(routeLine);

                startMarker = L.marker(startCoord).addTo(map).bindPopup("จุดเริ่มต้น").openPopup();
            } else {
                // ถ้ามีจุดเริ่มต้นแล้ว ให้คลิกครั้งนี้เป็นจุดปลายทาง
                endCoord = [e.latlng.lat, e.latlng.lng];
                document.getElementById('destination').value = endCoord[0].toFixed(6) + ',' + endCoord[1].toFixed(
                    6);
                endMarker = L.marker(endCoord).addTo(map).bindPopup("ปลายทาง").openPopup();

                // สั่งคำนวณเส้นทางทันที
                drawRoute();
            }
        });

        // 2. ฟังก์ชันแปลชื่อสถานที่ -> เป็นพิกัด
        async function getCoordinates(place) {
            // เช็คก่อนว่าผู้ใช้ใส่เป็นพิกัด (Lat,Lng) จากการคลิกแผนที่มาหรือไม่
            if (place.includes(',')) {
                let parts = place.split(',');
                if (!isNaN(parts[0]) && !isNaN(parts[1])) {
                    return [parseFloat(parts[0]), parseFloat(parts[1])];
                }
            }

            // ถ้าพิมพ์เป็นตัวหนังสือ ให้ต่อท้ายด้วย "ประเทศไทย" เพื่อให้หาแม่นยำขึ้น
            const query = encodeURIComponent(place + ' ประเทศไทย');
            const res = await fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${query}&limit=1`);
            const data = await res.json();
            return data.length ? [parseFloat(data[0].lat), parseFloat(data[0].lon)] : null;
        }

        // 3. ฟังก์ชันรับค่าจากช่อง Input
        async function calculateRoute() {
            const start = document.getElementById('start_point').value;
            const end = document.getElementById('destination').value;

            if (!start || !end) return;

            startCoord = await getCoordinates(start);
            endCoord = await getCoordinates(end);

            if (!startCoord || !endCoord) {
                alert("ไม่พบตำแหน่งจากชื่อที่พิมพ์ แนะนำให้ระบุ ตำบล/อำเภอ หรือใช้เมาส์คลิกปักหมุดบนแผนที่โดยตรงครับ");
                return;
            }

            drawRoute();
        }

        // 4. ฟังก์ชันวาดเส้นทางและคำนวณระยะทาง
        async function drawRoute() {
            if (!startCoord || !endCoord) return;

            // อัปเดตหมุดบนแผนที่
            if (startMarker) map.removeLayer(startMarker);
            if (endMarker) map.removeLayer(endMarker);
            startMarker = L.marker(startCoord).addTo(map).bindPopup("จุดเริ่มต้น").openPopup();
            endMarker = L.marker(endCoord).addTo(map).bindPopup("ปลายทาง").openPopup();

            // ดึงข้อมูลเส้นทางจาก OSRM
            const routeURL =
                `https://router.project-osrm.org/route/v1/driving/${startCoord[1]},${startCoord[0]};${endCoord[1]},${endCoord[0]}?overview=full&geometries=geojson`;
            const routeRes = await fetch(routeURL);
            const routeData = await routeRes.json();

            if (routeData.routes && routeData.routes[0]) {
                const distanceKm = routeData.routes[0].distance / 1000;
                document.getElementById('distance').value = distanceKm.toFixed(2);

                // สั่งฟังก์ชันคำนวณเงินทำงานทันที
                if (window.calculateFuelCost) window.calculateFuelCost();

                // วาดเส้นสีน้ำเงิน
                if (routeLine) map.removeLayer(routeLine);
                routeLine = L.geoJSON(routeData.routes[0].geometry, {
                    style: {
                        color: '#0d6efd',
                        weight: 5
                    }
                }).addTo(map);

                // ซูมแผนที่ให้พอดีกับเส้นทาง
                map.fitBounds(routeLine.getBounds());
            }
        }

        // 5. ดักจับการพิมพ์ในช่อง Input
        document.addEventListener('DOMContentLoaded', () => {
            const startInput = document.getElementById('start_point');
            const endInput = document.getElementById('destination');

            startInput.addEventListener('change', calculateRoute);
            endInput.addEventListener('change', calculateRoute);

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
            const truckSelect = document.getElementById('truck_select');

            //  ดึง Element ช่องแสดงอัตราสิ้นเปลืองมาใช้งาน
            const showFuelRateInput = document.getElementById('show_fuel_rate');

            window.calculateFuelCost = () => {
                const distance = parseFloat(distanceInput.value);
                const price = parseFloat(priceInput.value);

                // ดึงอัตราสิ้นเปลืองจากรถบรรทุกที่กำลังเลือกใน Dropdown
                const selectedOption = truckSelect.options[truckSelect.selectedIndex];
                const fuelRate = parseFloat(selectedOption.getAttribute('data-fuel-rate'));

                //  โชว์ตัวเลขอัตราสิ้นเปลืองให้ผู้ใช้เห็นบนหน้าเว็บ
                if (!isNaN(fuelRate) && fuelRate > 0) {
                    showFuelRateInput.value = fuelRate;
                } else {
                    showFuelRateInput.value = '';
                }

                // เช็คว่ามีข้อมูลครบไหม ถ้าไม่ครบให้หยุดทำงานแค่นี้ (ปล่อยช่องรวมว่างไว้)
                if (!distance || !price || isNaN(fuelRate) || fuelRate <= 0) {
                    totalInput.value = '';
                    return;
                }

                // สูตร: (ระยะทาง / อัตราสิ้นเปลือง) * ราคาน้ำมัน
                const total = (distance / fuelRate) * price;

                // ปัดทศนิยม 2 ตำแหน่งและใส่ในช่องรวม
                totalInput.value = total.toFixed(2);
            };

            // ดักจับการเปลี่ยนแปลง
            distanceInput.addEventListener('input', calculateFuelCost);
            priceInput.addEventListener('input', calculateFuelCost);
            truckSelect.addEventListener('change', calculateFuelCost);

            //  เรียกใช้งาน 1 ครั้งตอนโหลดหน้าเว็บ เพื่อให้อัตราสิ้นเปลืองของรถคันแรก (ค่า Default) โชว์ขึ้นมาทันที
            calculateFuelCost();
        });
    </script>
@endsection
