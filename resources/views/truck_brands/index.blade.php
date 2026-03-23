@extends('layout')

@section('namepage')
    <div class="container">
        <h3>จัดการยี่ห้อรถบรรทุก</h3>
    </div>
@endsection

@section('content')
    <div class="container py-3">
        @if (session('ok'))
            <div class="alert alert-success">{{ session('ok') }}</div>
        @endif

        <div class="d-flex justify-content-end mb-3">
            <a href="{{ route('truck_brands.create') }}" class="btn btn-dark">เพิ่มยี่ห้อรถบรรทุก</a>
        </div>

        <div class="table-responsive shadow-sm rounded-3">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>ลำดับ</th>
                        <th>ชื่อยี่ห้อ</th>
                        <th>จัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($brands as $b)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $b->name_brand }}</td>
                            <td>
                                <a href="{{ route('truck_brands.edit', $b->id) }}" class="btn btn-sm btn-primary">แก้ไข</a>
                                <form method="POST" action="{{ route('truck_brands.destroy', $b->id) }}" class="d-inline" onsubmit="return confirm('ยืนยันลบข้อมูล?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-danger">ลบ</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted">— ไม่พบข้อมูล —</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            {{ $brands->links() ?? '' }}
        </div>
    </div>
@endsection
