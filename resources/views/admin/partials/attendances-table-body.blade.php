@forelse ($attendances as $attendance)
    @php
        $statusBadgeClass = match ($attendance->status) {
            'present' => 'admin-badge-success',
            'absent' => 'admin-badge-danger',
            'late' => 'admin-badge-warning',
            'excused' => 'admin-badge-role',
            default => 'admin-badge-muted',
        };
    @endphp
    <tr>
        <th scope="row" class="row-number">{{ $attendances->firstItem() + $loop->index }}</th>
        <td>
            <strong class="admin-user-link d-block mb-0">{{ $attendance->student->user->name }}</strong>
            <small class="text-muted">{{ $attendance->student->student_code }}</small>
        </td>
        <td>
            <span class="admin-badge admin-badge-role">
                {{ $attendance->section->class->grade->name }} - {{ $attendance->section->class->name }} - {{ $attendance->section->name }}
            </span>
        </td>
        <td>{{ $attendance->date->format('Y-m-d') }}</td>
        <td>
            <span class="admin-badge {{ $statusBadgeClass }}">{{ $attendance->status_label }}</span>
        </td>
        <td>
            @if ($attendance->check_in_time)
                {{ \Carbon\Carbon::parse($attendance->check_in_time)->format('H:i') }}
            @else
                <span class="text-muted">—</span>
            @endif
        </td>
        <td>
            @if ($attendance->notes)
                <span class="text-truncate d-inline-block" style="max-width: 150px;" title="{{ $attendance->notes }}">
                    {{ $attendance->notes }}
                </span>
            @else
                <span class="text-muted">—</span>
            @endif
        </td>
        <td>{{ $attendance->markedBy->name ?? '—' }}</td>
        <td>
            <div class="admin-action-group">
                <a href="{{ route('admin.attendances.edit', $attendance->id) }}" class="admin-action-btn admin-action-edit" title="تعديل">
                    <i class="ri-edit-line"></i>
                </a>
                <button type="button" class="admin-action-btn admin-action-delete" title="حذف"
                        data-delete-url="{{ route('admin.attendances.destroy', $attendance->id) }}"
                        data-delete-message="هل أنت متأكد من رغبتك في حذف سجل الحضور للطالب <strong>{{ $attendance->student->user->name }}</strong> في تاريخ <strong>{{ $attendance->date->format('Y-m-d') }}</strong>؟">
                    <i class="ri-delete-bin-line"></i>
                </button>
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="9">
            <div class="admin-empty-state">
                <i class="ri-calendar-check-line"></i>
                لا توجد سجلات حضور
            </div>
        </td>
    </tr>
@endforelse
