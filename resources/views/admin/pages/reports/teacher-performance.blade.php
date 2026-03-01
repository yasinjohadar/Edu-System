@extends('admin.layouts.master')

@section('page-title')
    تقرير أداء المعلمين
@stop

@section('content')
    <!-- Start::app-content -->
    <div class="main-content app-content">
        <div class="container-fluid">
            <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
                <div class="my-auto">
                    <h5 class="page-title fs-21 mb-1">تقرير أداء المعلمين</h5>
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
                                <form action="{{ route('admin.reports.teacher-performance') }}" method="GET" class="d-flex align-items-center gap-2">
                                    <select name="teacher_id" class="form-select" style="width: 200px;">
                                        <option value="">كل المعلمين</option>
                                        @foreach($teachersData as $teacher)
                                            <option value="{{ $teacher->id }}" {{ request('teacher_id') == $teacher->id ? 'selected' : '' }}>
                                                {{ $teacher->user->name }}
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
                                    <button type="submit" class="btn btn-primary">بحث</button>
                                    <a href="{{ route('admin.reports.teacher-performance') }}" class="btn btn-danger">مسح</a>
                                </form>
                            </div>
                        </div>
                        <div class="card-body">
                            @if($teachersData->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover align-middle table-nowrap mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th>#</th>
                                                <th>اسم المعلم</th>
                                                <th>عدد الطلاب</th>
                                                <th>عدد الفصول</th>
                                                <th>عدد المواد</th>
                                                <th>عدد الدرجات</th>
                                                <th>المتوسط العام</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($teachersData as $teacher)
                                                @php
                                                    $stat = $stats[$teacher->id] ?? [];
                                                @endphp
                                                <tr>
                                                    <td>{{ $teacher->id }}</td>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            @if($teacher->user->photo)
                                                                <img src="{{ asset('storage/' . $teacher->user->photo) }}" alt="{{ $teacher->user->name }}" class="avatar avatar-sm rounded-circle me-2">
                                                            @else
                                                                <div class="avatar avatar-sm rounded-circle bg-primary me-2 d-flex align-items-center justify-content-center text-white">
                                                                    {{ substr($teacher->user->name, 0, 1) }}
                                                                </div>
                                                            @endif
                                                            <div>
                                                                <h6 class="mb-0">{{ $teacher->user->name }}</h6>
                                                                <small class="text-muted">{{ $teacher->user->email }}</small>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>{{ $stat['total_students'] ?? 0 }}</td>
                                                    <td>{{ $stat['total_sections'] ?? 0 }}</td>
                                                    <td>{{ $stat['total_subjects'] ?? 0 }}</td>
                                                    <td>{{ $stat['total_grades'] ?? 0 }}</td>
                                                    <td>
                                                        <span class="badge bg-{{ ($stat['average_percentage'] ?? 0) >= 90 ? 'success' : (($stat['average_percentage'] ?? 0) >= 75 ? 'info' : (($stat['average_percentage'] ?? 0) >= 50 ? 'warning' : 'danger')) }}">
                                                            {{ number_format($stat['average_percentage'] ?? 0, 2) }}%
                                                        </span>
                                                    </td>
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
                        @if($teachersData->count() > 0)
                            <div class="card-footer">
                                <form action="{{ route('admin.reports.export') }}" method="POST" class="d-inline">
                                    @csrf
                                    <input type="hidden" name="type" value="teacher_performance">
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

