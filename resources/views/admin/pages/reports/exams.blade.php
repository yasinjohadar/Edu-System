@extends('admin.layouts.master')

@section('page-title')
    تقرير الاختبارات
@stop

@section('content')
    <!-- Start::app-content -->
    <div class="main-content app-content">
        <div class="container-fluid">
            <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
                <div class="my-auto">
                    <h5 class="page-title fs-21 mb-1">تقرير الاختبارات</h5>
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
                                <form action="{{ route('admin.reports.exams') }}" method="GET" class="d-flex align-items-center gap-2">
                                    <select name="subject_id" class="form-select" style="width: 150px;">
                                        <option value="">كل المواد</option>
                                        @foreach($subjects as $subject)
                                            <option value="{{ $subject->id }}" {{ request('subject_id') == $subject->id ? 'selected' : '' }}>
                                                {{ $subject->name }}
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
                                    <input type="date" name="date_from" class="form-control" style="width: 150px;" value="{{ request('date_from') }}">
                                    <input type="date" name="date_to" class="form-control" style="width: 150px;" value="{{ request('date_to') }}">
                                    <button type="submit" class="btn btn-primary">بحث</button>
                                    <a href="{{ route('admin.reports.exams') }}" class="btn btn-danger">مسح</a>
                                </form>
                            </div>
                        </div>
                        <div class="card-body">
                            @if($exams->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover align-middle table-nowrap mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th>#</th>
                                                <th>اسم الاختبار</th>
                                                <th>المادة</th>
                                                <th>الفصل</th>
                                                <th>تاريخ الاختبار</th>
                                                <th>عدد الطلاب</th>
                                                <th>المتوسط</th>
                                                <th>أعلى درجة</th>
                                                <th>أقل درجة</th>
                                                <th>ناجح</th>
                                                <th>راسب</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($exams as $exam)
                                                @php
                                                    $stat = $stats[$exam->id] ?? [];
                                                @endphp
                                                <tr>
                                                    <td>{{ $exam->id }}</td>
                                                    <td>
                                                        <h6 class="mb-0">{{ $exam->title }}</h6>
                                                        <small class="text-muted">{{ $exam->description }}</small>
                                                    </td>
                                                    <td>{{ $exam->subject->name }}</td>
                                                    <td>{{ $exam->section->name }}</td>
                                                    <td>{{ $exam->exam_date ? $exam->exam_date->format('Y-m-d') : '-' }}</td>
                                                    <td>{{ $stat['total_students'] ?? 0 }}</td>
                                                    <td>
                                                        <span class="badge bg-{{ ($stat['average_percentage'] ?? 0) >= 90 ? 'success' : (($stat['average_percentage'] ?? 0) >= 75 ? 'info' : (($stat['average_percentage'] ?? 0) >= 50 ? 'warning' : 'danger')) }}">
                                                            {{ number_format($stat['average_percentage'] ?? 0, 2) }}%
                                                        </span>
                                                    </td>
                                                    <td>{{ number_format($stat['highest_score'] ?? 0, 2) }}</td>
                                                    <td>{{ number_format($stat['lowest_score'] ?? 100, 2) }}</td>
                                                    <td><span class="badge bg-success">{{ $stat['passed_count'] ?? 0 }}</span></td>
                                                    <td><span class="badge bg-danger">{{ $stat['failed_count'] ?? 0 }}</span></td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="alert alert-info text-center">
                                    <p class="mb-0">لا توجد اختبارات متاحة</p>
                                </div>
                            @endif
                        </div>
                        @if($exams->count() > 0)
                            <div class="card-footer">
                                <form action="{{ route('admin.reports.export') }}" method="POST" class="d-inline">
                                    @csrf
                                    <input type="hidden" name="type" value="exams">
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

