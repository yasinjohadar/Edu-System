@extends('student.layouts.master')

@section('page-title')
    الفواتير والمدفوعات
@stop

@section('content')
    <!-- Start::app-content -->
    <div class="main-content app-content">
        <div class="container-fluid">
            <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
                <div class="my-auto">
                    <h5 class="page-title fs-21 mb-1">الفواتير والمدفوعات</h5>
                </div>
            </div>

            <!-- إحصائيات -->
            <div class="row mb-4">
                <div class="col-xl-3 col-lg-6 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="avatar avatar-md bg-primary text-white rounded">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                            <polyline points="14 2 14 8 20 8"></polyline>
                                        </svg>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-0">إجمالي الفواتير</h6>
                                    <h4 class="mb-0">{{ $stats['total_invoices'] }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-6 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="avatar avatar-md bg-success text-white rounded">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <polyline points="20 6 9 17 4 12"></polyline>
                                        </svg>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-0">الفواتير المدفوعة</h6>
                                    <h4 class="mb-0">{{ $stats['paid_invoices'] }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-6 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="avatar avatar-md bg-warning text-white rounded">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <circle cx="12" cy="12" r="10"></circle>
                                            <polyline points="12 6 12 12 16 14"></polyline>
                                        </svg>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-0">الفواتير المعلقة</h6>
                                    <h4 class="mb-0">{{ $stats['pending_invoices'] }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-6 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="avatar avatar-md bg-danger text-white rounded">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <line x1="12" y1="1" x2="12" y2="23"></line>
                                            <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-0">المبلغ المتبقي</h6>
                                    <h4 class="mb-0">{{ number_format($stats['remaining_amount'], 2) }} ر.س</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header align-items-center d-flex gap-3">
                            <div class="flex-shrink-0">
                                <form action="{{ route('student.invoices.index') }}" method="GET" class="d-flex align-items-center gap-2">
                                    <select name="status" class="form-select" style="width: 150px;">
                                        <option value="">كل الحالات</option>
                                        <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>مدفوعة</option>
                                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>معلقة</option>
                                        <option value="partial" {{ request('status') == 'partial' ? 'selected' : '' }}>جزئية</option>
                                        <option value="overdue" {{ request('status') == 'overdue' ? 'selected' : '' }}>متأخرة</option>
                                    </select>
                                    <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}" style="width: 180px;" placeholder="من تاريخ">
                                    <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}" style="width: 180px;" placeholder="إلى تاريخ">
                                    <button type="submit" class="btn btn-secondary">بحث</button>
                                    <a href="{{ route('student.invoices.index') }}" class="btn btn-danger">مسح</a>
                                </form>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover align-middle table-nowrap mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>رقم الفاتورة</th>
                                            <th>تاريخ الفاتورة</th>
                                            <th>تاريخ الاستحقاق</th>
                                            <th>المبلغ الإجمالي</th>
                                            <th>المدفوع</th>
                                            <th>المتبقي</th>
                                            <th>الحالة</th>
                                            <th>العمليات</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($invoices as $invoice)
                                            <tr>
                                                <td>{{ $invoice->id }}</td>
                                                <td><strong>{{ $invoice->invoice_number }}</strong></td>
                                                <td>{{ $invoice->invoice_date->format('Y-m-d') }}</td>
                                                <td>{{ $invoice->due_date->format('Y-m-d') }}</td>
                                                <td><strong>{{ number_format($invoice->total_amount, 2) }} ر.س</strong></td>
                                                <td>{{ number_format($invoice->paid_amount, 2) }} ر.س</td>
                                                <td>
                                                    @if($invoice->remaining_amount > 0)
                                                        <strong class="text-danger">{{ number_format($invoice->remaining_amount, 2) }} ر.س</strong>
                                                    @else
                                                        <span class="text-success">0.00 ر.س</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($invoice->status == 'paid')
                                                        <span class="badge bg-success">مدفوعة</span>
                                                    @elseif($invoice->status == 'overdue')
                                                        <span class="badge bg-danger">متأخرة</span>
                                                    @elseif($invoice->status == 'partial')
                                                        <span class="badge bg-warning">جزئية</span>
                                                    @else
                                                        <span class="badge bg-info">معلقة</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="{{ route('student.invoices.show', $invoice->id) }}" class="btn btn-sm btn-info" title="عرض">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                            <circle cx="12" cy="12" r="3"></circle>
                                                        </svg>
                                                    </a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="9" class="text-center">لا توجد فواتير</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-3">
                                {{ $invoices->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End::app-content -->
@stop

