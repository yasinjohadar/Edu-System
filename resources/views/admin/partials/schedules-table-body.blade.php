@forelse ($schedules as $schedule)
    <tr>
        <th scope="row" class="row-number">{{ $schedules->firstItem() + $loop->index }}</th>
        <td>
            <span class="admin-badge admin-badge-role">{{ $schedule->section->class->name }} - {{ $schedule->section->name }}</span>
        </td>
        <td>{{ $schedule->subject->name }}</td>
        <td>{{ $schedule->teacher->user->name }}</td>
        <td>
            <span class="admin-badge admin-badge-role">{{ $schedule->day_name }}</span>
        </td>
        <td>{{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }}</td>
        <td>{{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}</td>
        <td>{{ $schedule->room ?? '—' }}</td>
        <td>
            @if ($schedule->is_active)
                <span class="admin-badge admin-badge-success">نشط</span>
            @else
                <span class="admin-badge admin-badge-danger">غير نشط</span>
            @endif
        </td>
        <td>
            <div class="admin-action-group">
                @can('schedule-edit')
                <a href="{{ route('admin.schedules.edit', $schedule->id) }}" class="admin-action-btn admin-action-edit" title="تعديل">
                    <i class="ri-edit-line"></i>
                </a>
                @endcan
                @can('schedule-delete')
                <button type="button" class="admin-action-btn admin-action-delete" title="حذف"
                        data-delete-url="{{ route('admin.schedules.destroy', $schedule->id) }}"
                        data-delete-message="هل أنت متأكد من رغبتك في حذف الجدول الدراسي <strong>{{ $schedule->subject->name }}</strong> للفصل <strong>{{ $schedule->section->name }}</strong>؟">
                    <i class="ri-delete-bin-line"></i>
                </button>
                @endcan
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="10">
            <div class="admin-empty-state">
                <i class="ri-calendar-schedule-line"></i>
                لا توجد جداول دراسية
            </div>
        </td>
    </tr>
@endforelse
