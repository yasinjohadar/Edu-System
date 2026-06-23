@forelse ($subjects as $subject)
    <tr>
        <th scope="row" class="row-number">{{ $subjects->firstItem() + $loop->index }}</th>
        <td>
            <div class="admin-grade-name">
                <strong class="admin-user-link d-block mb-0">{{ $subject->name }}</strong>
                @if ($subject->name_en)
                    <small class="text-muted">{{ $subject->name_en }}</small>
                @endif
            </div>
        </td>
        <td>
            @if ($subject->code)
                <span class="admin-badge admin-badge-muted">{{ $subject->code }}</span>
            @else
                <span class="text-muted">—</span>
            @endif
        </td>
        <td>
            @if ($subject->type === 'required')
                <span class="admin-badge admin-badge-role">إجباري</span>
            @else
                <span class="admin-badge admin-badge-muted">اختياري</span>
            @endif
        </td>
        <td>{{ $subject->weekly_hours ?? '—' }}</td>
        <td>{{ $subject->full_marks ?? '—' }}</td>
        <td>
            @if ($subject->is_active)
                <span class="admin-badge admin-badge-success">نشط</span>
            @else
                <span class="admin-badge admin-badge-danger">غير نشط</span>
            @endif
        </td>
        <td>
            <div class="admin-action-group">
                <a href="{{ route('admin.subjects.edit', $subject->id) }}" class="admin-action-btn admin-action-edit" title="تعديل">
                    <i class="ri-edit-line"></i>
                </a>
                <button type="button" class="admin-action-btn admin-action-delete" title="حذف"
                        data-delete-url="{{ route('admin.subjects.destroy', $subject->id) }}"
                        data-delete-message="هل أنت متأكد من رغبتك في حذف المادة <strong>{{ $subject->name }}</strong>؟">
                    <i class="ri-delete-bin-line"></i>
                </button>
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="8">
            <div class="admin-empty-state">
                <i class="ri-book-open-line"></i>
                لا توجد مواد دراسية
            </div>
        </td>
    </tr>
@endforelse
