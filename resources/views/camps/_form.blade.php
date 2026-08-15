<h6 class="text-muted fw-semibold border-bottom pb-2 mb-3">ข้อมูลแคมป์</h6>

<div class="mb-3">
    <label class="form-label">ลูกค้า</label>
    <select name="id_customer" class="form-select @error('id_customer') is-invalid @enderror" required>
        <option value="">— เลือกลูกค้า —</option>
        @foreach ($customers as $c)
            <option value="{{ $c->id_customer }}" @selected(old('id_customer', $camp->id_customer ?? '') == $c->id_customer)>
                {{ $c->name_customer }}
            </option>
        @endforeach
    </select>
    @error('id_customer')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label class="form-label">ชื่อแคมป์</label>
    <input type="text" name="name_camp" value="{{ old('name_camp', $camp->name_camp ?? '') }}"
        class="form-control @error('name_camp') is-invalid @enderror" placeholder="เช่น แคมป์บ้านไผ่ เฟส 2" required>
    @error('name_camp')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

{{-- <div class="mb-3">
    <label class="form-label">รหัสแคมป์</label>
    <input type="text" class="form-control bg-light" value="{{ $camp->code_camp ?? 'ระบบจะสร้างให้เมื่อบันทึก' }}"
        readonly>
    <div class="form-text text-muted">
        @if ($camp->code_camp ?? false)
            รหัสถูกใช้อ้างอิงแล้ว จึงแก้ไขไม่ได้
        @else
            รูปแบบ CP-{{ now()->year + 543 }}-0001
        @endif
    </div>
</div> --}}

<div class="mb-3">
    <label class="form-label">สถานะ</label>
    @php($val = old('status_camp', $camp->status_camp ?? 'active'))
    <select name="status_camp" class="form-select @error('status_camp') is-invalid @enderror" required>
        @foreach (\App\Models\Camp::STATUS_LABELS as $value => $label)
            <option value="{{ $value }}" @selected($val === $value)>{{ $label }}</option>
        @endforeach
    </select>
    @error('status_camp')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label class="form-label">บ้านเลขที่ / หมู่</label>
    <input type="text" name="address_detail" value="{{ old('address_detail', $camp->address_detail ?? '') }}"
        class="form-control" placeholder="เช่น 123/45 หมู่ 6">
</div>

<div class="row g-3 mt-2">

    <div class="col-md-3">
        <label>จังหวัด</label>
        <select id="province" name="province" class="form-select">
            <option value="">— เลือกจังหวัด —</option>
        </select>
    </div>

    <div class="col-md-3">
        <label>อำเภอ</label>
        <select id="district" name="district" class="form-select" disabled>
            <option value="">— เลือกอำเภอ —</option>
        </select>
    </div>

    <div class="col-md-3">
        <label>ตำบล</label>
        <select id="subdistrict" name="subdistrict" class="form-select" disabled>
            <option value="">— เลือกตำบล —</option>
        </select>
    </div>

    <div class="col-md-3">
        <label>รหัสไปรษณีย์</label>
        <input type="text" id="zipcode" name="zipcode" value="{{ old('zipcode', $camp->zipcode ?? '') }}"
            class="form-control bg-light @error('zipcode') is-invalid @enderror" readonly>
        @error('zipcode')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

</div>

<div class="row g-3 mt-2">

    <div class="col-md-6">
        <label>ละติจูด</label>
        <input type="number" step="0.0000001" id="latitude" name="latitude"
            value="{{ old('latitude', $camp->latitude ?? '') }}"
            class="form-control bg-light @error('latitude') is-invalid @enderror" readonly>
        @error('latitude')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label>ลองจิจูด</label>
        <input type="number" step="0.0000001" id="longitude" name="longitude"
            value="{{ old('longitude', $camp->longitude ?? '') }}"
            class="form-control bg-light @error('longitude') is-invalid @enderror" readonly>
        @error('longitude')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-12">
        <label>ตำแหน่งแคมป์บนแผนที่</label>

        <div class="d-flex gap-2 mb-2">
            <button type="button" class="btn btn-outline-dark btn-sm" id="searchFromAddress">
                ค้นหาจากที่อยู่ที่กรอก
            </button>
            <button type="button" class="btn btn-outline-secondary btn-sm" id="clearPin">
                ล้างหมุด
            </button>
        </div>

        <div id="map" style="width:100%;height:400px;border-radius:10px;"></div>

        <div class="form-text text-muted">
            คลิกบนแผนที่เพื่อปักหมุด หรือลากหมุดเพื่อปรับตำแหน่ง
        </div>
    </div>

</div>

<div class="row g-3 mt-2 mb-4">

    <div class="col-md-4">
        <label>ชื่อผู้ติดต่อหน้างาน</label>
        <input type="text" name="contact_name" value="{{ old('contact_name', $camp->contact_name ?? '') }}"
            class="form-control">
    </div>

    <div class="col-md-4">
        <label>เบอร์โทร (10 หลัก)</label>
        <input type="text" name="contact_phone" value="{{ old('contact_phone', $camp->contact_phone ?? '') }}"
            class="form-control @error('contact_phone') is-invalid @enderror" maxlength="10" pattern="[0-9]{10}"
            inputmode="numeric" placeholder="เช่น 0812345678">
        @error('contact_phone')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-4">
        <label>หมายเหตุ</label>
        <input type="text" name="note" value="{{ old('note', $camp->note ?? '') }}" class="form-control">
    </div>

</div>

<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {


        const apiURL =
            'https://raw.githubusercontent.com/kongvut/thai-province-data/master/api/latest/province_with_district_and_sub_district.json';

        let thaiData = [];

        const province = document.getElementById('province');
        const district = document.getElementById('district');
        const subdistrict = document.getElementById('subdistrict');
        const zipcode = document.getElementById('zipcode');

        const oldProvince = @json(old('province', $camp->province ?? ''));
        const oldDistrict = @json(old('district', $camp->district ?? ''));
        const oldSubdistrict = @json(old('subdistrict', $camp->subdistrict ?? ''));

        fetch(apiURL)
            .then(res => res.json())
            .then(data => {
                thaiData = data;

                data.forEach(p => {
                    let option = new Option(p.name_th, p.name_th);
                    if (p.name_th === oldProvince) option.selected = true;
                    province.add(option);
                });

                if (oldProvince) {
                    province.dispatchEvent(new Event('change'));

                    if (oldDistrict) {
                        district.value = oldDistrict;
                        district.dispatchEvent(new Event('change'));

                        if (oldSubdistrict) {
                            subdistrict.value = oldSubdistrict;
                            subdistrict.dispatchEvent(new Event('change'));
                        }
                    }
                }
            })
            .catch(err => {
                console.error("โหลดข้อมูลที่อยู่ไม่ได้", err);
                province.innerHTML = '<option value="">โหลดข้อมูลจังหวัดไม่สำเร็จ</option>';
            });

        province.addEventListener('change', function() {

            district.innerHTML = '<option value="">— เลือกอำเภอ —</option>';
            subdistrict.innerHTML = '<option value="">— เลือกตำบล —</option>';
            zipcode.value = '';

            district.disabled = true;
            subdistrict.disabled = true;

            const p = thaiData.find(x => x.name_th === this.value);
            if (!p) return;

            const districts = p.amphure || p.districts || p.district || [];

            districts.forEach(d => {
                district.add(new Option(d.name_th, d.name_th));
            });

            district.disabled = false;
        });

        district.addEventListener('change', function() {

            subdistrict.innerHTML = '<option value="">— เลือกตำบล —</option>';
            zipcode.value = '';

            subdistrict.disabled = true;

            const p = thaiData.find(x => x.name_th === province.value);
            if (!p) return;

            const districts = p.amphure || p.districts || p.district || [];
            const d = districts.find(x => x.name_th === this.value);
            if (!d) return;

            const subs = d.tambon || d.sub_districts || d.subdistricts || [];

            subs.forEach(s => {
                let option = new Option(s.name_th, s.name_th);
                option.dataset.zip = s.zip_code;
                subdistrict.add(option);
            });

            subdistrict.disabled = false;
        });

        subdistrict.addEventListener('change', function() {
            const selected = this.selectedOptions[0];
            zipcode.value = selected?.dataset?.zip || '';
        });


        const latInput = document.getElementById('latitude');
        const lngInput = document.getElementById('longitude');

        const hasPin = latInput.value !== '' && lngInput.value !== '';
        const startLat = parseFloat(latInput.value) || 16.4419;
        const startLng = parseFloat(lngInput.value) || 102.8360;

        const campMap = L.map('map').setView([startLat, startLng], hasPin ? 15 : 11);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(campMap);

        let campMarker = null;

        function setPin(lat, lng, zoom) {
            if (campMarker) {
                campMarker.setLatLng([lat, lng]);
            } else {
                campMarker = L.marker([lat, lng], {
                    draggable: true
                }).addTo(campMap).bindPopup("ตำแหน่งแคมป์");

                campMarker.on('dragend', function() {
                    const pos = campMarker.getLatLng();
                    latInput.value = pos.lat.toFixed(7);
                    lngInput.value = pos.lng.toFixed(7);
                });
            }

            latInput.value = lat.toFixed(7);
            lngInput.value = lng.toFixed(7);

            if (zoom) campMap.setView([lat, lng], zoom);
        }

        if (hasPin) setPin(startLat, startLng);

        campMap.on('click', function(e) {
            setPin(e.latlng.lat, e.latlng.lng);
        });

        document.getElementById('clearPin').addEventListener('click', function() {
            if (campMarker) {
                campMap.removeLayer(campMarker);
                campMarker = null;
            }
            latInput.value = '';
            lngInput.value = '';
        });

        document.getElementById('searchFromAddress').addEventListener('click', async function() {
            const parts = [
                subdistrict.value,
                district.value,
                province.value,
                'ประเทศไทย'
            ].filter(Boolean);

            if (parts.length <= 1) {
                alert('กรุณาเลือกจังหวัดและอำเภอก่อน');
                return;
            }

            this.disabled = true;
            this.textContent = 'กำลังค้นหา...';

            try {
                const query = encodeURIComponent(parts.join(' '));
                const res = await fetch(
                    `https://nominatim.openstreetmap.org/search?format=json&q=${query}&limit=1`
                );
                const data = await res.json();

                if (!data.length) {
                    alert('ไม่พบตำแหน่งจากที่อยู่นี้ กรุณาคลิกปักหมุดบนแผนที่เอง');
                } else {
                    setPin(parseFloat(data[0].lat), parseFloat(data[0].lon), 14);
                }
            } catch (err) {
                alert('ค้นหาตำแหน่งไม่สำเร็จ กรุณาปักหมุดเอง');
            }

            this.disabled = false;
            this.textContent = 'ค้นหาจากที่อยู่ที่กรอก';
        });

        setTimeout(function() {
            campMap.invalidateSize();
        }, 200);

    });
</script>