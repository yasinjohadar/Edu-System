@forelse ($students as $student)
    <tr>
        <th scope="row" class="row-number">{{ $students->firstItem() + $loop->index }}</th>
        <td>
            <div class="admin-user-cell">
                @if ($student->photo)
                    <img src="{{ asset('storage/' . $student->photo) }}" alt="{{ $student->user->name }}" class="admin-avatar-initial" style="object-fit: cover; padding: 0;">
                @else
                    <span class="admin-avatar-initial">{{ mb_substr($student->user->name, 0, 1) }}</span>
                @endif
                <div>
                    <strong class="admin-user-link d-block mb-0">{{ $student->user->name }}</strong>
                    @if ($student->user->phone)
                        <small class="text-muted">{{ $student->user->phone }}</small>
                    @endif
                </div>
            </div>
        </td>
        <td><span class="admin-badge admin-badge-muted">{{ $student->student_code }}</span></td>
        <td>
            @if ($student->user->email)
                <div class="admin-email-cell">
                    <a href="mailto:{{ $student->user->email }}" class="admin-email-link">{{ $student->user->email }}</a>
                    <button type="button" class="admin-copy-btn" data-copy-email="{{ $student->user->email }}" title="نسخ البريد">
                        <i class="ri-file-copy-line"></i>
                    </button>
                </div>
            @else
                <span class="text-muted">—</span>
            @endif
        </td>
        <td>
            @if ($student->class && $student->section)
                <span class="admin-badge admin-badge-role">{{ $student->class->grade->name }} - {{ $student->class->name }}</span>
                <span class="admin-badge admin-badge-muted">{{ $student->section->name }}</span>
            @else
                <span class="text-muted">غير مسجل</span>
            @endif
        </td>
        <td>
            @if ($student->parents->count() > 0)
                @foreach ($student->parents->take(2) as $parent)
                    <span class="admin-badge admin-badge-muted">{{ $parent->user->name }}</span>
                @endforeach
                @if ($student->parents->count() > 2)
                    <span class="admin-badge admin-badge-role">+{{ $student->parents->count() - 2 }}</span>
                @endif
            @else
                <span class="text-muted">—</span>
            @endif
        </td>
        <td>
            @if ($student->status === 'active')
                <span class="admin-badge admin-badge-success">نشط</span>
            @elseif ($student->status === 'graduated')
                <span class="admin-badge admin-badge-role">متخرج</span>
            @elseif ($student->status === 'transferred')
                <span class="admin-badge admin-badge-warning">منقول</span>
            @else
                <span class="admin-badge admin-badge-danger">معلق</span>
            @endif
        </td>
        <td>
            <div class="admin-action-group">
                <a href="{{ route('admin.students.show', $student->id) }}" class="admin-action-btn admin-action-view" title="عرض">
                    <i class="ri-eye-line"></i>
                </a>
                <a href="{{ route('admin.students.edit', $student->id) }}" class="admin-action-btn admin-action-edit" title="تعديل">
                    <i class="ri-edit-line"></i>
                </a>
                <button type="button" class="admin-action-btn admin-action-delete" title="حذف"
                        data-delete-url="{{ route('admin.students.destroy', $student->id) }}"
                        data-delete-message="هل أنت متأكد من رغبتك في حذف الطالب <strong>{{ $student->user->name }}</strong>؟">
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
                لا يوجد طلاب
            </div>
        </td>
    </tr>
@endforelse
