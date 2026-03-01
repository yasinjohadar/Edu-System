@extends('admin.layouts.master')

@section('page-title')
    تقرير الواجبات
@stop

@section('content')
    <!-- Start::app-content -->
    <div class="main-content app-content">
        <div class="container-fluid">
            <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
                <div class="my-auto">
                    <h5 class="page-title fs-21 mb-1">تقرير الواجبات</h5>
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
                                <form action="{{ route('admin.reports.assignments') }}" method="GET" class="d-flex align-items-center gap-2">
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
                                    <select name="status" class="form-select" style="width: 150px;">
                                        <option value="">كل الحالات</option>
                                        <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>مسودة</option>
                                        <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>منشور</option>
                                        <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>مغلق</option>
                                    </select>
                                    <input type="date" name="date_from" class="form-control" style="width: 150px;" value="{{ request('date_from') }}">
                                    <input type="date" name="date_to" class="form-control" style="width: 150px;" value="{{ request('date_to') }}">
                                    <button type="submit" class="btn btn-primary">بحث</button>
                                    <a href="{{ route('admin.reports.assignments') }}" class="btn btn-danger">مسح</a>
                                </form>
                            </div>
                        </div>
                        <div class="card-body">
                            @if($assignments->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover align-middle table-nowrap mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th>#</th>
                                                <th>اسم الواجب</th>
                                                <th>المادة</th>
                                                <th>الفصل</th>
                                                <th>المعلم</th>
                                                <th>تاريخ الإنشاء</th>
                                                <th>الموعد النهائي</th>
                                                <th>عدد الطلاب</th>
                                                <th>مُسلم</th>
                                                <th>متأخر</th>
                                                <th>مُقيم</th>
                                                <th>المتوسط</th>
                                                <th>الحالة</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($assignments as $assignment)
                                                @php
                                                    $stat = $stats[$assignment->id] ?? [];
                                                @endphp
                                                <tr>
                                                    <td>{{ $assignment->id }}</td>
                                                    <td>
                                                        <h6 class="mb-0">{{ $assignment->title }}</h6>
                                                        <small class="text-muted">{{ $assignment->assignment_number }}</small>
                                                    </td>
                                                    <td>{{ $assignment->subject->name }}</td>
                                                    <td>{{ $assignment->section->name }}</td>
                                                    <td>{{ $assignment->teacher->user->name }}</td>
                                                    <td>{{ $assignment->created_at->format('Y-m-d') }}</td>
                                                    <td>{{ $assignment->due_date ? $assignment->due_date->format('Y-m-d') : '-' }}</td>
                                                    <td>{{ $stat['total_students'] ?? 0 }}</td>
                                                    <td><span class="badge bg-info">{{ $stat['submitted_count'] ?? 0 }}</span></td>
                                                    <td><span class="badge bg-warning">{{ $stat['late_count'] ?? 0 }}</span></td>
                                                    <td><span class="badge bg-success">{{ $stat['graded_count'] ?? 0 }}</span></td>
                                                    <td>
                                                        @if(($stat['average_grade'] ?? 0) > 0)
                                                            <span class="badge bg-primary">{{ number_format($stat['average_grade'], 2) }}</span>
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($assignment->status == 'published')
                                                            <span class="badge bg-success">منشور</span>
                                                        @elseif($assignment->status == 'closed')
                                                            <span class="badge bg-danger">مغلق</span>
                                                        @else
                                                            <span class="badge bg-secondary">مسودة</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="alert alert-info text-center">
                                    <p class="mb-0">لا توجد واجبات متاحة</p>
                                </div>
                            @endif
                        </div>
                        @if($assignments->count() > 0)
                            <div class="card-footer">
                                <form action="{{ route('admin.reports.export') }}" method="POST" class="d-inline">
                                    @csrf
                                    <input type="hidden" name="type" value="assignments">
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

