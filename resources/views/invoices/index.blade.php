@extends('layout')

@section('namepage')
<div class="container">
    <h3>รายการใบแจ้งหนี้</h3>
</div>
@endsection

@section('content')
<div class="container py-3">

    @if(session('success'))
        <div class="alert alert-success shadow-sm">
            {{ session('success') }}
        </div>
    @endif

    <div class="table-responsive shadow-sm rounded-3">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>เลขที่ใบแจ้งหนี้</th>
                    <th>ลูกค้า</th>
                    <th>อ้างอิงใบเสนอราคา</th>
                    <th class="text-end">ยอดรวม</th>
                    <th class="text-center">สถานะ</th>
                    <th class="text-center">จัดการ</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($invoices as $inv)
                <tr>

                    <td>
                        <strong>
                            INV{{ str_pad($inv->id_invoice, 5, '0', STR_PAD_LEFT) }}
                        </strong>
                    </td>

                    <td>
                        {{ $inv->customer->name_customer ?? '-' }}
                    </td>

                    <td>
                        QT{{ str_pad($inv->id_quotation, 5, '0', STR_PAD_LEFT) }}
                    </td>

                    <td class="text-end">
                        {{ number_format($inv->total, 2) }}
                    </td>

                    <td class="text-center">
                        @if($inv->status == 'paid')
                            <span class="badge bg-success">ชำระแล้ว</span>
                        @else
                            <span class="badge bg-warning text-dark">ยังไม่ชำระ</span>
                        @endif
                    </td>

                    <td class="text-center">
                        <div class="btn-group">

                            <!-- ดู -->
                            <a href="{{ route('invoices.show', $inv->id_invoice) }}"
                               class="btn btn-sm btn-outline-primary">
                                ดู
                            </a>

                            <!-- ลบ -->
                            <form action="{{ route('invoices.destroy', $inv->id_invoice) }}"
                                  method="POST"
                                  onsubmit="return confirm('ยืนยันลบใบแจ้งหนี้?')">
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
                    <td colspan="6" class="text-center text-muted py-4">
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