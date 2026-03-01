@extends('admin.layouts.master')

@section('page-title')
    تقرير أداء الطلاب
@stop

@section('content')
    <!-- Start::app-content -->
    <div class="main-content app-content">
        <div class="container-fluid">
            <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
                <div class="my-auto">
                    <h5 class="page-title fs-21 mb-1">تقرير أداء الطلاب</h5>
                </div>
                <div>
                    <a href="{{ route('admin.reports.index') }}" class="btn btn-secondary">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-left: 4px; vertical-align: middle;">
                            <line x1="19" y1="12" x2="5" y2="12"></line>
                            <polyline points="12 19 5 12 12 5"></polyline>
                        </svg>
                        رجوع
                    </a>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header align-items-center d-flex gap-3">
                            <h5 class="card-title mb-0">فلاتر التقرير</h5>
                            <div class="flex-shrink-0">
                                <form action="{{ route('admin.reports.student-performance') }}" method="GET" class="d-flex align-items-center gap-2">
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
                                    <input type="date" name="date_from" class="form-control" style="width: 150px;" value="{{ request('date_from') }}" placeholder="من تاريخ">
                                    <input type="date" name="date_to" class="form-control" style="width: 150px;" value="{{ request('date_to') }}" placeholder="إلى تاريخ">
                                    <button type="submit" class="btn btn-primary">بحث</button>
                                    <a href="{{ route('admin.reports.student-performance') }}" class="btn btn-danger">مسح</a>
                                </form>
                            </div>
                        </div>
                        <div class="card-body">
                            @if($students->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover align-middle table-nowrap mb-0">
                                        <thead class="table-light">
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
                                            @foreach($students as $student)
                                                @php
                                                    $stat = $stats[$student->id] ?? [];
                                                @endphp
                                                <tr>
                                                    <td>{{ $student->id }}</td>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            @if($student->photo)
                                                                <img src="{{ asset('storage/' . $student->photo) }}" alt="{{ $student->user->name }}" class="avatar avatar-sm rounded-circle me-2">
                                                            @else
                                                                <div class="avatar avatar-sm rounded-circle bg-primary me-2 d-flex align-items-center justify-content-center text-white">
                                                                    {{ substr($student->user->name, 0, 1) }}
                                                                </div>
                                                            @endif
                                                            <div>
                                                                <h6 class="mb-0">{{ $student->user->name }}</h6>
                                                                <small class="text-muted">{{ $student->student_code }}</small>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        @if($student->class && $student->section)
                                                            <span class="badge bg-info">{{ $student->class->grade->name }} - {{ $student->class->name }}</span>
                                                            <br>
                                                            <small class="text-muted">الفصل: {{ $student->section->name }}</small>
                                                        @else
                                                            <span class="text-muted">غير مسجل</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ $stat['total_records'] ?? 0 }}</td>
                                                    <td>
                                                        <span class="badge bg-{{ ($stat['average_percentage'] ?? 0) >= 90 ? 'success' : (($stat['average_percentage'] ?? 0) >= 75 ? 'info' : (($stat['average_percentage'] ?? 0) >= 50 ? 'warning' : 'danger')) }}">
                                                            {{ number_format($stat['average_percentage'] ?? 0, 2) }}%
                                                        </span>
                                                    </td>
                                                    <td>{{ number_format($stat['highest_grade'] ?? 0, 2) }}%</td>
                                                    <td>{{ number_format($stat['lowest_grade'] ?? 100, 2) }}%</td>
                                                    <td><span class="badge bg-success">{{ $stat['excellent_count'] ?? 0 }}</span></td>
                                                    <td><span class="badge bg-info">{{ $stat['good_count'] ?? 0 }}</span></td>
                                                    <td><span class="badge bg-warning">{{ $stat['pass_count'] ?? 0 }}</span></td>
                                                    <td><span class="badge bg-danger">{{ $stat['fail_count'] ?? 0 }}</span></td>
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
                        @if($students->count() > 0)
                            <div class="card-footer">
                                <form action="{{ route('admin.reports.export') }}" method="POST" class="d-inline">
                                    @csrf
                                    <input type="hidden" name="type" value="student_performance">
                                    @foreach(request()->except(['type', 'format', '_token']) as $key => $value)
                                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                                    @endforeach
                                    <button type="submit" name="format" value="pdf" class="btn btn-danger">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                            <polyline points="7 10 12 15 17 10"></polyline>
                                            <line x1="12" y1="15" x2="12" y2="3"></line>
                                        </svg>
                                        تصدير PDF
                                    </button>
                                    <button type="submit" name="format" value="excel" class="btn btn-success">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                            <polyline points="7 10 12 15 17 10"></polyline>
                                            <line x1="12" y1="15" x2="12" y2="3"></line>
                                        </svg>
                                        تصدير Excel
                                    </button>
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

