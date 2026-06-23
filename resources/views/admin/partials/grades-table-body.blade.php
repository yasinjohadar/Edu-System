@forelse ($grades as $grade)
    <tr>
        <th scope="row" class="row-number">{{ $grades->firstItem() + $loop->index }}</th>
        <td>
            <div class="admin-grade-name">
                <strong class="admin-user-link d-block mb-0">{{ $grade->name }}</strong>
                @if ($grade->name_en)
                    <small class="text-muted">{{ $grade->name_en }}</small>
                @endif
            </div>
        </td>
        <td>{{ $grade->min_age ?? '—' }}</td>
        <td>{{ $grade->max_age ?? '—' }}</td>
        <td>
            @if ($grade->fees)
                <span class="admin-badge admin-badge-role">{{ number_format($grade->fees, 2) }} ر.س</span>
            @else
                <span class="text-muted">—</span>
            @endif
        </td>
        <td><span class="admin-badge admin-badge-muted">{{ $grade->order }}</span></td>
        <td>
            @if ($grade->is_active)
                <span class="admin-badge admin-badge-success">نشط</span>
            @else
                <span class="admin-badge admin-badge-danger">غير نشط</span>
            @endif
        </td>
        <td>
            <div class="admin-action-group">
                <a href="{{ route('admin.grades.edit', $grade->id) }}" class="admin-action-btn admin-action-edit" title="تعديل">
                    <i class="ri-edit-line"></i>
                </a>
                <button type="button" class="admin-action-btn admin-action-delete" title="حذف"
                        data-delete-url="{{ route('admin.grades.destroy', $grade->id) }}"
                        data-delete-message="هل أنت متأكد من رغبتك في حذف المرحلة <strong>{{ $grade->name }}</strong>؟">
                    <i class="ri-delete-bin-line"></i>
                </button>
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="8">
            <div class="admin-empty-state">
                <i class="ri-graduation-cap-line"></i>
                لا توجد مراحل تعليمية
            </div>
        </td>
    </tr>
@endforelse
