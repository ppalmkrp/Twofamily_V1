@extends('layout')

@section('namepage')
    <div class="container">
        <h3>รถบรรทุกทั้งหมด</h3>
    </div>
@endsection

@section('content')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        .table-card {
            border: 1px solid rgba(0, 0, 0, .08);
            box-shadow: 0 8px 30px rgba(0, 0, 0, .06);
            border-radius: 14px;
            overflow: hidden;
        }

        .table thead th {
            background: #f7f7f9;
        }

        .action-col {
            width: 160px;
        }
    </style>

    <div class="container py-3">
        @if (session('ok'))
            <div class="alert alert-success shadow-sm">{{ session('ok') }}</div>
        @endif

        <div class="d-flex flex-wrap gap-2 justify-content-between align-items-center mb-3">
            <form method="GET" class="d-flex gap-2">
                <input type="text" name="q" value="{{ $q }}" class="form-control"
                    placeholder="ค้นหา: ทะเบียน/ยี่ห้อ/รุ่น" />
                <select name="status" class="form-select">
                    <option value="">ทุกสถานะ</option>
                    <option value="active" @selected($status === 'active')>พร้อมใช้งาน</option>
                    <option value="maintenance" @selected($status === 'maintenance')>ซ่อมบำรุง</option>
                    <option value="retired" @selected($status === 'retired')>ปลดประจำการ</option>
                </select>
                <button class="btn btn-outline-secondary" type="submit">ค้นหา</button>
            </form>

            <a href="{{ route('trucks.create') }}" class="btn btn-dark">
                เพิ่มรถบรรทุก
            </a>
        </div>

        <div class="table-card">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead>
                        <tr>
                            <th>เลขทะเบียน</th>
                            <th>ยี่ห้อ</th>
                            <th>รุ่น</th>
                            <th>ปีที่ซื้อ</th>
                            <th>จังหวัด</th>
                            <th>สถานะ</th>
                            <th class="action-col text-center">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($trucks as $t)
                            <tr>
                                <td class="fw-semibold">{{ $t->id_truck }}</td>
                                <td>{{ $t->brand->name_brand ?? '-' }}</td>
                                <td>{{ $t->model->name_model ?? '-' }}</td>
                                <td>{{ $t->year_truck }}</td>
                                <td>{{ $t->province_truck }}</td>
                                <td>
                                    @php
                                        // ตั้งค่าสี
                                        $badge = [
                                            'active' => 'success',
                                            'maintenance' => 'warning',
                                            'retired' => 'secondary',
                                        ][$t->status_truck] ?? 'secondary';

                                        // แปลเป็นภาษาไทยให้โชว์บนหน้าเว็บสวยๆ
                                        $statusText = [
                                            'active' => 'พร้อมใช้งาน',
                                            'maintenance' => 'ซ่อมบำรุง',
                                            'retired' => 'ปลดประจำการ',
                                        ][$t->status_truck] ?? 'ไม่ระบุ';
                                    @endphp
                                    <span class="badge bg-{{ $badge }}">{{ $statusText }}</span>
                                </td>
                                <td class="text-center">
                                    <div class="d-inline-flex gap-2">
                                        <a href="{{ route('trucks.edit', $t->id_truck) }}"
                                            class="btn btn-sm btn-outline-primary">
                                            แก้ไข
                                        </a>
                                        <form method="POST" action="{{ route('trucks.destroy', $t->id_truck) }}"
                                            onsubmit="return confirm('ยืนยันลบรถทะเบียน {{ $t->id_truck }} ?')">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger" type="submit">ลบ</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">ไม่พบข้อมูล</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-3">
                {{ $trucks->withQueryString()->links() }}
            </div>
        </div>
    </div>
@endsection
