@extends('layout')

@section('namepage')
    <div class="container">
        <h3>จัดการรุ่นรถบรรทุก</h3>
    </div>
@endsection

@section('content')
    <div class="container py-3">
        @if (session('ok'))
            <div class="alert alert-success">{{ session('ok') }}</div>
        @endif

        <div class="d-flex justify-content-end mb-3">
            <a href="{{ route('truck_models.create') }}" class="btn btn-dark">เพิ่มรุ่นรถบรรทุก</a>
        </div>

        <div class="table-responsive shadow-sm rounded-3">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>ลำดับ</th>
                        <th>ยี่ห้อ</th>
                        <th>ชื่อรุ่น</th>
                        <th>จัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($models as $m)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $m->brand->name_brand ?? 'ไม่ระบุ' }}</td>
                            <td>{{ $m->name_model }}</td>
                            <td>
                                <a href="{{ route('truck_models.edit', $m->id) }}" class="btn btn-sm btn-primary">แก้ไข</a>
                                <form method="POST" action="{{ route('truck_models.destroy', $m->id) }}" class="d-inline" onsubmit="return confirm('ยืนยันลบข้อมูล?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-danger">ลบ</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted">— ไม่พบข้อมูล —</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            {{ $models->links() ?? '' }}
        </div>
    </div>
@endsection
