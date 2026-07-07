<div class="admin-report-stats admin-report-stats-4">
    <button type="button" class="admin-report-stat admin-report-stat-blue {{ !request('tier') ? 'is-active' : '' }}" data-grades-stat-filter="" title="عرض الكل">
        <span class="admin-report-stat-icon"><i class="ri-file-list-3-line"></i></span>
        <span class="admin-report-stat-label">إجمالي السجلات</span>
        <strong class="admin-report-stat-value">{{ $stats['total_records'] }}</strong>
    </button>
    <div class="admin-report-stat admin-report-stat-cyan admin-report-stat-static">
        <span class="admin-report-stat-icon"><i class="ri-percent-line"></i></span>
        <span class="admin-report-stat-label">المتوسط العام</span>
        <strong class="admin-report-stat-value">{{ number_format($stats['average_percentage'], 2) }}%</strong>
    </div>
    <button type="button" class="admin-report-stat admin-report-stat-green {{ request('tier') == 'excellent' ? 'is-active' : '' }}" data-grades-stat-filter="excellent" title="فلترة: ممتاز (90+)">
        <span class="admin-report-stat-icon"><i class="ri-star-smile-line"></i></span>
        <span class="admin-report-stat-label">ممتاز (90+)</span>
        <strong class="admin-report-stat-value">{{ $stats['excellent_count'] }}</strong>
    </button>
    <button type="button" class="admin-report-stat admin-report-stat-red {{ request('tier') == 'fail' ? 'is-active' : '' }}" data-grades-stat-filter="fail" title="فلترة: راسب (&lt;50)">
        <span class="admin-report-stat-icon"><i class="ri-error-warning-line"></i></span>
        <span class="admin-report-stat-label">راسب (&lt;50)</span>
        <strong class="admin-report-stat-value">{{ $stats['fail_count'] }}</strong>
    </button>
</div>

<div class="admin-report-section">
    <div class="admin-report-section-head">
        <h3><i class="ri-bar-chart-box-line"></i> سجلات الدرجات</h3>
        <span class="admin-badge admin-badge-muted">{{ $gradeRecords->count() }}</span>
    </div>
    <div class="admin-table-wrap">
        <div class="table-responsive">
            @if ($gradeRecords->count() > 0)
                <table class="admin-data-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>اسم الطالب</th>
                            <th>الصف / الفصل</th>
                            <th>المادة</th>
                            <th>نوع الدرجة</th>
                            <th>الدرجة</th>
                            <th>النسبة</th>
                            <th>التاريخ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($gradeRecords as $index => $record)
                            @php
                                $pct = (float) $record->percentage;
                                $pctBadge = $pct >= 90 ? 'admin-badge-success' : ($pct >= 75 ? 'admin-badge-role' : ($pct >= 50 ? 'admin-badge-warning' : 'admin-badge-danger'));
                            @endphp
                            <tr>
                                <th scope="row" class="row-number">{{ $index + 1 }}</th>
                                <td>
                                    <strong class="admin-user-link d-block">{{ $record->student->user->name ?? '—' }}</strong>
                                    <small class="text-muted">{{ $record->student->student_code ?? '' }}</small>
                                </td>
                                <td>
                                    @if ($record->student->class && $record->student->section)
                                        <span class="admin-badge admin-badge-role">{{ $record->student->class->grade->name }} - {{ $record->student->class->name }}</span>
                                        <small class="d-block text-muted mt-1">الفصل: {{ $record->student->section->name }}</small>
                                    @else
                                        <span class="text-muted">غير مسجل</span>
                                    @endif
                                </td>
                                <td>{{ $record->subject->name }}</td>
                                <td><span class="admin-badge admin-badge-muted">{{ $record->exam_type_name }}</span></td>
                                <td>
                                    <strong>{{ number_format($record->marks_obtained, 2) }}</strong>
                                    <span class="text-muted">/ {{ number_format($record->total_marks, 2) }}</span>
                                </td>
                                <td><span class="admin-badge {{ $pctBadge }}">{{ number_format($pct, 2) }}%</span></td>
                                <td>{{ $record->exam_date->format('Y-m-d') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="admin-empty-state">
                    <i class="ri-bar-chart-box-line"></i>
                    لا توجد بيانات متاحة
                </div>
            @endif
        </div>
    </div>
</div>

@if ($gradeRecords->count() > 0)
    <div class="admin-table-footer">
        <div class="admin-table-meta">تصدير نتائج التقرير</div>
        <form action="{{ route('admin.reports.export') }}" method="POST" class="d-flex flex-wrap gap-2">
            @csrf
            <input type="hidden" name="type" value="grades">
            @foreach (request()->except(['type', 'format', '_token']) as $key => $value)
                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
            @endforeach
            <button type="submit" name="format" value="excel" class="admin-btn admin-btn-primary">
                <i class="ri-file-excel-2-line"></i>
                تصدير Excel
            </button>
            <button type="submit" name="format" value="pdf" class="admin-btn admin-btn-danger">
                <i class="ri-file-pdf-line"></i>
                تصدير PDF
            </button>
        </form>
    </div>
@endif
