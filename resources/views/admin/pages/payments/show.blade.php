@extends('admin.layouts.master')

@section('page-title')
    تفاصيل الدفعة
@stop

@section('content')
    <!-- Start::app-content -->
    <div class="main-content app-content">
        <div class="container-fluid">
            <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
                <div class="my-auto">
                    <h5 class="page-title fs-21 mb-1">تفاصيل الدفعة</h5>
                </div>
                <div>
                    <a href="{{ route('admin.payments.index') }}" class="btn btn-secondary">
                        <i class="fa-solid fa-arrow-right"></i> العودة للقائمة
                    </a>
                    @can('payment-edit')
                    @if($payment->status != 'refunded')
                    <a href="{{ route('admin.payments.edit', $payment->id) }}" class="btn btn-warning">
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
                    <div class="card" id="paymentCard">
                        <div class="card-body">
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <h4 class="mb-3">إيصال دفع</h4>
                                    <p class="mb-1"><strong>رقم الدفعة:</strong> {{ $payment->payment_number }}</p>
                                    <p class="mb-1"><strong>تاريخ الدفع:</strong> {{ $payment->payment_date->format('Y-m-d') }}</p>
                                    <p class="mb-1"><strong>المبلغ:</strong> <span class="text-success"><strong>{{ number_format($payment->amount, 2) }} ر.س</strong></span></p>
                                    <p class="mb-0">
                                        <strong>الحالة:</strong> 
                                        @if($payment->status == 'completed')
                                            <span class="badge bg-success">{{ $payment->status_name }}</span>
                                        @elseif($payment->status == 'pending')
                                            <span class="badge bg-warning">{{ $payment->status_name }}</span>
                                        @else
                                            <span class="badge bg-danger">{{ $payment->status_name }}</span>
                                        @endif
                                    </p>
                                </div>
                                <div class="col-md-6 text-end">
                                    <h5>معلومات الطالب</h5>
                                    <p class="mb-1"><strong>الاسم:</strong> {{ $payment->student->user->name ?? 'غير محدد' }}</p>
                                    <p class="mb-1"><strong>رقم القيد:</strong> {{ $payment->student->student_code }}</p>
                                    @if($payment->invoice)
                                    <p class="mb-0">
                                        <strong>الفاتورة:</strong> 
                                        <a href="{{ route('admin.invoices.show', $payment->invoice->id) }}">
                                            {{ $payment->invoice->invoice_number }}
                                        </a>
                                    </p>
                                    @endif
                                </div>
                            </div>

                            <hr>

                            <div class="row">
                                <div class="col-md-6">
                                    <h6>تفاصيل الدفع</h6>
                                    <table class="table table-bordered">
                                        <tr>
                                            <td><strong>طريقة الدفع:</strong></td>
                                            <td>{{ $payment->payment_method_name }}</td>
                                        </tr>
                                        @if($payment->reference_number)
                                        <tr>
                                            <td><strong>رقم المرجع:</strong></td>
                                            <td>{{ $payment->reference_number }}</td>
                                        </tr>
                                        @endif
                                        @if($payment->bank_name)
                                        <tr>
                                            <td><strong>اسم البنك:</strong></td>
                                            <td>{{ $payment->bank_name }}</td>
                                        </tr>
                                        @endif
                                        @if($payment->receiver)
                                        <tr>
                                            <td><strong>استلم بواسطة:</strong></td>
                                            <td>{{ $payment->receiver->name }}</td>
                                        </tr>
                                        @endif
                                        @if($payment->processed_at)
                                        <tr>
                                            <td><strong>تاريخ المعالجة:</strong></td>
                                            <td>{{ $payment->processed_at->format('Y-m-d H:i') }}</td>
                                        </tr>
                                        @endif
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    @if($payment->notes)
                                    <h6>ملاحظات</h6>
                                    <p>{{ $payment->notes }}</p>
                                    @endif
                                </div>
                            </div>
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
    #paymentCard {
        border: none;
        box-shadow: none;
    }
}
</style>
@endpush

