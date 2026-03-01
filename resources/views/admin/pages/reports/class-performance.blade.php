@extends('admin.layouts.master')

@section('page-title')
    تقرير أداء الفصول
@stop

@section('content')
    <!-- Start::app-content -->
    <div class="main-content app-content">
        <div class="container-fluid">
            <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
                <div class="my-auto">
                    <h5 class="page-title fs-21 mb-1">تقرير أداء الفصول</h5>
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
                                <form action="{{ route('admin.reports.class-performance') }}" method="GET" class="d-flex align-items-center gap-2">
                                    <select name="grade_id" class="form-select" style="width: 150px;">
                                        <option value="">كل المراحل</option>
                                        @foreach($grades as $grade)
                                            <option value="{{ $grade->id }}" {{ request('grade_id') == $grade->id ? 'selected' : '' }}>
                                                {{ $grade->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <select name="class_id" class="form-select" style="width: 180px;">
                                        <option value="">كل الصفوف</option>
                                        @foreach($classes as $class)
                                            <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>
                                                {{ $class->grade->name }} - {{ $class->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <input type="date" name="date_from" class="form-control" style="width: 150px;" value="{{ request('date_from') }}">
                                    <input type="date" name="date_to" class="form-control" style="width: 150px;" value="{{ request('date_to') }}">
                                    <button type="submit" class="btn btn-primary">بحث</button>
                                    <a href="{{ route('admin.reports.class-performance') }}" class="btn btn-danger">مسح</a>
                                </form>
                            </div>
                        </div>
                        <div class="card-body">
                            @if($classesData->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover align-middle table-nowrap mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th>#</th>
                                                <th>اسم الصف</th>
                                                <th>المرحلة</th>
                                                <th>عدد الطلاب</th>
                                                <th>عدد السجلات</th>
                                                <th>المتوسط العام</th>
                                                <th>طلاب ممتاز</th>
                                                <th>طلاب راسب</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($classesData as $class)
                                                @php
                                                    $stat = $stats[$class->id] ?? [];
                                                @endphp
                                                <tr>
                                                    <td>{{ $class->id }}</td>
                                                    <td>
                                                        <h6 class="mb-0">{{ $class->name }}</h6>
                                                        <small class="text-muted">{{ $class->grade->name }}</small>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-info">{{ $class->grade->name }}</span>
                                                    </td>
                                                    <td>{{ $stat['total_students'] ?? 0 }}</td>
                                                    <td>{{ $stat['total_records'] ?? 0 }}</td>
                                                    <td>
                                                        <span class="badge bg-{{ ($stat['average_percentage'] ?? 0) >= 90 ? 'success' : (($stat['average_percentage'] ?? 0) >= 75 ? 'info' : (($stat['average_percentage'] ?? 0) >= 50 ? 'warning' : 'danger')) }}">
                                                            {{ number_format($stat['average_percentage'] ?? 0, 2) }}%
                                                        </span>
                                                    </td>
                                                    <td><span class="badge bg-success">{{ $stat['excellent_students'] ?? 0 }}</span></td>
                                                    <td><span class="badge bg-danger">{{ $stat['failing_students'] ?? 0 }}</span></td>
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
                        @if($classesData->count() > 0)
                            <div class="card-footer">
                                <form action="{{ route('admin.reports.export') }}" method="POST" class="d-inline">
                                    @csrf
                                    <input type="hidden" name="type" value="class_performance">
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

