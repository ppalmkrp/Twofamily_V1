@extends('layout')

@section('namepage')
    <div class="container">
        <h3>ข้อมูลลูกค้า</h3>
    </div>
@endsection

@section('content')
    <div class="container py-4">

        <div class="card shadow-sm" style="max-width:800px;margin:auto">
            <div class="card-body p-4">

                <div class="mb-3">
                    <strong>ชื่อลูกค้า:</strong><br>
                    {{ $customer->name_customer }}
                </div>

                <div class="mb-3">
                    <strong>ประเภท:</strong><br>
                    {{ $customer->customer_type == 'company' ? 'บริษัท' : 'บุคคล' }}
                </div>

                <div class="mb-3">
                    <strong>เบอร์โทร:</strong><br>
                    {{ $customer->phone_customer ?: '-' }}
                </div>

                <div class="mb-3">
                    <strong>อีเมล:</strong><br>
                    {{ $customer->email_customer ?: '-' }}
                </div>

                <div class="mb-3">
                    <strong>ที่อยู่:</strong><br>
                    {{ $customer->address_detail }}
                    {{ $customer->subdistrict }}
                    {{ $customer->district }}
                    {{ $customer->province }}
                    {{ $customer->zipcode }}
                </div>

                <div class="d-flex gap-2 pt-3">
                    <a href="{{ route('customers.index') }}" class="btn btn-outline-secondary">
                        ย้อนกลับ
                    </a>

                    <a href="{{ route('customers.edit', $customer) }}" class="btn btn-outline-primary">
                        แก้ไข
                    </a>
                </div>

            </div>
        </div>

    </div>
@endsection
