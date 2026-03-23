@php($mode = $mode ?? 'create')
@php($ymax = now()->year)

<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label">เลขทะเบียน</label>
        <input type="text" name="id_truck" id="id_truck" value="{{ old('id_truck', $truck->id_truck ?? '') }}"
            class="form-control form-control-lg @error('id_truck') is-invalid @enderror" placeholder="เช่น 70-1234"
            required pattern="[0-9\s\-]+" title="ใช้ได้เฉพาะ ตัวเลข และเครื่องหมายขีดกลาง (-)">
        <div class="invalid-feedback">
            รูปแบบทะเบียนไม่ถูกต้อง (70-1234)
        </div>
        @error('id_truck')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label class="form-label">จังหวัด</label>
        <select name="province_truck" class="form-select form-select-lg @error('province_truck') is-invalid @enderror"
            required>
            <option value="">— เลือกจังหวัด —</option>
            @foreach ($provinces as $province)
                <option value="{{ $province }}"
                    {{ old('province_truck', $truck->province_truck ?? '') == $province ? 'selected' : '' }}>
                    {{ $province }}
                </option>
            @endforeach
        </select>
        @error('province_truck')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label class="form-label">ยี่ห้อ <span class="text-danger">*</span></label>
        <select name="truck_brand_id" id="truck_brand_select"
            class="form-select form-select-lg @error('truck_brand_id') is-invalid @enderror" required>
            <option value="">— เลือกยี่ห้อรถ —</option>
            @if (isset($brands))
                @foreach ($brands as $brand)
                    <option value="{{ $brand->id }}" @selected(old('truck_brand_id', $truck->truck_brand_id ?? '') == $brand->id)>
                        {{ $brand->name_brand }}
                    </option>
                @endforeach
            @endif
        </select>
        @error('truck_brand_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label class="form-label">รุ่น <span class="text-danger">*</span></label>
        <select name="truck_model_id" id="truck_model_select"
            class="form-select form-select-lg @error('truck_model_id') is-invalid @enderror" required disabled>
            <option value="">— เลือกรุ่นรถ —</option>
        </select>
        @error('truck_model_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label class="form-label">ปีที่ซื้อ</label>
        <select name="year_truck" class="form-select form-select-lg @error('year_truck') is-invalid @enderror">
            <option value="">— เลือกปี —</option>
            @for ($y = $ymax; $y >= 1980; $y--)
                <option value="{{ $y }}" @selected(old('year_truck', $truck->year_truck ?? '') == $y)>{{ $y }}</option>
            @endfor
        </select>
        @error('year_truck')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label class="form-label">น้ำหนักรถ (กก.)</label>
        <input type="number" min="0" name="weight_truck"
            value="{{ old('weight_truck', $truck->weight_truck ?? '') }}"
            class="form-control form-control-lg @error('weight_truck') is-invalid @enderror" placeholder="เช่น 12000">
        @error('weight_truck')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label class="form-label">ความจุถังน้ำมัน (ลิตร)</label>
        <input type="number" min="0" name="fuelfactory_truck"
            value="{{ old('fuelfactory_truck', $truck->fuelfactory_truck ?? '') }}"
            class="form-control form-control-lg @error('fuelfactory_truck') is-invalid @enderror"
            placeholder="เช่น 300">
        @error('fuelfactory_truck')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label class="form-label">อัตราการสิ้นเปลืองน้ำมัน (กม./ลิตร)</label>
        <input type="number" name="fuel_rate" value="{{ old('fuel_rate', $truck->fuel_rate ?? '') }}"
            class="form-control form-control-lg @error('fuel_rate') is-invalid @enderror" placeholder="เช่น 5.5"
            step="0.01" min="0" required>
        @error('fuel_rate')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label class="form-label">สถานะ</label>
        <select name="status_truck" class="form-select form-select-lg @error('status_truck') is-invalid @enderror"
            required>
            @php($val = old('status_truck', $truck->status_truck ?? 'active'))
            <option value="active" @selected($val === 'active')>พร้อมใช้งาน</option>
            <option value="maintenance" @selected($val === 'maintenance')>ซ่อมบำรุง</option>
            <option value="retired" @selected($val === 'retired')>ปลดประจำการ</option>
        </select>
        @error('status_truck')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        // --- 1. ระบบจัดการทะเบียนรถ ---
        const input = document.getElementById('id_truck');

        // ล้างค่าให้เหลือเฉพาะ ก-ฮ และ 0-9
        const clean = (v) => v.replace(/[^ก-ฮ0-9]/gu, '');

        const format = (v) => {
            if (v.length <= 2) return v;
            return v.slice(0, 2) + '-' + v.slice(2);
        };

        input.addEventListener('input', () => {
            let raw = clean(input.value);

            // แยกหน้า / หลัง
            let front = raw.slice(0, 2);
            let back = raw.slice(2);

            //  บังคับ 4 ตัวหลังเป็นตัวเลขเท่านั้น
            back = back.replace(/\D/g, '');

            // จำกัดจำนวน
            front = front.slice(0, 2);
            back = back.slice(0, 4);

            input.value = format(front + back);
        });

        // --- 2. ระบบ Dependent Dropdown (ยี่ห้อ -> รุ่น) ---
        @if (isset($brands))
            const brandsData = @json($brands);
            const brandSelect = document.getElementById('truck_brand_select');
            const modelSelect = document.getElementById('truck_model_select');

            // ดึงค่าเก่าออกมา (ใช้ตอน validation ไม่ผ่าน หรือตอนกดแก้ไข)
            const oldModelId = "{{ old('truck_model_id', $truck->truck_model_id ?? '') }}";

            function updateModels() {
                const selectedBrandId = brandSelect.value;

                // ล้างข้อมูลรุ่นเก่า
                modelSelect.innerHTML = '<option value="">— เลือกรุ่นรถ —</option>';

                //  ถ้าไม่ได้เลือกยี่ห้อ (หรือกดกลับไปที่ "เลือกยี่ห้อรถ") ให้ล็อคช่องรุ่นไว้เหมือนเดิม
                if (!selectedBrandId) {
                    modelSelect.disabled = true;
                    return;
                }

                //  ถ้ามีการเลือกยี่ห้อแล้ว ให้ปลดล็อคช่องรุ่น
                modelSelect.disabled = false;

                // หายี่ห้อที่ตรงกับที่เลือก
                const selectedBrand = brandsData.find(b => b.id == selectedBrandId);

                // ถ้ายี่ห้อนี้มีรุ่น ให้สร้างตัวเลือก
                if (selectedBrand && selectedBrand.models) {
                    selectedBrand.models.forEach(model => {
                        const option = document.createElement('option');
                        option.value = model.id;
                        option.textContent = model.name_model;

                        // ถ้ารุ่นตรงกับค่าเก่าที่เคยเลือกไว้ ให้ selected ทันที
                        if (model.id == oldModelId) {
                            option.selected = true;
                        }

                        modelSelect.appendChild(option);
                    });
                }
            }

            // สั่งทำงานเมื่อมีการเปลี่ยนยี่ห้อ
            brandSelect.addEventListener('change', updateModels);

            // สั่งทำงาน 1 ครั้งตอนเปิดหน้าเว็บ (เพื่อโหลดข้อมูลเก่ามาแสดงทันที)
            if (brandSelect.value) {
                updateModels();
            }
        @endif
    });
</script>
