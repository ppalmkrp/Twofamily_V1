@extends('layout')

@section('namepage')
<div class="container">
    <h3>รายการใบเสนอราคา</h3>
</div>
@endsection

@section('content')
<div class="container py-3">

    @if(session('ok'))
        <div class="alert alert-success shadow-sm">
            {{ session('ok') }}
        </div>
    @endif

    <!-- ปุ่มสร้าง -->
    <div class="d-flex justify-content-end mb-3">
        <a href="{{ route('quotations.create') }}" class="btn btn-dark">
            + สร้างใบเสนอราคา
        </a>
    </div>

    <!-- ตาราง -->
    <div class="table-responsive shadow-sm rounded-3">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>เลขที่ใบเสนอราคา</th>
                    <th>ลูกค้า</th>
                    <th>วันที่ออก</th>
                    <th class="text-end">ยอดสุทธิ</th>
                    <th class="text-center">สถานะ</th>
                    <th class="text-center">จัดการ</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($quotations as $q)
                <tr>

                    <!-- เลขที่ -->
                    <td>
                        <strong>
                            QT{{ str_pad($q->id_quot, 5, '0', STR_PAD_LEFT) }}
                        </strong>
                    </td>

                    <!-- ลูกค้า -->
                    <td>
                        {{ $q->customer->name_customer ?? '-' }}
                    </td>

                    <!-- วันที่ -->
                    <td>
                        {{ \Carbon\Carbon::parse($q->date_quot)->format('d/m/Y') }}
                    </td>

                    <!-- ยอด -->
                    <td class="text-end">
                        {{ number_format($q->total_amount, 2) }}
                    </td>

                    <!-- 🔥 สถานะ -->
                    <td class="text-center">
                        @if($q->status == 'draft')
                            <span class="badge bg-secondary">ร่าง</span>

                        @elseif($q->status == 'approved')
                            <span class="badge bg-success">อนุมัติแล้ว</span>

                        @elseif($q->status == 'rejected')
                            <span class="badge bg-danger">ยกเลิก</span>

                        @else
                            <span class="badge bg-light text-dark">ไม่ทราบสถานะ</span>
                        @endif
                    </td>

                    <!-- ปุ่ม -->
                    <td class="text-center">
                        <div class="btn-group">

                            <!-- ดู -->
                            <a href="{{ route('quotations.show', $q) }}"
                               class="btn btn-sm btn-outline-primary">
                                ดู
                            </a>

                            <!-- ลบ -->
                            <form action="{{ route('quotations.destroy', $q) }}"
                                  method="POST"
                                  onsubmit="return confirm('ยืนยันลบใบเสนอราคานี้?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger">
                                    ลบ
                                </button>
                            </form>

                        </div>
                    </td>

                </tr>
                @empty
                <tr>
                    <td colspan="6"
                        class="text-center text-muted py-4">
                        — ยังไม่มีใบเสนอราคา —
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- pagination -->
    <div class="mt-3">
        {{ $quotations->links() }}
    </div>

</div>
@endsection