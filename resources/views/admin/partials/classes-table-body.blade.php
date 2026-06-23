@forelse ($classes as $class)
    <tr>
        <th scope="row" class="row-number">{{ $classes->firstItem() + $loop->index }}</th>
        <td>
            <div class="admin-grade-name">
                <strong class="admin-user-link d-block mb-0">{{ $class->name }}</strong>
                @if ($class->name_en)
                    <small class="text-muted">{{ $class->name_en }}</small>
                @endif
            </div>
        </td>
        <td>
            @if ($class->grade)
                <span class="admin-badge admin-badge-role">{{ $class->grade->name }}</span>
            @else
                <span class="text-muted">—</span>
            @endif
        </td>
        <td><span class="admin-badge admin-badge-muted">{{ $class->order }}</span></td>
        <td>
            @if ($class->is_active)
                <span class="admin-badge admin-badge-success">نشط</span>
            @else
                <span class="admin-badge admin-badge-danger">غير نشط</span>
            @endif
        </td>
        <td>
            <div class="admin-action-group">
                <a href="{{ route('admin.classes.edit', $class->id) }}" class="admin-action-btn admin-action-edit" title="تعديل">
                    <i class="ri-edit-line"></i>
                </a>
                <button type="button" class="admin-action-btn admin-action-delete" title="حذف"
                        data-delete-url="{{ route('admin.classes.destroy', $class->id) }}"
                        data-delete-message="هل أنت متأكد من رغبتك في حذف الصف <strong>{{ $class->name }}</strong>؟">
                    <i class="ri-delete-bin-line"></i>
                </button>
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="6">
            <div class="admin-empty-state">
                <i class="ri-book-2-line"></i>
                لا توجد صفوف دراسية
            </div>
        </td>
    </tr>
@endforelse
