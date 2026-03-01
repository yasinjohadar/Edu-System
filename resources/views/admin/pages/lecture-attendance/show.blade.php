@extends('admin.layouts.master')

@section('page-title')
    تفاصيل الحضور
@stop

@section('content')
    <!-- Start::app-content -->
    <div class="main-content app-content">
        <div class="container-fluid">
            <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
                <div class="my-auto">
                    <h5 class="page-title fs-21 mb-1">تفاصيل الحضور</h5>
                </div>
                <div>
                    <a href="{{ route('admin.lecture-attendance.edit', $attendance->id) }}" class="btn btn-warning btn-sm">تعديل</a>
                    <a href="{{ route('admin.lecture-attendance.index') }}" class="btn btn-secondary btn-sm">رجوع</a>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">معلومات الحضور</h5>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <strong>المحاضرة:</strong> {{ $attendance->lecture->title }}
                                </div>
                                <div class="col-md-6">
                                    <strong>الطالب:</strong> {{ $attendance->student->user->name ?? '-' }}
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <strong>الحالة:</strong>
                                    <span class="badge bg-{{ $attendance->status_color }}">{{ $attendance->status_label }}</span>
                                </div>
                                <div class="col-md-6">
                                    <strong>مدة الحضور:</strong> {{ $attendance->duration_minutes ? $attendance->duration_minutes . ' دقيقة' : '-' }}
                                </div>
                            </div>
                            @if($attendance->joined_at)
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <strong>وقت الانضمام:</strong> {{ $attendance->joined_at->format('Y-m-d H:i') }}
                                    </div>
                                    @if($attendance->left_at)
                                        <div class="col-md-6">
                                            <strong>وقت المغادرة:</strong> {{ $attendance->left_at->format('Y-m-d H:i') }}
                                        </div>
                                    @endif
                                </div>
                            @endif
                            @if($attendance->notes)
                                <div class="mb-3">
                                    <strong>ملاحظات:</strong>
                                    <p>{{ $attendance->notes }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End::app-content -->
@stop

