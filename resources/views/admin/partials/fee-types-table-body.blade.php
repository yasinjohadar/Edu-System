@php
    $periodLabels = [
        'monthly' => 'شهري',
        'quarterly' => 'ربع سنوي',
        'semester' => 'فصلي',
        'yearly' => 'سنوي',
    ];
@endphp

@forelse ($feeTypes as $feeType)
    <tr>
        <th scope="row" class="row-number">{{ $feeTypes->firstItem() + $loop->index }}</th>
        <td><span class="admin-badge admin-badge-muted">{{ $feeType->code }}</span></td>
        <td>
            <strong class="admin-user-link d-block">{{ $feeType->name }}</strong>
            @if ($feeType->name_en)
                <small class="text-muted">{{ $feeType->name_en }}</small>
            @endif
        </td>
        <td><span class="admin-badge admin-badge-role">{{ $feeType->category_name }}</span></td>
        <td><span class="admin-badge admin-badge-success">{{ number_format($feeType->default_amount, 2) }} ر.س</span></td>
        <td>
            @if ($feeType->is_recurring)
                <span class="admin-badge admin-badge-role">
                    نعم — {{ $periodLabels[$feeType->recurring_period] ?? $feeType->recurring_period }}
                </span>
            @else
                <span class="admin-badge admin-badge-muted">لا</span>
            @endif
        </td>
        <td>
            @if ($feeType->is_active)
                <span class="admin-badge admin-badge-success">نشط</span>
            @else
                <span class="admin-badge admin-badge-danger">غير نشط</span>
            @endif
        </td>
        <td>
            <div class="admin-action-group">
                @can('fee-type-edit')
                    <a href="{{ route('admin.fee-types.edit', $feeType->id) }}" class="admin-action-btn admin-action-edit" title="تعديل">
                        <i class="ri-edit-line"></i>
                    </a>
                @endcan
                @can('fee-type-delete')
                    <button type="button" class="admin-action-btn admin-action-delete" title="حذف"
                            data-delete-url="{{ route('admin.fee-types.destroy', $feeType->id) }}"
                            data-delete-message="هل أنت متأكد من رغبتك في حذف نوع الرسوم <strong>{{ $feeType->name }}</strong>؟">
                        <i class="ri-delete-bin-line"></i>
                    </button>
                @endcan
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="8">
            <div class="admin-empty-state">
                <i class="ri-money-dollar-circle-line"></i>
                لا توجد أنواع رسوم
            </div>
        </td>
    </tr>
@endforelse
