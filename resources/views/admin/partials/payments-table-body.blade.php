@php
    $statusClasses = [
        'completed' => 'admin-badge-success',
        'pending' => 'admin-badge-warning',
        'failed' => 'admin-badge-danger',
        'refunded' => 'admin-badge-muted',
    ];
@endphp

@forelse ($payments as $payment)
    <tr>
        <th scope="row" class="row-number">{{ $payments->firstItem() + $loop->index }}</th>
        <td><span class="admin-badge admin-badge-muted">{{ $payment->payment_number }}</span></td>
        <td>
            <strong class="admin-user-link d-block">{{ $payment->student->user->name ?? 'غير محدد' }}</strong>
            <small class="text-muted">{{ $payment->student->student_code }}</small>
        </td>
        <td>
            @if ($payment->invoice)
                <a href="{{ route('admin.invoices.show', $payment->invoice->id) }}" class="admin-user-link">
                    {{ $payment->invoice->invoice_number }}
                </a>
            @else
                <span class="text-muted">—</span>
            @endif
        </td>
        <td>{{ $payment->payment_date->format('Y-m-d') }}</td>
        <td><span class="admin-badge admin-badge-success">{{ number_format($payment->amount, 2) }} ر.س</span></td>
        <td>{{ $payment->payment_method_name }}</td>
        <td>
            <span class="admin-badge {{ $statusClasses[$payment->status] ?? 'admin-badge-muted' }}">
                {{ $payment->status_name }}
            </span>
        </td>
        <td>
            <div class="admin-action-group">
                <a href="{{ route('admin.payments.show', $payment->id) }}" class="admin-action-btn admin-action-view" title="عرض">
                    <i class="ri-eye-line"></i>
                </a>
                @can('payment-edit')
                    @if ($payment->status != 'refunded')
                        <a href="{{ route('admin.payments.edit', $payment->id) }}" class="admin-action-btn admin-action-edit" title="تعديل">
                            <i class="ri-edit-line"></i>
                        </a>
                    @endif
                @endcan
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="9">
            <div class="admin-empty-state">
                <i class="ri-wallet-3-line"></i>
                لا توجد مدفوعات
            </div>
        </td>
    </tr>
@endforelse
