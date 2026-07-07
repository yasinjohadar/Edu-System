@php
    $statusClasses = [
        'published' => 'admin-badge-success',
        'closed' => 'admin-badge-danger',
        'draft' => 'admin-badge-muted',
    ];
@endphp

@forelse ($assignments as $assignment)
    <tr>
        <th scope="row" class="row-number">{{ $assignments->firstItem() + $loop->index }}</th>
        <td><span class="admin-badge admin-badge-muted">{{ $assignment->assignment_number }}</span></td>
        <td>
            <strong class="d-block">{{ $assignment->title }}</strong>
            @if ($assignment->description)
                <small class="text-muted">{{ Str::limit($assignment->description, 50) }}</small>
            @endif
        </td>
        <td>{{ $assignment->subject->name ?? 'غير محدد' }}</td>
        <td>{{ $assignment->teacher->user->name ?? 'غير محدد' }}</td>
        <td>{{ $assignment->section->name ?? 'كل الفصول' }}</td>
        <td><strong>{{ number_format($assignment->total_marks, 2) }}</strong></td>
        <td>
            {{ $assignment->due_date->format('Y-m-d') }}
            @if ($assignment->isOverdue())
                <br><small class="text-danger">متأخر</small>
            @endif
        </td>
        <td>
            <span class="admin-badge {{ $statusClasses[$assignment->status] ?? 'admin-badge-muted' }}">
                {{ $assignment->status_name }}
            </span>
            @if (! $assignment->is_active)
                <br><small class="text-muted">غير نشط</small>
            @endif
        </td>
        <td>
            <span class="admin-badge admin-badge-role">{{ $assignment->submissions()->count() }}</span>
        </td>
        <td>
            <div class="admin-action-group">
                <a href="{{ route('admin.assignments.show', $assignment->id) }}" class="admin-action-btn admin-action-view" title="عرض">
                    <i class="ri-eye-line"></i>
                </a>
                <a href="{{ route('admin.assignments.submissions.index', $assignment->id) }}" class="admin-action-btn admin-action-edit" title="التسليمات">
                    <i class="ri-upload-2-line"></i>
                </a>
                @can('assignment-edit')
                    <a href="{{ route('admin.assignments.edit', $assignment->id) }}" class="admin-action-btn admin-action-key" title="تعديل">
                        <i class="ri-edit-line"></i>
                    </a>
                    @if ($assignment->status == 'draft')
                        <form action="{{ route('admin.assignments.publish', $assignment->id) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="admin-action-btn admin-action-view" title="نشر">
                                <i class="ri-check-line"></i>
                            </button>
                        </form>
                    @endif
                    @if ($assignment->status == 'published')
                        <form action="{{ route('admin.assignments.close', $assignment->id) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="admin-action-btn admin-action-delete" title="إغلاق">
                                <i class="ri-lock-line"></i>
                            </button>
                        </form>
                    @endif
                @endcan
                @can('assignment-delete')
                    @if ($assignment->submissions()->count() == 0)
                        <button type="button" class="admin-action-btn admin-action-delete"
                                data-delete-url="{{ route('admin.assignments.destroy', $assignment->id) }}"
                                data-delete-message="هل أنت متأكد من رغبتك في حذف الواجب <strong>{{ $assignment->title }}</strong>؟"
                                title="حذف">
                            <i class="ri-delete-bin-line"></i>
                        </button>
                    @endif
                @endcan
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="11">
            <div class="admin-empty-state">
                <i class="ri-file-text-line"></i>
                لا توجد واجبات
            </div>
        </td>
    </tr>
@endforelse
