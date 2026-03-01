@extends('admin.layouts.master')

@section('page-title')
    عرض الفاتورة
@stop

@section('content')
    <!-- Start::app-content -->
    <div class="main-content app-content">
        <div class="container-fluid">
            <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
                <div class="my-auto">
                    <h5 class="page-title fs-21 mb-1">عرض الفاتورة</h5>
                </div>
                <div>
                    <a href="{{ route('admin.invoices.index') }}" class="btn btn-secondary">
                        <i class="fa-solid fa-arrow-right"></i> العودة للقائمة
                    </a>
                    @can('invoice-edit')
                    @if($invoice->status != 'paid' && $invoice->status != 'cancelled')
                    <a href="{{ route('admin.invoices.edit', $invoice->id) }}" class="btn btn-warning">
                        <i class="fa-solid fa-edit"></i> تعديل
                    </a>
                    @endif
                    @endcan
                    <button onclick="window.print()" class="btn btn-primary">
                        <i class="fa-solid fa-print"></i> طباعة
                    </button>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-12">
                    <div class="card" id="invoiceCard">
                        <div class="card-body">
                            <!-- Header -->
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <h4 class="mb-3">فاتورة مالية</h4>
                                    <p class="mb-1"><strong>رقم الفاتورة:</strong> {{ $invoice->invoice_number }}</p>
                                    <p class="mb-1"><strong>تاريخ الفاتورة:</strong> {{ $invoice->invoice_date->format('Y-m-d') }}</p>
                                    <p class="mb-1"><strong>تاريخ الاستحقاق:</strong> {{ $invoice->due_date->format('Y-m-d') }}</p>
                                    <p class="mb-0">
                                        <strong>الحالة:</strong> 
                                        @if($invoice->status == 'paid')
                                            <span class="badge bg-success">{{ $invoice->status_name }}</span>
                                        @elseif($invoice->status == 'overdue')
                                            <span class="badge bg-danger">{{ $invoice->status_name }}</span>
                                        @elseif($invoice->status == 'partial')
                                            <span class="badge bg-warning">{{ $invoice->status_name }}</span>
                                        @else
                                            <span class="badge bg-info">{{ $invoice->status_name }}</span>
                                        @endif
                                    </p>
                                </div>
                                <div class="col-md-6 text-end">
                                    <h5>معلومات الطالب</h5>
                                    <p class="mb-1"><strong>الاسم:</strong> {{ $invoice->student->user->name ?? 'غير محدد' }}</p>
                                    <p class="mb-1"><strong>رقم القيد:</strong> {{ $invoice->student->student_code }}</p>
                                    @if($invoice->student->class)
                                    <p class="mb-1"><strong>الصف:</strong> {{ $invoice->student->class->name }}</p>
                                    @endif
                                    @if($invoice->student->section)
                                    <p class="mb-0"><strong>الفصل:</strong> {{ $invoice->student->section->name }}</p>
                                    @endif
                                </div>
                            </div>

                            <hr>

                            <!-- Items -->
                            <div class="table-responsive mb-4">
                                <table class="table table-bordered">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>البند</th>
                                            <th>الوصف</th>
                                            <th class="text-center">الكمية</th>
                                            <th class="text-end">سعر الوحدة</th>
                                            <th class="text-end">الخصم</th>
                                            <th class="text-end">الضريبة</th>
                                            <th class="text-end">الإجمالي</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($invoice->items as $index => $item)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>
                                                    <strong>{{ $item->item_name }}</strong>
                                                    @if($item->feeType)
                                                        <br><small class="text-muted">{{ $item->feeType->name }}</small>
                                                    @endif
                                                </td>
                                                <td>{{ $item->description ?? '-' }}</td>
                                                <td class="text-center">{{ $item->quantity }}</td>
                                                <td class="text-end">{{ number_format($item->unit_price, 2) }} ر.س</td>
                                                <td class="text-end">{{ number_format($item->discount, 2) }} ر.س</td>
                                                <td class="text-end">{{ number_format($item->tax, 2) }} ر.س</td>
                                                <td class="text-end"><strong>{{ number_format($item->total, 2) }} ر.س</strong></td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Totals -->
                            <div class="row">
                                <div class="col-md-6">
                                    @if($invoice->notes)
                                    <div class="mb-3">
                                        <strong>ملاحظات:</strong>
                                        <p class="mb-0">{{ $invoice->notes }}</p>
                                    </div>
                                    @endif
                                    @if($invoice->terms)
                                    <div>
                                        <strong>شروط الدفع:</strong>
                                        <p class="mb-0">{{ $invoice->terms }}</p>
                                    </div>
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <tr>
                                                <td><strong>المجموع الفرعي:</strong></td>
                                                <td class="text-end">{{ number_format($invoice->subtotal, 2) }} ر.س</td>
                                            </tr>
                                            @if($invoice->discount_amount > 0)
                                            <tr>
                                                <td><strong>الخصم:</strong></td>
                                                <td class="text-end text-danger">- {{ number_format($invoice->discount_amount, 2) }} ر.س</td>
                                            </tr>
                                            @endif
                                            @if($invoice->tax_amount > 0)
                                            <tr>
                                                <td><strong>الضريبة:</strong></td>
                                                <td class="text-end">{{ number_format($invoice->tax_amount, 2) }} ر.س</td>
                                            </tr>
                                            @endif
                                            <tr class="table-primary">
                                                <td><strong>المبلغ الإجمالي:</strong></td>
                                                <td class="text-end"><strong>{{ number_format($invoice->total_amount, 2) }} ر.س</strong></td>
                                            </tr>
                                            <tr>
                                                <td><strong>المبلغ المدفوع:</strong></td>
                                                <td class="text-end text-success">{{ number_format($invoice->paid_amount, 2) }} ر.س</td>
                                            </tr>
                                            <tr>
                                                <td><strong>المبلغ المتبقي:</strong></td>
                                                <td class="text-end text-danger"><strong>{{ number_format($invoice->remaining_amount, 2) }} ر.س</strong></td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <!-- Payments -->
                            @if($invoice->payments->count() > 0)
                            <hr>
                            <h5 class="mb-3">سجل المدفوعات</h5>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead class="table-light">
                                        <tr>
                                            <th>رقم الدفعة</th>
                                            <th>التاريخ</th>
                                            <th>المبلغ</th>
                                            <th>طريقة الدفع</th>
                                            <th>الحالة</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($invoice->payments as $payment)
                                            <tr>
                                                <td>{{ $payment->payment_number }}</td>
                                                <td>{{ $payment->payment_date->format('Y-m-d') }}</td>
                                                <td><strong>{{ number_format($payment->amount, 2) }} ر.س</strong></td>
                                                <td>{{ $payment->payment_method_name }}</td>
                                                <td>
                                                    @if($payment->status == 'completed')
                                                        <span class="badge bg-success">{{ $payment->status_name }}</span>
                                                    @else
                                                        <span class="badge bg-warning">{{ $payment->status_name }}</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @endif

                            @if($invoice->remaining_amount > 0)
                            <div class="mt-4 text-center">
                                <a href="{{ route('admin.payments.create', ['invoice_id' => $invoice->id]) }}" class="btn btn-success btn-lg">
                                    <i class="fa-solid fa-money-bill"></i> تسجيل دفعة جديدة
                                </a>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End::app-content -->
@stop

@push('styles')
<style>
@media print {
    .page-header-breadcrumb, .btn, nav, aside {
        display: none !important;
    }
    #invoiceCard {
        border: none;
        box-shadow: none;
    }
}
</style>
@endpush

