@extends('admin.layouts.master')

@section('page-title')
    تفاصيل الدفعة
@stop

@section('content')
    @php
        $statusClasses = [
            'completed' => 'admin-badge-success',
            'pending' => 'admin-badge-warning',
            'failed' => 'admin-badge-danger',
            'refunded' => 'admin-badge-muted',
        ];
    @endphp

    <div class="main-content app-content">
        <div class="container-fluid">

            <div class="admin-page-header">
                <div class="page-title-wrap">
                    <h1>تفاصيل الدفعة</h1>
                    <p>{{ $payment->payment_number }} — {{ $payment->student->user->name ?? 'غير محدد' }}</p>
                </div>
                <div class="admin-page-header-actions">
                    @can('payment-edit')
                        @if ($payment->status != 'refunded')
                            <a href="{{ route('admin.payments.edit', $payment->id) }}" class="admin-btn admin-btn-primary">
                                <i class="ri-edit-line"></i>
                                تعديل
                            </a>
                        @endif
                    @endcan
                    <button type="button" onclick="window.print()" class="admin-btn admin-btn-secondary">
                        <i class="ri-printer-line"></i>
                        طباعة
                    </button>
                    <a href="{{ route('admin.payments.index') }}" class="admin-btn admin-btn-secondary">
                        <i class="ri-arrow-right-line"></i>
                        العودة
                    </a>
                </div>
            </div>

            <div class="admin-page-card" id="paymentCard">
                <div class="admin-detail-card">
                    <div class="admin-detail-card-head">
                        <h3><i class="ri-wallet-3-line section-icon-sm"></i> إيصال الدفع</h3>
                        <span class="admin-badge {{ $statusClasses[$payment->status] ?? 'admin-badge-muted' }}">{{ $payment->status_name }}</span>
                    </div>
                    <div class="admin-detail-card-body">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="admin-detail-grid">
                                    <div class="admin-detail-item">
                                        <span class="label">رقم الدفعة</span>
                                        <span class="value">{{ $payment->payment_number }}</span>
                                    </div>
                                    <div class="admin-detail-item">
                                        <span class="label">تاريخ الدفع</span>
                                        <span class="value">{{ $payment->payment_date->format('Y-m-d') }}</span>
                                    </div>
                                    <div class="admin-detail-item">
                                        <span class="label">المبلغ</span>
                                        <span class="value text-success"><strong>{{ number_format($payment->amount, 2) }} ر.س</strong></span>
                                    </div>
                                    <div class="admin-detail-item">
                                        <span class="label">طريقة الدفع</span>
                                        <span class="value">{{ $payment->payment_method_name }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="admin-detail-grid">
                                    <div class="admin-detail-item">
                                        <span class="label">الطالب</span>
                                        <span class="value">{{ $payment->student->user->name ?? 'غير محدد' }}</span>
                                    </div>
                                    <div class="admin-detail-item">
                                        <span class="label">رقم القيد</span>
                                        <span class="value">{{ $payment->student->student_code }}</span>
                                    </div>
                                    @if ($payment->invoice)
                                        <div class="admin-detail-item">
                                            <span class="label">الفاتورة</span>
                                            <span class="value">
                                                <a href="{{ route('admin.invoices.show', $payment->invoice->id) }}" class="admin-user-link">
                                                    {{ $payment->invoice->invoice_number }}
                                                </a>
                                            </span>
                                        </div>
                                    @endif
                                    @if ($payment->receiver)
                                        <div class="admin-detail-item">
                                            <span class="label">استلم بواسطة</span>
                                            <span class="value">{{ $payment->receiver->name }}</span>
                                        </div>
                                    @endif
                                    @if ($payment->processed_at)
                                        <div class="admin-detail-item">
                                            <span class="label">تاريخ المعالجة</span>
                                            <span class="value">{{ $payment->processed_at->format('Y-m-d H:i') }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        @if ($payment->reference_number || $payment->bank_name || $payment->notes)
                            <div class="admin-detail-card mt-4">
                                <div class="admin-detail-card-head">
                                    <h3><i class="ri-information-line section-icon-sm"></i> معلومات إضافية</h3>
                                </div>
                                <div class="admin-detail-card-body">
                                    <div class="admin-detail-grid">
                                        @if ($payment->reference_number)
                                            <div class="admin-detail-item">
                                                <span class="label">رقم المرجع</span>
                                                <span class="value">{{ $payment->reference_number }}</span>
                                            </div>
                                        @endif
                                        @if ($payment->bank_name)
                                            <div class="admin-detail-item">
                                                <span class="label">اسم البنك</span>
                                                <span class="value">{{ $payment->bank_name }}</span>
                                            </div>
                                        @endif
                                        @if ($payment->notes)
                                            <div class="admin-detail-item">
                                                <span class="label">ملاحظات</span>
                                                <span class="value">{{ $payment->notes }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>
@stop

@push('styles')
<style>
@media print {
    .admin-page-header, .admin-btn, nav, aside {
        display: none !important;
    }
    #paymentCard {
        border: none;
        box-shadow: none;
    }
}
</style>
@endpush
