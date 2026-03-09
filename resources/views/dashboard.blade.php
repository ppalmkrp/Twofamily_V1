{{-- <x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <x-welcome />
            </div>
        </div>
    </div>
</x-app-layout> --}}


@extends('layout')

@section('namepage')
    <div class="container">
        <h3 class="mb-0">หน้าหลัก</h3>
    </div>
@endsection

@section('content')
    <style>
        /* ===============================
           DASHBOARD THEME (MATCH LAYOUT)
        =============================== */
        .dashboard-page {
            --card-border: rgba(0, 0, 0, .06);
            --card-shadow-lg: 0 10px 28px rgba(0, 0, 0, .06);
            --card-shadow-md: 0 8px 24px rgba(0, 0, 0, .05);
            --card-radius: 18px;
            --primary: #223654;
        }

        /* ===============================
           CARDS
        =============================== */
        .dashboard-page .kpi-card,
        .dashboard-page .section-card {
            background-color: #ffffff;
            border: 1px solid var(--card-border);
            border-radius: var(--card-radius);
        }

        .dashboard-page .kpi-card {
            box-shadow: var(--card-shadow-lg);
        }

        .dashboard-page .section-card {
            box-shadow: var(--card-shadow-md);
        }

        /* ===============================
           KPI
        =============================== */
        .dashboard-page .kpi-number {
            font-size: 2.1rem;
            font-weight: 700;
            line-height: 1.1;
            color: var(--primary);
        }

        /* ===============================
           TABLE
        =============================== */
        .dashboard-page .table thead th {
            font-weight: 600;
            color: var(--primary);
        }

        .dashboard-page .table-hover tbody tr:hover {
            background-color: rgba(34, 54, 84, .04);
        }

        /* ===============================
           BUTTONS
        =============================== */
        .dashboard-page .btn-dark {
            background-color: var(--primary);
            border-color: var(--primary);
        }

        .dashboard-page .btn-dark:hover {
            background-color: #1b2a45;
            border-color: #1b2a45;
        }

        .dashboard-page .btn-outline-secondary {
            border-color: #cbd5e1;
            color: #334155;
        }

        .dashboard-page .btn-outline-secondary:hover {
            background-color: #e2e8f0;
            color: #1e293b;
        }

        /* ===============================
           BADGE
        =============================== */
        .badge-soft {
            background: rgba(0, 0, 0, .05);
            color: #333;
        }
    </style>

    <div class="container py-3 dashboard-page">

        {{-- KPIs --}}
        <div class="row g-3">
            <div class="col-12 col-md-6 col-xl-3">
                <div class="p-4 kpi-card h-100">
                    <div class="text-muted">รถบรรทุกรวม</div>
                    <div class="kpi-number mt-1">
                        {{ number_format($truckCounts['total']) }}
                    </div>
                    <div class="mt-3 d-flex flex-wrap gap-2">
                        <span class="badge text-bg-dark">
                            พร้อมใช้งาน {{ $truckCounts['active'] }}
                        </span>
                        <span class="badge text-bg-secondary">
                            ซ่อมบำรุง {{ $truckCounts['maintenance'] }}
                        </span>
                        <span class="badge text-bg-light text-dark">
                            ปลดประจำการ {{ $truckCounts['retired'] }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6 col-xl-3">
                <div class="p-4 kpi-card h-100">
                    <div class="text-muted">พนักงานขับรถ</div>
                    <div class="kpi-number mt-1">
                        {{ number_format($driversTotal) }}
                    </div>
                    <div class="mt-3">
                        <a href="{{ route('drivers.index') }}" class="btn btn-outline-secondary btn-sm">
                            ดูทั้งหมด
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-12 col-xl-6">
                <div class="p-4 kpi-card h-100">
                    <div class="text-muted mb-2">ทางลัด</div>
                    <div class="d-flex flex-wrap gap-2">
                        <a href="{{ route('trucks.create') }}" class="btn btn-dark">
                            เพิ่มรถบรรทุก
                        </a>
                        <a href="{{ route('drivers.create') }}" class="btn btn-dark">
                            เพิ่มพนักงานขับรถ
                        </a>
                        <a href="{{ route('trucks.index') }}" class="btn btn-outline-secondary">
                            รถบรรทุกทั้งหมด
                        </a>
                        <a href="{{ route('drivers.index') }}" class="btn btn-outline-secondary">
                            พนักงานทั้งหมด
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Recent lists --}}
        <div class="row g-3 mt-1">
            <div class="col-12 col-xl-6">
                <div class="section-card p-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h5 class="mb-0">รถบรรทุก (ล่าสุด)</h5>
                        <a href="{{ route('trucks.index') }}" class="btn btn-sm btn-outline-secondary">
                            ดูทั้งหมด
                        </a>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th style="width:140px">ทะเบียน</th>
                                    <th>ยี่ห้อ / รุ่น</th>
                                    <th style="width:140px">สถานะ</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($latestTrucks as $t)
                                    <tr>
                                        <td class="fw-semibold">{{ $t->id_truck }}</td>
                                        <td>
                                            {{ $t->brand_truck }}
                                            {{ $t->model_truck ? ' · ' . $t->model_truck : '' }}
                                        </td>
                                        <td>
                                            @php
                                                $map = [
                                                    'active' => ['พร้อมใช้งาน', 'text-bg-dark'],
                                                    'maintenance' => ['ซ่อมบำรุง', 'text-bg-secondary'],
                                                    'retired' => ['ปลดประจำการ', 'text-bg-light text-dark'],
                                                ];
                                                [$label, $cls] = $map[$t->status_truck] ?? ['-', 'badge-soft'];
                                            @endphp
                                            <span class="badge {{ $cls }}">{{ $label }}</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted py-4">
                                            ยังไม่มีข้อมูล
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-12 col-xl-6">
                <div class="section-card p-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h5 class="mb-0">พนักงานขับรถ (ล่าสุด)</h5>
                        <a href="{{ route('drivers.index') }}" class="btn btn-sm btn-outline-secondary">
                            ดูทั้งหมด
                        </a>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th style="width:90px">รหัส</th>
                                    <th>ชื่อ-สกุล</th>
                                    <th style="width:160px">โทรศัพท์</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($latestDrivers as $d)
                                    <tr>
                                        <td>{{ $d->id_driver }}</td>
                                        <td class="fw-semibold">{{ $d->name_driver }}</td>
                                        <td>{{ $d->phone_driver ?: '-' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted py-4">
                                            ยังไม่มีข้อมูล
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
