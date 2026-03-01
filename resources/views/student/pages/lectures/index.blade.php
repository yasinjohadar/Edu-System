@extends('student.layouts.master')

@section('page-title')
    المحاضرات الإلكترونية
@stop

@section('content')
    <!-- Start::app-content -->
    <div class="main-content app-content">
        <div class="container-fluid">
            <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
                <div class="my-auto">
                    <h5 class="page-title fs-21 mb-1">المحاضرات الإلكترونية</h5>
                </div>
            </div>

            <!-- المحاضرات القادمة -->
            @if($upcomingLectures->count() > 0)
                <div class="row mb-4">
                    <div class="col-xl-12">
                        <div class="card">
                            <div class="card-header bg-primary text-white">
                                <h5 class="card-title mb-0">المحاضرات القادمة</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>العنوان</th>
                                                <th>المادة</th>
                                                <th>المعلم</th>
                                                <th>التاريخ والوقت</th>
                                                <th>النوع</th>
                                                <th>العمليات</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($upcomingLectures as $lecture)
                                                <tr>
                                                    <td><strong>{{ $lecture->title }}</strong></td>
                                                    <td>{{ $lecture->subject->name }}</td>
                                                    <td>{{ $lecture->teacher->user->name ?? '-' }}</td>
                                                    <td>
                                                        @if($lecture->scheduled_at)
                                                            {{ $lecture->scheduled_at->format('Y-m-d H:i') }}
                                                        @else
                                                            -
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($lecture->type == 'live')
                                                            <span class="badge bg-danger">مباشرة</span>
                                                        @else
                                                            <span class="badge bg-primary">مسجلة</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('student.lectures.show', $lecture->id) }}" class="btn btn-sm btn-primary">عرض</a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header align-items-center d-flex gap-3">
                            <div class="flex-shrink-0">
                                <form action="{{ route('student.lectures.index') }}" method="GET" class="d-flex align-items-center gap-2">
                                    <select name="subject_id" class="form-select" style="width: 200px;">
                                        <option value="">كل المواد</option>
                                        @foreach($subjects as $subject)
                                            <option value="{{ $subject->id }}" {{ request('subject_id') == $subject->id ? 'selected' : '' }}>{{ $subject->name }}</option>
                                        @endforeach
                                    </select>
                                    <select name="type" class="form-select" style="width: 150px;">
                                        <option value="">كل الأنواع</option>
                                        <option value="live" {{ request('type') == 'live' ? 'selected' : '' }}>مباشرة</option>
                                        <option value="recorded" {{ request('type') == 'recorded' ? 'selected' : '' }}>مسجلة</option>
                                    </select>
                                    <button type="submit" class="btn btn-secondary">بحث</button>
                                    <a href="{{ route('student.lectures.index') }}" class="btn btn-danger">مسح</a>
                                </form>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover align-middle table-nowrap mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>العنوان</th>
                                            <th>المادة</th>
                                            <th>المعلم</th>
                                            <th>النوع</th>
                                            <th>التاريخ</th>
                                            <th>المشاهدات</th>
                                            <th>العمليات</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($lectures as $lecture)
                                            <tr>
                                                <td>{{ $lecture->id }}</td>
                                                <td><strong>{{ $lecture->title }}</strong></td>
                                                <td>{{ $lecture->subject->name }}</td>
                                                <td>{{ $lecture->teacher->user->name ?? '-' }}</td>
                                                <td>
                                                    @if($lecture->type == 'live')
                                                        <span class="badge bg-danger">مباشرة</span>
                                                    @else
                                                        <span class="badge bg-primary">مسجلة</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($lecture->scheduled_at)
                                                        {{ $lecture->scheduled_at->format('Y-m-d H:i') }}
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                <td>{{ $lecture->views_count }}</td>
                                                <td>
                                                    <a href="{{ route('student.lectures.show', $lecture->id) }}" class="btn btn-sm btn-primary" title="عرض">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                            <circle cx="12" cy="12" r="3"></circle>
                                                        </svg>
                                                    </a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="8" class="text-center">لا توجد محاضرات</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-3">
                                {{ $lectures->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End::app-content -->
@stop

