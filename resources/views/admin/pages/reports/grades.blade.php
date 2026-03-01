@extends('admin.layouts.master')

@section('page-title')
    تقرير الدرجات
@stop

@section('content')
    <!-- Start::app-content -->
    <div class="main-content app-content">
        <div class="container-fluid">
            <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
                <div class="my-auto">
                    <h5 class="page-title fs-21 mb-1">تقرير الدرجات</h5>
                </div>
                <div>
                    <a href="{{ route('admin.reports.index') }}" class="btn btn-secondary">رجوع</a>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header align-items-center d-flex gap-3">
                            <h5 class="card-title mb-0">فلاتر التقرير</h5>
                            <div class="flex-shrink-0">
                                <form action="{{ route('admin.reports.grades') }}" method="GET" class="d-flex align-items-center gap-2">
                                    <select name="class_id" class="form-select" style="width: 180px;">
                                        <option value="">كل الصفوف</option>
                                        @foreach($classes as $class)
                                            <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>
                                                {{ $class->grade->name }} - {{ $class->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <select name="section_id" class="form-select" style="width: 150px;">
                                        <option value="">كل الفصول</option>
                                        @foreach($sections as $section)
                                            <option value="{{ $section->id }}" {{ request('section_id') == $section->id ? 'selected' : '' }}>
                                                {{ $section->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <select name="subject_id" class="form-select" style="width: 150px;">
                                        <option value="">كل المواد</option>
                                        @foreach($subjects as $subject)
                                            <option value="{{ $subject->id }}" {{ request('subject_id') == $subject->id ? 'selected' : '' }}>
                                                {{ $subject->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <input type="date" name="date_from" class="form-control" style="width: 150px;" value="{{ request('date_from') }}">
                                    <input type="date" name="date_to" class="form-control" style="width: 150px;" value="{{ request('date_to') }}">
                                    <button type="submit" class="btn btn-primary">بحث</button>
                                    <a href="{{ route('admin.reports.grades') }}" class="btn btn-danger">مسح</a>
                                </form>
                            </div>
                        </div>
                        <div class="card-body">
                            <!-- إحصائيات عامة -->
                            <div class="row mb-4">
                                <div class="col-md-3">
                                    <div class="card border-primary">
                                        <div class="card-body text-center">
                                            <h6 class="text-muted">إجمالي السجلات</h6>
                                            <h3 class="mb-0">{{ $stats['total_records'] }}</h3>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card border-info">
                                        <div class="card-body text-center">
                                            <h6 class="text-muted">المتوسط العام</h6>
                                            <h3 class="mb-0 text-info">{{ number_format($stats['average_percentage'], 2) }}%</h3>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card border-success">
                                        <div class="card-body text-center">
                                            <h6 class="text-muted">ممتاز (90+)</h6>
                                            <h3 class="mb-0 text-success">{{ $stats['excellent_count'] }}</h3>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card border-danger">
                                        <div class="card-body text-center">
                                            <h6 class="text-muted">راسب (&lt;50)</h6>
                                            <h3 class="mb-0 text-danger">{{ $stats['fail_count'] }}</h3>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @if($gradeRecords->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover align-middle table-nowrap mb-0">
                                        <thead class="table-light">
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
                                            @foreach($gradeRecords as $record)
                                                <tr>
                                                    <td>{{ $record->id }}</td>
                                                    <td>{{ $record->student->user->name }}</td>
                                                    <td>
                                                        @if($record->student->class && $record->student->section)
                                                            <span class="badge bg-info">{{ $record->student->class->grade->name }} - {{ $record->student->class->name }}</span>
                                                            <br>
                                                            <small class="text-muted">الفصل: {{ $record->student->section->name }}</small>
                                                        @else
                                                            <span class="text-muted">غير مسجل</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ $record->subject->name }}</td>
                                                    <td>{{ $record->grade_type }}</td>
                                                    <td>{{ number_format($record->score, 2) }} / {{ number_format($record->max_score, 2) }}</td>
                                                    <td>
                                                        <span class="badge bg-{{ $record->percentage >= 90 ? 'success' : ($record->percentage >= 75 ? 'info' : ($record->percentage >= 50 ? 'warning' : 'danger')) }}">
                                                            {{ number_format($record->percentage, 2) }}%
                                                        </span>
                                                    </td>
                                                    <td>{{ $record->record_date->format('Y-m-d') }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="alert alert-info text-center">
                                    <p class="mb-0">لا توجد بيانات متاحة</p>
                                </div>
                            @endif
                        </div>
                        @if($gradeRecords->count() > 0)
                            <div class="card-footer">
                                <form action="{{ route('admin.reports.export') }}" method="POST" class="d-inline">
                                    @csrf
                                    <input type="hidden" name="type" value="grades">
                                    @foreach(request()->except(['type', 'format', '_token']) as $key => $value)
                                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                                    @endforeach
                                    <button type="submit" name="format" value="pdf" class="btn btn-danger">تصدير PDF</button>
                                    <button type="submit" name="format" value="excel" class="btn btn-success">تصدير Excel</button>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End::app-content -->
@stop

