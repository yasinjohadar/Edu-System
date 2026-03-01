@extends('admin.layouts.master')

@section('page-title')
    التقارير المالية
@stop

@section('content')
    <!-- Start::app-content -->
    <div class="main-content app-content">
        <div class="container-fluid">
            <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
                <div class="my-auto">
                    <h5 class="page-title fs-21 mb-1">التقارير المالية</h5>
                </div>
                <div>
                    <a href="{{ route('admin.reports.index') }}" class="btn btn-secondary">رجوع</a>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header align-items-center d-flex gap-3">
                            <h5 class="card-title mb-0">فلاتر التقرير</h5>
                            <div class="flex-shrink-0">
                                <form action="{{ route('admin.reports.financial') }}" method="GET" class="d-flex align-items-center gap-2">
                                    <select name="status" class="form-select" style="width: 150px;">
                                        <option value="">كل الحالات</option>
                                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>معلقة</option>
                                        <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>مدفوعة</option>
                                        <option value="partial" {{ request('status') == 'partial' ? 'selected' : '' }}>جزئية</option>
                                        <option value="overdue" {{ request('status') == 'overdue' ? 'selected' : '' }}>متأخرة</option>
                                    </select>
                                    <input type="date" name="date_from" class="form-control" style="width: 150px;" value="{{ request('date_from') }}">
                                    <input type="date" name="date_to" class="form-control" style="width: 150px;" value="{{ request('date_to') }}">
                                    <button type="submit" class="btn btn-primary">بحث</button>
                                    <a href="{{ route('admin.reports.financial') }}" class="btn btn-danger">مسح</a>
                                </form>
                            </div>
                        </div>
                        <div class="card-body">
                            <!-- إحصائيات الفواتير -->
                            <div class="row mb-4">
                                <div class="col-md-3">
                                    <div class="card border-primary">
                                        <div class="card-body text-center">
                                            <h6 class="text-muted">إجمالي الفواتير</h6>
                                            <h3 class="mb-0">{{ $stats['total_invoices'] }}</h3>
                                            <small class="text-muted">{{ number_format($stats['total_amount'], 2) }} ر.س</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card border-success">
                                        <div class="card-body text-center">
                                            <h6 class="text-muted">المدفوع</h6>
                                            <h3 class="mb-0 text-success">{{ $stats['paid_count'] }}</h3>
                                            <small class="text-muted">{{ number_format($stats['paid_amount'], 2) }} ر.س</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card border-warning">
                                        <div class="card-body text-center">
                                            <h6 class="text-muted">المتبقي</h6>
                                            <h3 class="mb-0 text-warning">{{ $stats['pending_count'] + $stats['partial_count'] }}</h3>
                                            <small class="text-muted">{{ number_format($stats['remaining_amount'], 2) }} ر.س</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card border-danger">
                                        <div class="card-body text-center">
                                            <h6 class="text-muted">متأخرة</h6>
                                            <h3 class="mb-0 text-danger">{{ $stats['overdue_count'] }}</h3>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <h5 class="mb-3">الفواتير</h5>
                            @if($invoices->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover align-middle table-nowrap mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th>#</th>
                                                <th>رقم الفاتورة</th>
                                                <th>اسم الطالب</th>
                                                <th>تاريخ الفاتورة</th>
                                                <th>تاريخ الاستحقاق</th>
                                                <th>المبلغ الإجمالي</th>
                                                <th>المدفوع</th>
                                                <th>المتبقي</th>
                                                <th>الحالة</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($invoices as $invoice)
                                                <tr>
                                                    <td>{{ $invoice->id }}</td>
                                                    <td>{{ $invoice->invoice_number }}</td>
                                                    <td>{{ $invoice->student->user->name }}</td>
                                                    <td>{{ $invoice->invoice_date->format('Y-m-d') }}</td>
                                                    <td>{{ $invoice->due_date->format('Y-m-d') }}</td>
                                                    <td>{{ number_format($invoice->total_amount, 2) }} ر.س</td>
                                                    <td>{{ number_format($invoice->paid_amount, 2) }} ر.س</td>
                                                    <td>{{ number_format($invoice->remaining_amount, 2) }} ر.س</td>
                                                    <td>
                                                        @if($invoice->status == 'paid')
                                                            <span class="badge bg-success">{{ $invoice->status_name }}</span>
                                                        @elseif($invoice->status == 'partial')
                                                            <span class="badge bg-warning">{{ $invoice->status_name }}</span>
                                                        @elseif($invoice->status == 'overdue')
                                                            <span class="badge bg-danger">{{ $invoice->status_name }}</span>
                                                        @else
                                                            <span class="badge bg-secondary">{{ $invoice->status_name }}</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="alert alert-info text-center">
                                    <p class="mb-0">لا توجد فواتير متاحة</p>
                                </div>
                            @endif

                            <h5 class="mb-3 mt-4">المدفوعات</h5>
                            @if($payments->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover align-middle table-nowrap mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th>#</th>
                                                <th>اسم الطالب</th>
                                                <th>رقم الفاتورة</th>
                                                <th>تاريخ الدفع</th>
                                                <th>المبلغ</th>
                                                <th>طريقة الدفع</th>
                                                <th>الحالة</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($payments as $payment)
                                                <tr>
                                                    <td>{{ $payment->id }}</td>
                                                    <td>{{ $payment->student->user->name }}</td>
                                                    <td>{{ $payment->invoice->invoice_number ?? '-' }}</td>
                                                    <td>{{ $payment->payment_date->format('Y-m-d') }}</td>
                                                    <td>{{ number_format($payment->amount, 2) }} ر.س</td>
                                                    <td>{{ $payment->payment_method ?? '-' }}</td>
                                                    <td>
                                                        @if($payment->status == 'completed')
                                                            <span class="badge bg-success">مكتمل</span>
                                                        @else
                                                            <span class="badge bg-warning">{{ $payment->status }}</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="alert alert-info text-center">
                                    <p class="mb-0">لا توجد مدفوعات متاحة</p>
                                </div>
                            @endif
                        </div>
                        @if($invoices->count() > 0 || $payments->count() > 0)
                            <div class="card-footer">
                                <form action="{{ route('admin.reports.export') }}" method="POST" class="d-inline">
                                    @csrf
                                    <input type="hidden" name="type" value="financial">
                                    @foreach(request()->except(['type', 'format', '_token']) as $key => $value)
                                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                                    @endforeach
                                    <button type="submit" name="format" value="pdf" class="btn btn-danger">تصدير PDF</button>
                                    <button type="submit" name="format" value="excel" class="btn btn-success">تصدير Excel</button>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End::app-content -->
@stop

