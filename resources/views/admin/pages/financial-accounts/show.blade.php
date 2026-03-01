@extends('admin.layouts.master')

@section('page-title')
    تفاصيل الحساب المالي
@stop

@section('content')
    <!-- Start::app-content -->
    <div class="main-content app-content">
        <div class="container-fluid">
            <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
                <div class="my-auto">
                    <h5 class="page-title fs-21 mb-1">تفاصيل الحساب المالي</h5>
                </div>
                <div>
                    <a href="{{ route('admin.financial-accounts.index') }}" class="btn btn-secondary">
                        <i class="fa-solid fa-arrow-right"></i> العودة للقائمة
                    </a>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-12">
                    <!-- معلومات الحساب -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title">معلومات الحساب</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <p class="mb-1"><strong>رقم الحساب:</strong></p>
                                    <h5>{{ $account->account_number }}</h5>
                                </div>
                                <div class="col-md-3">
                                    <p class="mb-1"><strong>الطالب:</strong></p>
                                    <h5>{{ $account->student->user->name ?? 'غير محدد' }}</h5>
                                    <small class="text-muted">{{ $account->student->student_code }}</small>
                                </div>
                                <div class="col-md-3">
                                    <p class="mb-1"><strong>إجمالي الفواتير:</strong></p>
                                    <h5 class="text-primary">{{ number_format($account->total_invoiced, 2) }} ر.س</h5>
                                </div>
                                <div class="col-md-3">
                                    <p class="mb-1"><strong>إجمالي المدفوعات:</strong></p>
                                    <h5 class="text-success">{{ number_format($account->total_paid, 2) }} ر.س</h5>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-4">
                                    <p class="mb-1"><strong>المستحقات:</strong></p>
                                    <h4 class="text-danger">{{ number_format($account->total_due, 2) }} ر.س</h4>
                                </div>
                                <div class="col-md-4">
                                    <p class="mb-1"><strong>الرصيد:</strong></p>
                                    @if($account->balance >= 0)
                                        <h4 class="text-success">{{ number_format($account->balance, 2) }} ر.س</h4>
                                    @else
                                        <h4 class="text-danger">{{ number_format($account->balance, 2) }} ر.س</h4>
                                    @endif
                                </div>
                                <div class="col-md-4">
                                    <p class="mb-1"><strong>آخر معاملة:</strong></p>
                                    <p>{{ $account->last_transaction_date ? $account->last_transaction_date->format('Y-m-d') : '-' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- الفواتير -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title">الفواتير ({{ $account->invoices->count() }})</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead class="table-light">
                                        <tr>
                                            <th>رقم الفاتورة</th>
                                            <th>التاريخ</th>
                                            <th>تاريخ الاستحقاق</th>
                                            <th>المبلغ الإجمالي</th>
                                            <th>المدفوع</th>
                                            <th>المتبقي</th>
                                            <th>الحالة</th>
                                            <th>العمليات</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($account->invoices as $invoice)
                                            <tr>
                                                <td><strong>{{ $invoice->invoice_number }}</strong></td>
                                                <td>{{ $invoice->invoice_date->format('Y-m-d') }}</td>
                                                <td>{{ $invoice->due_date->format('Y-m-d') }}</td>
                                                <td>{{ number_format($invoice->total_amount, 2) }} ر.س</td>
                                                <td class="text-success">{{ number_format($invoice->paid_amount, 2) }} ر.س</td>
                                                <td class="text-danger">{{ number_format($invoice->remaining_amount, 2) }} ر.س</td>
                                                <td>
                                                    @if($invoice->status == 'paid')
                                                        <span class="badge bg-success">{{ $invoice->status_name }}</span>
                                                    @elseif($invoice->status == 'overdue')
                                                        <span class="badge bg-danger">{{ $invoice->status_name }}</span>
                                                    @else
                                                        <span class="badge bg-warning">{{ $invoice->status_name }}</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="{{ route('admin.invoices.show', $invoice->id) }}" class="btn btn-sm btn-info">
                                                        <i class="fa-solid fa-eye"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="8" class="text-center">لا توجد فواتير</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- المدفوعات -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">المدفوعات ({{ $account->payments->count() }})</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead class="table-light">
                                        <tr>
                                            <th>رقم الدفعة</th>
                                            <th>التاريخ</th>
                                            <th>المبلغ</th>
                                            <th>طريقة الدفع</th>
                                            <th>الفاتورة</th>
                                            <th>الحالة</th>
                                            <th>العمليات</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($account->payments as $payment)
                                            <tr>
                                                <td><strong>{{ $payment->payment_number }}</strong></td>
                                                <td>{{ $payment->payment_date->format('Y-m-d') }}</td>
                                                <td class="text-success"><strong>{{ number_format($payment->amount, 2) }} ر.س</strong></td>
                                                <td>{{ $payment->payment_method_name }}</td>
                                                <td>
                                                    @if($payment->invoice)
                                                        <a href="{{ route('admin.invoices.show', $payment->invoice->id) }}">
                                                            {{ $payment->invoice->invoice_number }}
                                                        </a>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($payment->status == 'completed')
                                                        <span class="badge bg-success">{{ $payment->status_name }}</span>
                                                    @else
                                                        <span class="badge bg-warning">{{ $payment->status_name }}</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="{{ route('admin.payments.show', $payment->id) }}" class="btn btn-sm btn-info">
                                                        <i class="fa-solid fa-eye"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center">لا توجد مدفوعات</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End::app-content -->
@stop

