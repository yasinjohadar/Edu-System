@extends('student.layouts.master')

@section('page-title')
    تفاصيل الفاتورة
@stop

@section('content')
    <!-- Start::app-content -->
    <div class="main-content app-content">
        <div class="container-fluid">
            <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
                <div class="my-auto">
                    <h5 class="page-title fs-21 mb-1">تفاصيل الفاتورة</h5>
                </div>
                <div>
                    <button onclick="window.print()" class="btn btn-primary btn-sm">طباعة</button>
                    <a href="{{ route('student.invoices.index') }}" class="btn btn-secondary btn-sm">رجوع</a>
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
                                            <span class="badge bg-success">مدفوعة</span>
                                        @elseif($invoice->status == 'overdue')
                                            <span class="badge bg-danger">متأخرة</span>
                                        @elseif($invoice->status == 'partial')
                                            <span class="badge bg-warning">جزئية</span>
                                        @else
                                            <span class="badge bg-info">معلقة</span>
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
                                            <th class="text-end">الإجمالي</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($invoice->items as $index => $item)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $item->item_name }}</td>
                                                <td>{{ $item->description ?? '-' }}</td>
                                                <td class="text-center">{{ $item->quantity }}</td>
                                                <td class="text-end">{{ number_format($item->unit_price, 2) }} ر.س</td>
                                                <td class="text-end">{{ number_format($item->total, 2) }} ر.س</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Totals -->
                            <div class="row">
                                <div class="col-md-6"></div>
                                <div class="col-md-6">
                                    <table class="table table-bordered">
                                        <tr>
                                            <th>المجموع الفرعي:</th>
                                            <td class="text-end">{{ number_format($invoice->subtotal, 2) }} ر.س</td>
                                        </tr>
                                        @if($invoice->discount_amount > 0)
                                        <tr>
                                            <th>الخصم:</th>
                                            <td class="text-end text-danger">- {{ number_format($invoice->discount_amount, 2) }} ر.س</td>
                                        </tr>
                                        @endif
                                        @if($invoice->tax_amount > 0)
                                        <tr>
                                            <th>الضريبة:</th>
                                            <td class="text-end">{{ number_format($invoice->tax_amount, 2) }} ر.س</td>
                                        </tr>
                                        @endif
                                        <tr class="table-primary">
                                            <th><strong>المبلغ الإجمالي:</strong></th>
                                            <td class="text-end"><strong>{{ number_format($invoice->total_amount, 2) }} ر.س</strong></td>
                                        </tr>
                                        <tr>
                                            <th>المبلغ المدفوع:</th>
                                            <td class="text-end text-success">{{ number_format($invoice->paid_amount, 2) }} ر.س</td>
                                        </tr>
                                        <tr class="table-warning">
                                            <th><strong>المبلغ المتبقي:</strong></th>
                                            <td class="text-end"><strong class="text-danger">{{ number_format($invoice->remaining_amount, 2) }} ر.س</strong></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                            @if($invoice->notes)
                                <div class="mt-3">
                                    <strong>ملاحظات:</strong>
                                    <p>{{ $invoice->notes }}</p>
                                </div>
                            @endif

                            @if($invoice->payments->count() > 0)
                                <hr>
                                <h5 class="mb-3">سجل المدفوعات</h5>
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
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
                                                    <td>{{ number_format($payment->amount, 2) }} ر.س</td>
                                                    <td>{{ $payment->payment_method }}</td>
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
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End::app-content -->
@stop

