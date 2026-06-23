@extends('admin.layouts.master')

@section('page-title')
    عرض الفاتورة
@stop

@section('content')
    @php
        $statusClasses = [
            'paid' => 'admin-badge-success',
            'overdue' => 'admin-badge-danger',
            'partial' => 'admin-badge-warning',
            'pending' => 'admin-badge-role',
            'draft' => 'admin-badge-muted',
            'cancelled' => 'admin-badge-danger',
        ];
    @endphp

    <div class="main-content app-content">
        <div class="container-fluid">

            <div class="admin-page-header">
                <div class="page-title-wrap">
                    <h1>عرض الفاتورة</h1>
                    <p>{{ $invoice->invoice_number }} — {{ $invoice->student->user->name ?? 'غير محدد' }}</p>
                </div>
                <div class="admin-page-header-actions">
                    @can('invoice-edit')
                        @if ($invoice->status != 'paid' && $invoice->status != 'cancelled')
                            <a href="{{ route('admin.invoices.edit', $invoice->id) }}" class="admin-btn admin-btn-primary">
                                <i class="ri-edit-line"></i>
                                تعديل
                            </a>
                        @endif
                    @endcan
                    <button type="button" onclick="window.print()" class="admin-btn admin-btn-secondary">
                        <i class="ri-printer-line"></i>
                        طباعة
                    </button>
                    <a href="{{ route('admin.invoices.index') }}" class="admin-btn admin-btn-secondary">
                        <i class="ri-arrow-right-line"></i>
                        العودة
                    </a>
                </div>
            </div>

            <div class="admin-page-card" id="invoiceCard">
                <div class="admin-detail-card">
                    <div class="admin-detail-card-head">
                        <h3><i class="ri-file-list-3-line section-icon-sm"></i> تفاصيل الفاتورة</h3>
                        <span class="admin-badge {{ $statusClasses[$invoice->status] ?? 'admin-badge-muted' }}">{{ $invoice->status_name }}</span>
                    </div>
                    <div class="admin-detail-card-body">
                        <div class="row g-4 mb-4">
                            <div class="col-md-6">
                                <div class="admin-detail-grid">
                                    <div class="admin-detail-item">
                                        <span class="label">رقم الفاتورة</span>
                                        <span class="value">{{ $invoice->invoice_number }}</span>
                                    </div>
                                    <div class="admin-detail-item">
                                        <span class="label">تاريخ الفاتورة</span>
                                        <span class="value">{{ $invoice->invoice_date->format('Y-m-d') }}</span>
                                    </div>
                                    <div class="admin-detail-item">
                                        <span class="label">تاريخ الاستحقاق</span>
                                        <span class="value">{{ $invoice->due_date->format('Y-m-d') }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="admin-detail-grid">
                                    <div class="admin-detail-item">
                                        <span class="label">الطالب</span>
                                        <span class="value">{{ $invoice->student->user->name ?? 'غير محدد' }}</span>
                                    </div>
                                    <div class="admin-detail-item">
                                        <span class="label">رقم القيد</span>
                                        <span class="value">{{ $invoice->student->student_code }}</span>
                                    </div>
                                    @if ($invoice->student->class)
                                        <div class="admin-detail-item">
                                            <span class="label">الصف</span>
                                            <span class="value">{{ $invoice->student->class->name }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="admin-table-wrap mb-4">
                            <div class="table-responsive">
                                <table class="admin-data-table">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>البند</th>
                                            <th>الوصف</th>
                                            <th>الكمية</th>
                                            <th>سعر الوحدة</th>
                                            <th>الخصم</th>
                                            <th>الضريبة</th>
                                            <th>الإجمالي</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($invoice->items as $item)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>
                                                    <strong>{{ $item->item_name }}</strong>
                                                    @if ($item->feeType)
                                                        <br><small class="text-muted">{{ $item->feeType->name }}</small>
                                                    @endif
                                                </td>
                                                <td>{{ $item->description ?? '—' }}</td>
                                                <td>{{ $item->quantity }}</td>
                                                <td>{{ number_format($item->unit_price, 2) }} ر.س</td>
                                                <td>{{ number_format($item->discount, 2) }} ر.س</td>
                                                <td>{{ number_format($item->tax, 2) }} ر.س</td>
                                                <td><strong>{{ number_format($item->total, 2) }} ر.س</strong></td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="row g-4">
                            <div class="col-md-6">
                                @if ($invoice->notes)
                                    <div class="admin-detail-item mb-3">
                                        <span class="label">ملاحظات</span>
                                        <span class="value">{{ $invoice->notes }}</span>
                                    </div>
                                @endif
                                @if ($invoice->terms)
                                    <div class="admin-detail-item">
                                        <span class="label">شروط الدفع</span>
                                        <span class="value">{{ $invoice->terms }}</span>
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <div class="admin-detail-grid">
                                    <div class="admin-detail-item">
                                        <span class="label">المجموع الفرعي</span>
                                        <span class="value">{{ number_format($invoice->subtotal, 2) }} ر.س</span>
                                    </div>
                                    @if ($invoice->discount_amount > 0)
                                        <div class="admin-detail-item">
                                            <span class="label">الخصم</span>
                                            <span class="value text-danger">- {{ number_format($invoice->discount_amount, 2) }} ر.س</span>
                                        </div>
                                    @endif
                                    @if ($invoice->tax_amount > 0)
                                        <div class="admin-detail-item">
                                            <span class="label">الضريبة</span>
                                            <span class="value">{{ number_format($invoice->tax_amount, 2) }} ر.س</span>
                                        </div>
                                    @endif
                                    <div class="admin-detail-item">
                                        <span class="label">المبلغ الإجمالي</span>
                                        <span class="value"><strong>{{ number_format($invoice->total_amount, 2) }} ر.س</strong></span>
                                    </div>
                                    <div class="admin-detail-item">
                                        <span class="label">المدفوع</span>
                                        <span class="value text-success">{{ number_format($invoice->paid_amount, 2) }} ر.س</span>
                                    </div>
                                    <div class="admin-detail-item">
                                        <span class="label">المتبقي</span>
                                        <span class="value text-danger"><strong>{{ number_format($invoice->remaining_amount, 2) }} ر.س</strong></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if ($invoice->payments->count() > 0)
                            <div class="admin-detail-card mt-4">
                                <div class="admin-detail-card-head">
                                    <h3><i class="ri-wallet-3-line section-icon-sm"></i> سجل المدفوعات</h3>
                                </div>
                                <div class="admin-table-wrap">
                                    <div class="table-responsive">
                                        <table class="admin-data-table">
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
                                                @foreach ($invoice->payments as $payment)
                                                    <tr>
                                                        <td>{{ $payment->payment_number }}</td>
                                                        <td>{{ $payment->payment_date->format('Y-m-d') }}</td>
                                                        <td><strong>{{ number_format($payment->amount, 2) }} ر.س</strong></td>
                                                        <td>{{ $payment->payment_method_name }}</td>
                                                        <td>
                                                            <span class="admin-badge {{ $payment->status == 'completed' ? 'admin-badge-success' : 'admin-badge-warning' }}">
                                                                {{ $payment->status_name }}
                                                            </span>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if ($invoice->remaining_amount > 0)
                            <div class="mt-4 text-center">
                                <a href="{{ route('admin.payments.create', ['invoice_id' => $invoice->id]) }}" class="admin-btn admin-btn-primary">
                                    <i class="ri-money-dollar-circle-line"></i>
                                    تسجيل دفعة جديدة
                                </a>
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
    #invoiceCard {
        border: none;
        box-shadow: none;
    }
}
</style>
@endpush

