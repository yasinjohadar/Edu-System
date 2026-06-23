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

@forelse ($invoices as $invoice)
    <tr>
        <th scope="row" class="row-number">{{ $invoices->firstItem() + $loop->index }}</th>
        <td><span class="admin-badge admin-badge-muted">{{ $invoice->invoice_number }}</span></td>
        <td>
            <strong class="admin-user-link d-block">{{ $invoice->student->user->name ?? 'غير محدد' }}</strong>
            <small class="text-muted">{{ $invoice->student->student_code }}</small>
        </td>
        <td>{{ $invoice->invoice_date->format('Y-m-d') }}</td>
        <td>
            {{ $invoice->due_date->format('Y-m-d') }}
            @if ($invoice->isOverdue())
                <br><small class="text-danger">متأخرة</small>
            @endif
        </td>
        <td><span class="admin-badge admin-badge-role">{{ number_format($invoice->total_amount, 2) }} ر.س</span></td>
        <td><span class="admin-badge admin-badge-success">{{ number_format($invoice->paid_amount, 2) }} ر.س</span></td>
        <td><span class="admin-badge admin-badge-danger">{{ number_format($invoice->remaining_amount, 2) }} ر.س</span></td>
        <td>
            <span class="admin-badge {{ $statusClasses[$invoice->status] ?? 'admin-badge-muted' }}">
                {{ $invoice->status_name }}
            </span>
        </td>
        <td>
            <div class="admin-action-group">
                <a href="{{ route('admin.invoices.show', $invoice->id) }}" class="admin-action-btn admin-action-view" title="عرض">
                    <i class="ri-eye-line"></i>
                </a>
                @can('invoice-edit')
                    @if ($invoice->status != 'paid' && $invoice->status != 'cancelled')
                        <a href="{{ route('admin.invoices.edit', $invoice->id) }}" class="admin-action-btn admin-action-edit" title="تعديل">
                            <i class="ri-edit-line"></i>
                        </a>
                    @endif
                @endcan
                @can('invoice-delete')
                    @if ($invoice->status != 'paid' && $invoice->payments_count == 0)
                        <button type="button" class="admin-action-btn admin-action-delete" title="حذف"
                                data-delete-url="{{ route('admin.invoices.destroy', $invoice->id) }}"
                                data-delete-message="هل أنت متأكد من رغبتك في حذف الفاتورة <strong>{{ $invoice->invoice_number }}</strong>؟">
                            <i class="ri-delete-bin-line"></i>
                        </button>
                    @endif
                @endcan
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="10">
            <div class="admin-empty-state">
                <i class="ri-file-list-3-line"></i>
                لا توجد فواتير
            </div>
        </td>
    </tr>
@endforelse
