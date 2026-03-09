@extends('layout')

@section('namepage')
<div class="container">
    <h3>รายการใบแจ้งหนี้</h3>
</div>
@endsection

@section('content')
<div class="container py-3">

    @if(session('ok'))
        <div class="alert alert-success shadow-sm">
            {{ session('ok') }}
        </div>
    @endif

    <div class="d-flex justify-content-end mb-3">
        <a href="{{ route('invoices.create') }}" class="btn btn-dark">
            + สร้างใบแจ้งหนี้
        </a>
    </div>

    <div class="table-responsive shadow-sm rounded-3">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>เลขที่ใบแจ้งหนี้</th>
                    <th>ลูกค้า</th>
                    <th>วันที่ออก</th>
                    <th>สถานะ</th>
                    <th class="text-center">จัดการ</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($invoices as $i)
                <tr>
                    <td>
                        <strong>
                            IV{{ str_pad($i->id_iv, 5, '0', STR_PAD_LEFT) }}
                        </strong>
                    </td>

                    <td>
                        {{ $i->customer->name_customer ?? '-' }}
                    </td>

                    <td>
                        {{ \Carbon\Carbon::parse($i->date_iv)->format('d/m/Y') }}
                    </td>

                    <td>{{ $i->status }}</td>

                    <td class="text-center">
                        <div class="btn-group">
                            <a href="{{ route('invoices.show', $i) }}"
                               class="btn btn-sm btn-outline-primary">
                                ดู
                            </a>

                            <a href="{{ route('invoices.edit', $i) }}"
                               class="btn btn-sm btn-outline-secondary">
                                แก้ไข
                            </a>

                            <form action="{{ route('invoices.destroy', $i) }}"
                                  method="POST"
                                  onsubmit="return confirm('ยืนยันลบใบแจ้งหนี้นี้?')">
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
                    <td colspan="5" class="text-center text-muted py-4">
                        — ยังไม่มีใบแจ้งหนี้ —
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        {{ $invoices->links() }}
    </div>
</div>
@endsection