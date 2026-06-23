@forelse ($sections as $section)
    <tr>
        <th scope="row" class="row-number">{{ $sections->firstItem() + $loop->index }}</th>
        <td>
            <div class="admin-grade-name">
                <strong class="admin-user-link d-block mb-0">{{ $section->name }}</strong>
                @if ($section->name_en)
                    <small class="text-muted">{{ $section->name_en }}</small>
                @endif
            </div>
        </td>
        <td>
            @if ($section->class)
                <span class="admin-badge admin-badge-role">{{ $section->class->grade->name ?? '' }} — {{ $section->class->name }}</span>
            @else
                <span class="text-muted">—</span>
            @endif
        </td>
        <td><span class="admin-badge admin-badge-muted">{{ $section->capacity }}</span></td>
        <td>{{ $section->current_students ?? 0 }}</td>
        <td>{{ $section->classTeacher->name ?? '—' }}</td>
        <td>
            @if ($section->is_active)
                <span class="admin-badge admin-badge-success">نشط</span>
            @else
                <span class="admin-badge admin-badge-danger">غير نشط</span>
            @endif
        </td>
        <td>
            <div class="admin-action-group">
                <a href="{{ route('admin.sections.edit', $section->id) }}" class="admin-action-btn admin-action-edit" title="تعديل">
                    <i class="ri-edit-line"></i>
                </a>
                <button type="button" class="admin-action-btn admin-action-delete" title="حذف"
                        data-delete-url="{{ route('admin.sections.destroy', $section->id) }}"
                        data-delete-message="هل أنت متأكد من رغبتك في حذف الفصل <strong>{{ $section->name }}</strong>؟">
                    <i class="ri-delete-bin-line"></i>
                </button>
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="8">
            <div class="admin-empty-state">
                <i class="ri-group-line"></i>
                لا توجد فصول دراسية
            </div>
        </td>
    </tr>
@endforelse
