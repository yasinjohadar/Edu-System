@extends('admin.layouts.master')

@section('page-title')
    تفاصيل المحاضرة
@stop

@section('content')
    <!-- Start::app-content -->
    <div class="main-content app-content">
        <div class="container-fluid">
            <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
                <div class="my-auto">
                    <h5 class="page-title fs-21 mb-1">تفاصيل المحاضرة</h5>
                </div>
                <div>
                    <a href="{{ route('admin.online-lectures.edit', $lecture->id) }}" class="btn btn-warning btn-sm">تعديل</a>
                    <a href="{{ route('admin.online-lectures.index') }}" class="btn btn-secondary btn-sm">رجوع</a>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-8">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">{{ $lecture->title }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <strong>المادة:</strong> {{ $lecture->subject->name }}
                                </div>
                                <div class="col-md-6">
                                    <strong>الفصل:</strong> {{ $lecture->section->name }}
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <strong>المعلم:</strong> {{ $lecture->teacher->user->name ?? '-' }}
                                </div>
                                <div class="col-md-6">
                                    <strong>النوع:</strong>
                                    @if($lecture->type == 'live')
                                        <span class="badge bg-danger">مباشرة</span>
                                    @elseif($lecture->type == 'recorded')
                                        <span class="badge bg-primary">مسجلة</span>
                                    @else
                                        <span class="badge bg-info">مواد</span>
                                    @endif
                                </div>
                            </div>
                            @if($lecture->description)
                                <div class="mb-3">
                                    <strong>الوصف:</strong>
                                    <p>{{ $lecture->description }}</p>
                                </div>
                            @endif
                            @if($lecture->content)
                                <div class="mb-3">
                                    <strong>المحتوى:</strong>
                                    <div>{!! nl2br(e($lecture->content)) !!}</div>
                                </div>
                            @endif
                            @if($lecture->video_url)
                                <div class="mb-3">
                                    <strong>رابط الفيديو:</strong>
                                    <a href="{{ $lecture->video_url }}" target="_blank" class="btn btn-sm btn-primary">فتح الفيديو</a>
                                </div>
                            @endif
                            @if($lecture->meeting_link)
                                <div class="mb-3">
                                    <strong>رابط الاجتماع:</strong>
                                    <a href="{{ $lecture->meeting_link }}" target="_blank" class="btn btn-sm btn-danger">انضم للاجتماع</a>
                                </div>
                            @endif
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <strong>المشاهدات:</strong> {{ $lecture->views_count }}
                                </div>
                                <div class="col-md-6">
                                    <strong>الحالة:</strong>
                                    @if($lecture->is_published)
                                        <span class="badge bg-success">منشورة</span>
                                    @else
                                        <span class="badge bg-warning">مسودة</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($lecture->materials->count() > 0)
                        <div class="card mt-3">
                            <div class="card-header">
                                <h5 class="card-title">المواد التعليمية</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>العنوان</th>
                                                <th>النوع</th>
                                                <th>التحميلات</th>
                                                <th>العمليات</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($lecture->materials as $material)
                                                <tr>
                                                    <td>{{ $material->title }}</td>
                                                    <td>
                                                        @if($material->type == 'file')
                                                            <span class="badge bg-primary">ملف</span>
                                                        @elseif($material->type == 'link')
                                                            <span class="badge bg-info">رابط</span>
                                                        @else
                                                            <span class="badge bg-secondary">{{ $material->type }}</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ $material->download_count }}</td>
                                                    <td>
                                                        @if($material->external_url)
                                                            <a href="{{ $material->external_url }}" target="_blank" class="btn btn-sm btn-primary">فتح</a>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="col-xl-4">
                    @if($lecture->attendance->count() > 0)
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title">الحضور</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>الطالب</th>
                                                <th>الحالة</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($lecture->attendance->take(10) as $attendance)
                                                <tr>
                                                    <td>{{ $attendance->student->user->name ?? '-' }}</td>
                                                    <td>
                                                        <span class="badge bg-{{ $attendance->status_color }}">{{ $attendance->status_label }}</span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!-- End::app-content -->
@stop

