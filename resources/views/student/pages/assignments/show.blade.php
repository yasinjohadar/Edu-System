@extends('student.layouts.master')

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
                    @if($canSubmit)
                    <a href="{{ route('student.assignments.submit', $assignment->id) }}" class="btn btn-primary btn-sm">
                        <i class="fa-solid fa-upload"></i> تسليم الواجب
                    </a>
                    @endif
                    <a href="{{ route('student.assignments.index') }}" class="btn btn-secondary btn-sm">رجوع</a>
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
                                    <strong>المادة:</strong> <span class="badge bg-info">{{ $assignment->subject->name ?? 'غير محدد' }}</span>
                                </div>
                                <div class="col-md-6">
                                    <strong>المعلم:</strong> {{ $assignment->teacher->user->name ?? 'غير محدد' }}
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
                                <p class="bg-light p-3 rounded">{{ $assignment->instructions }}</p>
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
                            @if($remainingAttempts !== null)
                            <div class="alert alert-info">
                                <strong>المحاولات المتبقية:</strong> {{ $remainingAttempts }}
                            </div>
                            @endif
                            @php
                                $submissionTypes = is_string($assignment->submission_types) ? json_decode($assignment->submission_types, true) : ($assignment->submission_types ?? []);
                            @endphp
                            <div class="mb-3">
                                <strong>أنواع التسليم المسموحة:</strong>
                                @foreach($submissionTypes as $type)
                                    <span class="badge bg-primary">{{ $type == 'file' ? 'ملفات' : ($type == 'text' ? 'نصوص' : 'روابط') }}</span>
                                @endforeach
                            </div>

                            @if($assignment->attachments->count() > 0)
                            <div class="mb-3">
                                <strong>المرفقات:</strong>
                                <div class="list-group mt-2">
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
                            @endif
                        </div>
                    </div>

                    @if($submissions->count() > 0)
                    <div class="card mt-3">
                        <div class="card-header">
                            <h5 class="card-title">تسليماتي</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>المحاولة</th>
                                            <th>تاريخ التسليم</th>
                                            <th>الحالة</th>
                                            <th>الدرجة</th>
                                            <th>العمليات</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($submissions as $submission)
                                            <tr>
                                                <td><span class="badge bg-info">{{ $submission->attempt_number }}</span></td>
                                                <td>{{ $submission->submitted_at->format('Y-m-d H:i') }}</td>
                                                <td>
                                                    @if($submission->status == 'graded')
                                                        <span class="badge bg-success">{{ $submission->status_name }}</span>
                                                    @elseif($submission->status == 'late')
                                                        <span class="badge bg-danger">{{ $submission->status_name }}</span>
                                                    @elseif($submission->status == 'returned')
                                                        <span class="badge bg-warning">{{ $submission->status_name }}</span>
                                                    @else
                                                        <span class="badge bg-info">{{ $submission->status_name }}</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($submission->marks_obtained !== null)
                                                        <strong>{{ number_format($submission->marks_obtained, 2) }}</strong> / {{ number_format($assignment->total_marks, 2) }}
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="{{ route('student.assignments.submissions.show', $submission->id) }}" class="btn btn-sm btn-info">
                                                        <i class="fa-solid fa-eye"></i>
                                                    </a>
                                                    @if($submission->requires_resubmission && $submission->canResubmit())
                                                    <a href="{{ route('student.assignments.submit', $assignment->id) }}" class="btn btn-sm btn-warning">
                                                        <i class="fa-solid fa-redo"></i> إعادة تسليم
                                                    </a>
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
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">معلومات إضافية</h5>
                        </div>
                        <div class="card-body">
                            <p><strong>السماح بالتأخير:</strong> {{ $assignment->allow_late_submission ? 'نعم' : 'لا' }}</p>
                            @if($assignment->allow_late_submission)
                                <p><strong>غرامة التأخير:</strong> {{ number_format($assignment->late_penalty_per_day, 2) }} لكل يوم</p>
                            @endif
                            <p><strong>عدد المحاولات:</strong> {{ $assignment->max_attempts ?? 'غير محدود' }}</p>
                            <p><strong>السماح بإعادة التسليم:</strong> {{ $assignment->allow_resubmission ? 'نعم' : 'لا' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End::app-content -->
@stop

