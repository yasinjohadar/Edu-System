@extends('admin.layouts.master')

@section('page-title')
    تقرير أداء الطلاب
@stop

@section('content')
    <div class="main-content app-content">
        <div class="container-fluid">

            <div class="admin-page-header">
                <div class="page-title-wrap">
                    <h1>تقرير أداء الطلاب</h1>
                    <p>تحليل درجات الطلاب حسب الصف والفصل والمادة</p>
                </div>
                <a href="{{ route('admin.reports.index') }}" class="admin-btn admin-btn-secondary">
                    <i class="ri-arrow-right-line"></i>
                    العودة للتقارير
                </a>
            </div>

            <div class="admin-page-card">
                <div class="card-toolbar">
                    <form action="{{ route('admin.reports.student-performance') }}" method="GET" class="admin-filters w-100">
                        <select name="class_id" class="form-select" style="width: auto; min-width: 180px;">
                            <option value="">كل الصفوف</option>
                            @foreach ($classes as $class)
                                <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>
                                    {{ $class->grade->name }} - {{ $class->name }}
                                </option>
                            @endforeach
                        </select>

                        <select name="section_id" class="form-select" style="width: auto; min-width: 140px;">
                            <option value="">كل الفصول</option>
                            @foreach ($sections as $section)
                                <option value="{{ $section->id }}" {{ request('section_id') == $section->id ? 'selected' : '' }}>
                                    {{ $section->name }}
                                </option>
                            @endforeach
                        </select>

                        <select name="subject_id" class="form-select" style="width: auto; min-width: 140px;">
                            <option value="">كل المواد</option>
                            @foreach ($subjects as $subject)
                                <option value="{{ $subject->id }}" {{ request('subject_id') == $subject->id ? 'selected' : '' }}>
                                    {{ $subject->name }}
                                </option>
                            @endforeach
                        </select>

                        <div class="admin-filter-dates">
                            <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}" title="من تاريخ">
                            <span class="admin-filter-dates-sep">—</span>
                            <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}" title="إلى تاريخ">
                        </div>

                        <button type="submit" class="admin-btn admin-btn-primary">
                            <i class="ri-search-line"></i>
                            بحث
                        </button>
                        <a href="{{ route('admin.reports.student-performance') }}" class="admin-btn admin-btn-danger">
                            <i class="ri-refresh-line"></i>
                            مسح
                        </a>
                    </form>
                </div>

                <div class="admin-table-wrap">
                    <div class="table-responsive">
                        @if ($students->count() > 0)
                            <table class="admin-data-table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>اسم الطالب</th>
                                        <th>الصف / الفصل</th>
                                        <th>عدد السجلات</th>
                                        <th>المتوسط</th>
                                        <th>أعلى درجة</th>
                                        <th>أقل درجة</th>
                                        <th>ممتاز</th>
                                        <th>جيد</th>
                                        <th>مقبول</th>
                                        <th>راسب</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($students as $index => $student)
                                        @php
                                            $stat = $stats[$student->id] ?? [];
                                            $avg = $stat['average_percentage'] ?? 0;
                                            $avgClass = $avg >= 90 ? 'admin-badge-success' : ($avg >= 75 ? 'admin-badge-role' : ($avg >= 50 ? 'admin-badge-warning' : 'admin-badge-danger'));
                                        @endphp
                                        <tr>
                                            <th scope="row" class="row-number">{{ $index + 1 }}</th>
                                            <td>
                                                <div class="admin-user-cell">
                                                    @if ($student->photo)
                                                        <img src="{{ asset('storage/' . $student->photo) }}" alt="{{ $student->user->name }}" class="admin-avatar-initial" style="object-fit: cover; padding: 0;">
                                                    @else
                                                        <span class="admin-avatar-initial">{{ mb_substr($student->user->name, 0, 1) }}</span>
                                                    @endif
                                                    <div>
                                                        <strong class="admin-user-link d-block mb-0">{{ $student->user->name }}</strong>
                                                        <small class="text-muted">{{ $student->student_code }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                @if ($student->class && $student->section)
                                                    <span class="admin-badge admin-badge-role">{{ $student->class->grade->name }} - {{ $student->class->name }}</span>
                                                    <span class="admin-badge admin-badge-muted d-inline-block mt-1">{{ $student->section->name }}</span>
                                                @else
                                                    <span class="text-muted">غير مسجل</span>
                                                @endif
                                            </td>
                                            <td>{{ $stat['total_records'] ?? 0 }}</td>
                                            <td>
                                                <span class="admin-badge {{ $avgClass }}">{{ number_format($avg, 2) }}%</span>
                                            </td>
                                            <td>{{ number_format($stat['highest_grade'] ?? 0, 2) }}%</td>
                                            <td>{{ number_format($stat['lowest_grade'] ?? 100, 2) }}%</td>
                                            <td><span class="admin-badge admin-badge-success">{{ $stat['excellent_count'] ?? 0 }}</span></td>
                                            <td><span class="admin-badge admin-badge-role">{{ $stat['good_count'] ?? 0 }}</span></td>
                                            <td><span class="admin-badge admin-badge-warning">{{ $stat['pass_count'] ?? 0 }}</span></td>
                                            <td><span class="admin-badge admin-badge-danger">{{ $stat['fail_count'] ?? 0 }}</span></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <div class="admin-empty-state">
                                <i class="ri-bar-chart-box-line"></i>
                                لا توجد بيانات متاحة للفلاتر المحددة
                            </div>
                        @endif
                    </div>
                </div>

                @if ($students->count() > 0)
                    <div class="admin-table-footer">
                        <div class="admin-table-meta">
                            عرض {{ $students->count() }} طالب
                        </div>
                        <form action="{{ route('admin.reports.export') }}" method="POST" class="d-flex flex-wrap gap-2">
                            @csrf
                            <input type="hidden" name="type" value="student_performance">
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
            </div>

        </div>
    </div>
@stop
