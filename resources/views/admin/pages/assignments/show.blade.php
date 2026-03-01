@extends('admin.layouts.master')

@section('page-title')
    تفاصيل الواجب
@stop

@section('content')
    <!-- Start::app-content -->
    <div class="main-content app-content">
        <div class="container-fluid">
            <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
                <div class="my-auto">
                    <h5 class="page-title fs-21 mb-1">تفاصيل الواجب</h5>
                </div>
                <div>
                    @can('assignment-edit')
                    <a href="{{ route('admin.assignments.edit', $assignment->id) }}" class="btn btn-warning btn-sm">
                        <i class="fa-solid fa-edit"></i> تعديل
                    </a>
                    @endcan
                    <a href="{{ route('admin.assignments.submissions.index', $assignment->id) }}" class="btn btn-primary btn-sm">
                        <i class="fa-solid fa-file-arrow-up"></i> التسليمات ({{ $stats['total_submissions'] }})
                    </a>
                    <a href="{{ route('admin.assignments.index') }}" class="btn btn-secondary btn-sm">رجوع</a>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-8">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">{{ $assignment->title }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <strong>رقم الواجب:</strong> {{ $assignment->assignment_number }}
                                </div>
                                <div class="col-md-6">
                                    <strong>المادة:</strong> <span class="badge bg-info">{{ $assignment->subject->name ?? 'غير محدد' }}</span>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <strong>المعلم:</strong> {{ $assignment->teacher->user->name ?? 'غير محدد' }}
                                </div>
                                <div class="col-md-6">
                                    <strong>الفصل:</strong> {{ $assignment->section->name ?? 'كل الفصول' }}
                                </div>
                            </div>
                            @if($assignment->description)
                            <div class="mb-3">
                                <strong>الوصف:</strong>
                                <p>{{ $assignment->description }}</p>
                            </div>
                            @endif
                            @if($assignment->instructions)
                            <div class="mb-3">
                                <strong>التعليمات:</strong>
                                <p>{{ $assignment->instructions }}</p>
                            </div>
                            @endif
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <strong>الدرجة الكلية:</strong> <span class="badge bg-success">{{ number_format($assignment->total_marks, 2) }}</span>
                                </div>
                                <div class="col-md-4">
                                    <strong>تاريخ الاستحقاق:</strong> {{ $assignment->due_date->format('Y-m-d') }}
                                    @if($assignment->isOverdue())
                                        <span class="badge bg-danger">متأخر</span>
                                    @endif
                                </div>
                                <div class="col-md-4">
                                    <strong>وقت الاستحقاق:</strong> {{ $assignment->due_time }}
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <strong>الحالة:</strong>
                                    @if($assignment->status == 'published')
                                        <span class="badge bg-success">{{ $assignment->status_name }}</span>
                                    @elseif($assignment->status == 'closed')
                                        <span class="badge bg-danger">{{ $assignment->status_name }}</span>
                                    @else
                                        <span class="badge bg-secondary">{{ $assignment->status_name }}</span>
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    <strong>النشاط:</strong>
                                    @if($assignment->is_active)
                                        <span class="badge bg-success">نشط</span>
                                    @else
                                        <span class="badge bg-danger">غير نشط</span>
                                    @endif
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <strong>السماح بالتأخير:</strong> {{ $assignment->allow_late_submission ? 'نعم' : 'لا' }}
                                    @if($assignment->allow_late_submission)
                                        <br><small class="text-muted">غرامة: {{ number_format($assignment->late_penalty_per_day, 2) }} لكل يوم</small>
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    <strong>عدد المحاولات:</strong> {{ $assignment->max_attempts ?? 'غير محدود' }}
                                </div>
                            </div>
                            @php
                                $submissionTypes = is_string($assignment->submission_types) ? json_decode($assignment->submission_types, true) : ($assignment->submission_types ?? []);
                            @endphp
                            <div class="mb-3">
                                <strong>أنواع التسليم المسموحة:</strong>
                                @foreach($submissionTypes as $type)
                                    <span class="badge bg-primary">{{ $type == 'file' ? 'ملفات' : ($type == 'text' ? 'نصوص' : 'روابط') }}</span>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    @if($assignment->attachments->count() > 0)
                    <div class="card mt-3">
                        <div class="card-header">
                            <h5 class="card-title">المرفقات</h5>
                        </div>
                        <div class="card-body">
                            <div class="list-group">
                                @foreach($assignment->attachments as $attachment)
                                    <div class="list-group-item">
                                        <a href="{{ asset('storage/' . $attachment->file_path) }}" target="_blank" class="text-decoration-none">
                                            <i class="fa-solid fa-file"></i> {{ $attachment->file_name }}
                                        </a>
                                        <small class="text-muted ms-2">({{ $attachment->formatted_file_size }})</small>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif
                </div>

                <div class="col-xl-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">الإحصائيات</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <strong>إجمالي التسليمات:</strong>
                                <span class="badge bg-info float-end">{{ $stats['total_submissions'] }}</span>
                            </div>
                            <div class="mb-3">
                                <strong>المصححة:</strong>
                                <span class="badge bg-success float-end">{{ $stats['graded_submissions'] }}</span>
                            </div>
                            <div class="mb-3">
                                <strong>المعلقة:</strong>
                                <span class="badge bg-warning float-end">{{ $stats['pending_submissions'] }}</span>
                            </div>
                            <div class="mb-3">
                                <strong>متوسط الدرجات:</strong>
                                <span class="badge bg-primary float-end">{{ number_format($stats['average_marks'], 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End::app-content -->
@stop

