@extends('admin.layouts.master')

@section('page-title')
    تفاصيل الحساب المالي
@stop

@section('content')
    @php
        $invoiceStatusClasses = [
            'paid' => 'admin-badge-success',
            'overdue' => 'admin-badge-danger',
            'partial' => 'admin-badge-warning',
            'pending' => 'admin-badge-role',
            'draft' => 'admin-badge-muted',
            'cancelled' => 'admin-badge-danger',
        ];
        $paymentStatusClasses = [
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
                    <h1>تفاصيل الحساب المالي</h1>
                    <p>{{ $account->account_number }} — {{ $account->student->user->name ?? 'غير محدد' }}</p>
                </div>
                <a href="{{ route('admin.financial-accounts.index') }}" class="admin-btn admin-btn-secondary">
                    <i class="ri-arrow-right-line"></i>
                    العودة للقائمة
                </a>
            </div>

            <div class="admin-page-card mb-3">
                <div class="admin-detail-card">
                    <div class="admin-detail-card-head">
                        <h3><i class="ri-bank-line section-icon-sm"></i> ملخص الحساب</h3>
                    </div>
                    <div class="admin-detail-card-body">
                        <div class="admin-detail-grid">
                            <div class="admin-detail-item">
                                <span class="label">رقم الحساب</span>
                                <span class="value">{{ $account->account_number }}</span>
                            </div>
                            <div class="admin-detail-item">
                                <span class="label">الطالب</span>
                                <span class="value">{{ $account->student->user->name ?? 'غير محدد' }} ({{ $account->student->student_code }})</span>
                            </div>
                            <div class="admin-detail-item">
                                <span class="label">إجمالي الفواتير</span>
                                <span class="value">{{ number_format($account->total_invoiced, 2) }} ر.س</span>
                            </div>
                            <div class="admin-detail-item">
                                <span class="label">إجمالي المدفوعات</span>
                                <span class="value text-success">{{ number_format($account->total_paid, 2) }} ر.س</span>
                            </div>
                            <div class="admin-detail-item">
                                <span class="label">المستحقات</span>
                                <span class="value text-danger">{{ number_format($account->total_due, 2) }} ر.س</span>
                            </div>
                            <div class="admin-detail-item">
                                <span class="label">الرصيد</span>
                                <span class="value {{ $account->balance >= 0 ? 'text-success' : 'text-danger' }}">
                                    {{ number_format($account->balance, 2) }} ر.س
                                </span>
                            </div>
                            <div class="admin-detail-item">
                                <span class="label">آخر معاملة</span>
                                <span class="value">{{ $account->last_transaction_date ? $account->last_transaction_date->format('Y-m-d') : '—' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="admin-page-card mb-3">
                <div class="admin-detail-card">
                    <div class="admin-detail-card-head">
                        <h3><i class="ri-file-list-3-line section-icon-sm"></i> الفواتير ({{ $account->invoices->count() }})</h3>
                    </div>
                    <div class="admin-table-wrap">
                        <div class="table-responsive">
                            <table class="admin-data-table">
                                <thead>
                                    <tr>
                                        <th>رقم الفاتورة</th>
                                        <th>التاريخ</th>
                                        <th>الاستحقاق</th>
                                        <th>الإجمالي</th>
                                        <th>المدفوع</th>
                                        <th>المتبقي</th>
                                        <th>الحالة</th>
                                        <th>العمليات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($account->invoices as $invoice)
                                        <tr>
                                            <td><span class="admin-badge admin-badge-muted">{{ $invoice->invoice_number }}</span></td>
                                            <td>{{ $invoice->invoice_date->format('Y-m-d') }}</td>
                                            <td>{{ $invoice->due_date->format('Y-m-d') }}</td>
                                            <td>{{ number_format($invoice->total_amount, 2) }} ر.س</td>
                                            <td class="text-success">{{ number_format($invoice->paid_amount, 2) }} ر.س</td>
                                            <td class="text-danger">{{ number_format($invoice->remaining_amount, 2) }} ر.س</td>
                                            <td>
                                                <span class="admin-badge {{ $invoiceStatusClasses[$invoice->status] ?? 'admin-badge-muted' }}">
                                                    {{ $invoice->status_name }}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.invoices.show', $invoice->id) }}" class="admin-action-btn admin-action-view" title="عرض">
                                                    <i class="ri-eye-line"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8">
                                                <div class="admin-empty-state">لا توجد فواتير</div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="admin-page-card">
                <div class="admin-detail-card">
                    <div class="admin-detail-card-head">
                        <h3><i class="ri-wallet-3-line section-icon-sm"></i> المدفوعات ({{ $account->payments->count() }})</h3>
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
                                        <th>الفاتورة</th>
                                        <th>الحالة</th>
                                        <th>العمليات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($account->payments as $payment)
                                        <tr>
                                            <td><span class="admin-badge admin-badge-muted">{{ $payment->payment_number }}</span></td>
                                            <td>{{ $payment->payment_date->format('Y-m-d') }}</td>
                                            <td class="text-success"><strong>{{ number_format($payment->amount, 2) }} ر.س</strong></td>
                                            <td>{{ $payment->payment_method_name }}</td>
                                            <td>
                                                @if ($payment->invoice)
                                                    <a href="{{ route('admin.invoices.show', $payment->invoice->id) }}" class="admin-user-link">
                                                        {{ $payment->invoice->invoice_number }}
                                                    </a>
                                                @else
                                                    <span class="text-muted">—</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="admin-badge {{ $paymentStatusClasses[$payment->status] ?? 'admin-badge-muted' }}">
                                                    {{ $payment->status_name }}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.payments.show', $payment->id) }}" class="admin-action-btn admin-action-view" title="عرض">
                                                    <i class="ri-eye-line"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7">
                                                <div class="admin-empty-state">لا توجد مدفوعات</div>
                                            </td>
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
@stop
