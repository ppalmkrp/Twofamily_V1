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

    <select name="province_truck"
        class="form-select form-select-lg @error('province_truck') is-invalid @enderror"
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
        <label class="form-label">ยี่ห้อ</label>

        <select name="brand_truck" class="form-select form-select-lg @error('brand_truck') is-invalid @enderror"
            required>

            <option value="">— เลือกยี่ห้อรถ —</option>

            <option value="HINO" {{ old('brand_truck', $truck->brand_truck ?? '') == 'HINO' ? 'selected' : '' }}>
                HINO
            </option>

            <option value="ISUZU" {{ old('brand_truck', $truck->brand_truck ?? '') == 'ISUZU' ? 'selected' : '' }}>
                ISUZU
            </option>
        </select>

        @error('brand_truck')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label class="form-label">รุ่น</label>
        <input type="text" name="model_truck" value="{{ old('model_truck', $truck->model_truck ?? '') }}"
            class="form-control form-control-lg @error('model_truck') is-invalid @enderror"
            placeholder="เช่น 500 Victor">
        @error('model_truck')
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
    });
</script>
