@extends('admin.layouts.master')

@section('page-title')
    التبرعات
@stop

@section('content')
    <div class="main-content app-content">
        <div class="container-fluid">
            <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
                <div class="my-auto">
                    <h5 class="page-title fs-21 mb-1">التبرعات</h5>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header align-items-center d-flex gap-3">
                            <div class="d-flex gap-2">
                                <a href="{{ route('admin.alumni-donations.create') }}" class="btn btn-primary btn-sm">إضافة تبرع جديد</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover align-middle table-nowrap mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>اسم الخريج</th>
                                            <th>المبلغ</th>
                                            <th>طريقة الدفع</th>
                                            <th>تاريخ التبرع</th>
                                            <th>الحالة</th>
                                            <th>العمليات</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($donations as $donation)
                                            <tr>
                                                <td>{{ $donation->id }}</td>
                                                <td>{{ $donation->alumni->name ?? '-' }}</td>
                                                <td>{{ number_format($donation->amount, 2) }}</td>
                                                <td><span class="badge bg-info">{{ $donation->payment_method }}</span></td>
                                                <td>{{ $donation->donation_date->format('Y-m-d') }}</td>
                                                <td><span class="badge bg-info">{{ $donation->status }}</span></td>
                                                <td>
                                                    <div class="d-flex gap-2">
                                                        <a href="{{ route('admin.alumni-donations.show', $donation) }}" class="btn btn-sm btn-info">عرض</a>
                                                        <a href="{{ route('admin.alumni-donations.edit', $donation) }}" class="btn btn-sm btn-primary">تعديل</a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center">لا توجد تبرعات</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-3">
                                {{ $donations->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

