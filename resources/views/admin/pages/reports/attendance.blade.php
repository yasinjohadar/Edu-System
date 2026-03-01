@extends('admin.layouts.master')

@section('page-title')
    تقرير الحضور والغياب
@stop

@section('content')
    <!-- Start::app-content -->
    <div class="main-content app-content">
        <div class="container-fluid">
            <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
                <div class="my-auto">
                    <h5 class="page-title fs-21 mb-1">تقرير الحضور والغياب</h5>
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
                                <form action="{{ route('admin.reports.attendance') }}" method="GET" class="d-flex align-items-center gap-2">
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
                                    <select name="status" class="form-select" style="width: 150px;">
                                        <option value="">كل الحالات</option>
                                        <option value="present" {{ request('status') == 'present' ? 'selected' : '' }}>حاضر</option>
                                        <option value="absent" {{ request('status') == 'absent' ? 'selected' : '' }}>غائب</option>
                                        <option value="late" {{ request('status') == 'late' ? 'selected' : '' }}>متأخر</option>
                                        <option value="excused" {{ request('status') == 'excused' ? 'selected' : '' }}>معذور</option>
                                    </select>
                                    <input type="date" name="date_from" class="form-control" style="width: 150px;" value="{{ request('date_from') }}">
                                    <input type="date" name="date_to" class="form-control" style="width: 150px;" value="{{ request('date_to') }}">
                                    <button type="submit" class="btn btn-primary">بحث</button>
                                    <a href="{{ route('admin.reports.attendance') }}" class="btn btn-danger">مسح</a>
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
                                            <h3 class="mb-0">{{ $stats['total'] }}</h3>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card border-success">
                                        <div class="card-body text-center">
                                            <h6 class="text-muted">حاضر</h6>
                                            <h3 class="mb-0 text-success">{{ $stats['present'] }} ({{ $stats['present_rate'] }}%)</h3>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card border-danger">
                                        <div class="card-body text-center">
                                            <h6 class="text-muted">غائب</h6>
                                            <h3 class="mb-0 text-danger">{{ $stats['absent'] }} ({{ $stats['absent_rate'] }}%)</h3>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card border-warning">
                                        <div class="card-body text-center">
                                            <h6 class="text-muted">متأخر</h6>
                                            <h3 class="mb-0 text-warning">{{ $stats['late'] }} ({{ $stats['late_rate'] }}%)</h3>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @if($attendances->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover align-middle table-nowrap mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th>#</th>
                                                <th>اسم الطالب</th>
                                                <th>التاريخ</th>
                                                <th>الحالة</th>
                                                <th>وقت الحضور</th>
                                                <th>وقت الانصراف</th>
                                                <th>ملاحظات</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($attendances as $attendance)
                                                <tr>
                                                    <td>{{ $attendance->id }}</td>
                                                    <td>{{ $attendance->student->user->name }}</td>
                                                    <td>{{ $attendance->date->format('Y-m-d') }}</td>
                                                    <td>
                                                        @if($attendance->status == 'present')
                                                            <span class="badge bg-success">حاضر</span>
                                                        @elseif($attendance->status == 'absent')
                                                            <span class="badge bg-danger">غائب</span>
                                                        @elseif($attendance->status == 'late')
                                                            <span class="badge bg-warning">متأخر</span>
                                                        @else
                                                            <span class="badge bg-info">معذور</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ $attendance->check_in_time ?? '-' }}</td>
                                                    <td>{{ $attendance->check_out_time ?? '-' }}</td>
                                                    <td>{{ $attendance->notes ?? '-' }}</td>
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
                        @if($attendances->count() > 0)
                            <div class="card-footer">
                                <form action="{{ route('admin.reports.export') }}" method="POST" class="d-inline">
                                    @csrf
                                    <input type="hidden" name="type" value="attendance">
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

