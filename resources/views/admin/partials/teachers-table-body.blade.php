@forelse ($teachers as $teacher)
    <tr>
        <th scope="row" class="row-number">{{ $teachers->firstItem() + $loop->index }}</th>
        <td>
            <div class="admin-user-cell">
                @if ($teacher->photo)
                    <img src="{{ asset('storage/' . $teacher->photo) }}" alt="{{ $teacher->user->name }}" class="admin-avatar-initial" style="object-fit: cover; padding: 0;">
                @else
                    <span class="admin-avatar-initial">{{ mb_substr($teacher->user->name, 0, 1) }}</span>
                @endif
                <div>
                    <strong class="admin-user-link d-block mb-0">{{ $teacher->user->name }}</strong>
                    @if ($teacher->user->phone)
                        <small class="text-muted">{{ $teacher->user->phone }}</small>
                    @endif
                </div>
            </div>
        </td>
        <td>
            @if ($teacher->user->email)
                <div class="admin-email-cell">
                    <a href="mailto:{{ $teacher->user->email }}" class="admin-email-link">{{ $teacher->user->email }}</a>
                    <button type="button" class="admin-copy-btn" data-copy-email="{{ $teacher->user->email }}" title="نسخ البريد">
                        <i class="ri-file-copy-line"></i>
                    </button>
                </div>
            @else
                <span class="text-muted">—</span>
            @endif
        </td>
        <td><span class="admin-badge admin-badge-muted">{{ $teacher->teacher_code }}</span></td>
        <td>{{ $teacher->specialization ?? '—' }}</td>
        <td>
            @if ($teacher->subjects->count() > 0)
                <span class="admin-badge admin-badge-role">{{ $teacher->subjects->count() }} مادة</span>
            @else
                <span class="text-muted">—</span>
            @endif
        </td>
        <td>
            @if ($teacher->status === 'active')
                <span class="admin-badge admin-badge-success">نشط</span>
            @elseif ($teacher->status === 'inactive')
                <span class="admin-badge admin-badge-danger">غير نشط</span>
            @elseif ($teacher->status === 'on_leave')
                <span class="admin-badge admin-badge-warning">في إجازة</span>
            @else
                <span class="admin-badge admin-badge-muted">استقال</span>
            @endif
        </td>
        <td>
            <div class="admin-action-group">
                <a href="{{ route('admin.teachers.show', $teacher->id) }}" class="admin-action-btn admin-action-view" title="عرض">
                    <i class="ri-eye-line"></i>
                </a>
                <a href="{{ route('admin.teachers.edit', $teacher->id) }}" class="admin-action-btn admin-action-edit" title="تعديل">
                    <i class="ri-edit-line"></i>
                </a>
                <button type="button" class="admin-action-btn admin-action-delete" title="حذف"
                        data-delete-url="{{ route('admin.teachers.destroy', $teacher->id) }}"
                        data-delete-message="هل أنت متأكد من رغبتك في حذف المعلم <strong>{{ $teacher->user->name }}</strong>؟">
                    <i class="ri-delete-bin-line"></i>
                </button>
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="8">
            <div class="admin-empty-state">
                <i class="ri-user-star-line"></i>
                لا يوجد معلمون
            </div>
        </td>
    </tr>
@endforelse
