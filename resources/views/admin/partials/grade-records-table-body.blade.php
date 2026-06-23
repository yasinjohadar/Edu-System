@forelse ($gradeRecords as $record)
    @php
        $pct = (float) $record->percentage;
        $pctBadge = $pct >= 80 ? 'admin-badge-success' : ($pct >= 50 ? 'admin-badge-warning' : 'admin-badge-danger');
        $gradeBadge = $record->grade === 'F' ? 'admin-badge-danger' : ($pct >= 80 ? 'admin-badge-success' : 'admin-badge-warning');
    @endphp
    <tr>
        <th scope="row" class="row-number">{{ $gradeRecords->firstItem() + $loop->index }}</th>
        <td>
            <strong class="admin-user-link d-block">{{ $record->student->user->name ?? 'غير محدد' }}</strong>
            <small class="text-muted">{{ $record->student->student_code }}</small>
        </td>
        <td>{{ $record->subject->name }}</td>
        <td><span class="admin-badge admin-badge-role">{{ $record->exam_type_name }}</span></td>
        <td>{{ $record->exam_name }}</td>
        <td>
            <strong>{{ number_format($record->marks_obtained, 2) }}</strong>
            <span class="text-muted">/ {{ number_format($record->total_marks, 2) }}</span>
        </td>
        <td><span class="admin-badge {{ $pctBadge }}">{{ number_format($pct, 2) }}%</span></td>
        <td><span class="admin-badge {{ $gradeBadge }}">{{ $record->grade }}</span></td>
        <td>{{ $record->exam_date->format('Y-m-d') }}</td>
        <td>{{ $record->academic_year }}</td>
        <td>
            @if ($record->is_published)
                <span class="admin-badge admin-badge-success">منشور</span>
            @else
                <span class="admin-badge admin-badge-warning">مسودة</span>
            @endif
        </td>
        <td>
            <div class="admin-action-group">
                @can('grade-edit')
                    <a href="{{ route('admin.grade-records.edit', $record->id) }}" class="admin-action-btn admin-action-edit" title="تعديل">
                        <i class="ri-edit-line"></i>
                    </a>
                @endcan
                @can('grade-delete')
                    <button type="button" class="admin-action-btn admin-action-delete" title="حذف"
                            data-delete-url="{{ route('admin.grade-records.destroy', $record->id) }}"
                            data-delete-message="هل أنت متأكد من رغبتك في حذف الدرجة للطالب <strong>{{ $record->student->user->name }}</strong> في <strong>{{ $record->subject->name }}</strong>؟">
                        <i class="ri-delete-bin-line"></i>
                    </button>
                @endcan
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="12">
            <div class="admin-empty-state">
                <i class="ri-bar-chart-box-line"></i>
                لا توجد درجات مسجلة
            </div>
        </td>
    </tr>
@endforelse
