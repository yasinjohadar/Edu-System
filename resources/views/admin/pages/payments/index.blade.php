@extends('admin.layouts.master')

@section('page-title')
    قائمة المدفوعات
@stop

@section('content')
    @if (\Session::has('success'))
        <div class="alert alert-success">
            <ul>
                <li>{!! \Session::get('success') !!}</li>
            </ul>
        </div>
    @endif

    @if (\Session::has('error'))
        <div class="alert alert-danger">
            <ul>
                <li>{!! \Session::get('error') !!}</li>
            </ul>
        </div>
    @endif

    <!-- Start::app-content -->
    <div class="main-content app-content">
        <div class="container-fluid">
            <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
                <div class="my-auto">
                    <h5 class="page-title fs-21 mb-1">المدفوعات</h5>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header align-items-center d-flex gap-3">
                            @can('payment-create')
                            <a href="{{ route('admin.payments.create') }}" class="btn btn-primary btn-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-left: 4px; vertical-align: middle;">
                                    <line x1="12" y1="5" x2="12" y2="19"></line>
                                    <line x1="5" y1="12" x2="19" y2="12"></line>
                                </svg>
                                تسجيل دفعة جديدة
                            </a>
                            @endcan
                            <div class="flex-shrink-0">
                                <form action="{{ route('admin.payments.index') }}" method="GET" class="d-flex align-items-center gap-2">
                                    <select name="student_id" class="form-select" style="width: 200px;">
                                        <option value="">كل الطلاب</option>
                                        @foreach($students as $student)
                                            <option value="{{ $student->id }}" {{ request('student_id') == $student->id ? 'selected' : '' }}>
                                                {{ $student->user->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <select name="status" class="form-select" style="width: 150px;">
                                        <option value="">كل الحالات</option>
                                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>معلق</option>
                                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>مكتمل</option>
                                        <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>فاشل</option>
                                        <option value="refunded" {{ request('status') == 'refunded' ? 'selected' : '' }}>مسترد</option>
                                    </select>
                                    <select name="payment_method" class="form-select" style="width: 150px;">
                                        <option value="">كل الطرق</option>
                                        <option value="cash" {{ request('payment_method') == 'cash' ? 'selected' : '' }}>نقدي</option>
                                        <option value="bank_transfer" {{ request('payment_method') == 'bank_transfer' ? 'selected' : '' }}>تحويل بنكي</option>
                                        <option value="card" {{ request('payment_method') == 'card' ? 'selected' : '' }}>بطاقة</option>
                                        <option value="check" {{ request('payment_method') == 'check' ? 'selected' : '' }}>شيك</option>
                                    </select>
                                    <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}" style="width: 150px;">
                                    <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}" style="width: 150px;">
                                    <button type="submit" class="btn btn-secondary">بحث</button>
                                    <a href="{{ route('admin.payments.index') }}" class="btn btn-danger">مسح</a>
                                </form>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover align-middle table-nowrap mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>رقم الدفعة</th>
                                            <th>الطالب</th>
                                            <th>الفاتورة</th>
                                            <th>التاريخ</th>
                                            <th>المبلغ</th>
                                            <th>طريقة الدفع</th>
                                            <th>الحالة</th>
                                            <th>العمليات</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($payments as $payment)
                                            <tr>
                                                <td>{{ $payment->id }}</td>
                                                <td><strong>{{ $payment->payment_number }}</strong></td>
                                                <td>
                                                    {{ $payment->student->user->name ?? 'غير محدد' }}
                                                    <br>
                                                    <small class="text-muted">{{ $payment->student->student_code }}</small>
                                                </td>
                                                <td>
                                                    @if($payment->invoice)
                                                        <a href="{{ route('admin.invoices.show', $payment->invoice->id) }}">
                                                            {{ $payment->invoice->invoice_number }}
                                                        </a>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td>{{ $payment->payment_date->format('Y-m-d') }}</td>
                                                <td><strong class="text-success">{{ number_format($payment->amount, 2) }} ر.س</strong></td>
                                                <td>{{ $payment->payment_method_name }}</td>
                                                <td>
                                                    @if($payment->status == 'completed')
                                                        <span class="badge bg-success">{{ $payment->status_name }}</span>
                                                    @elseif($payment->status == 'pending')
                                                        <span class="badge bg-warning">{{ $payment->status_name }}</span>
                                                    @elseif($payment->status == 'failed')
                                                        <span class="badge bg-danger">{{ $payment->status_name }}</span>
                                                    @else
                                                        <span class="badge bg-secondary">{{ $payment->status_name }}</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="d-flex gap-2">
                                                        <a href="{{ route('admin.payments.show', $payment->id) }}" class="btn btn-sm btn-info" title="عرض">
                                                            <i class="fa-solid fa-eye"></i>
                                                        </a>
                                                        @can('payment-edit')
                                                        @if($payment->status != 'refunded')
                                                        <a href="{{ route('admin.payments.edit', $payment->id) }}" class="btn btn-sm btn-warning" title="تعديل">
                                                            <i class="fa-solid fa-edit"></i>
                                                        </a>
                                                        @endif
                                                        @endcan
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="9" class="text-center">لا توجد مدفوعات</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-3">
                                {{ $payments->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End::app-content -->
@stop

