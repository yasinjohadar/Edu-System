@extends('student.layouts.master')

@section('page-title')
    تفاصيل التسليم
@stop

@section('content')
    <!-- Start::app-content -->
    <div class="main-content app-content">
        <div class="container-fluid">
            <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
                <div class="my-auto">
                    <h5 class="page-title fs-21 mb-1">تفاصيل التسليم: {{ $submission->submission_number }}</h5>
                </div>
                <div>
                    <a href="{{ route('student.assignments.show', $submission->assignment_id) }}" class="btn btn-secondary btn-sm">رجوع</a>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-8">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">معلومات التسليم</h5>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <strong>المحاولة:</strong> <span class="badge bg-info">{{ $submission->attempt_number }}</span>
                                    @if($submission->is_resubmission)
                                        <br><small class="text-muted">إعادة تسليم</small>
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    <strong>تاريخ التسليم:</strong> {{ $submission->submitted_at->format('Y-m-d H:i') }}
                                    @if($submission->is_late)
                                        <br><small class="text-danger">متأخر {{ $submission->days_late }} يوم</small>
                                    @endif
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <strong>الحالة:</strong>
                                    @if($submission->status == 'graded')
                                        <span class="badge bg-success">{{ $submission->status_name }}</span>
                                    @elseif($submission->status == 'late')
                                        <span class="badge bg-danger">{{ $submission->status_name }}</span>
                                    @elseif($submission->status == 'returned')
                                        <span class="badge bg-warning">{{ $submission->status_name }}</span>
                                    @else
                                        <span class="badge bg-info">{{ $submission->status_name }}</span>
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    @if($submission->marks_obtained !== null)
                                        <strong>الدرجة:</strong>
                                        <h4>{{ number_format($submission->marks_obtained, 2) }} / {{ number_format($submission->assignment->total_marks, 2) }}</h4>
                                    @else
                                        <strong>الدرجة:</strong> <span class="text-muted">لم يتم التصحيح بعد</span>
                                    @endif
                                </div>
                            </div>
                            @if($submission->student_notes)
                            <div class="mb-3">
                                <strong>ملاحظاتي:</strong>
                                <p class="bg-light p-3 rounded">{{ $submission->student_notes }}</p>
                            </div>
                            @endif

                            @if($submission->files->count() > 0)
                            <div class="mb-3">
                                <strong>الملفات المرفقة:</strong>
                                <div class="list-group mt-2">
                                    @foreach($submission->files as $file)
                                        <div class="list-group-item d-flex justify-content-between align-items-center">
                                            <div>
                                                <a href="{{ asset('storage/' . $file->file_path) }}" target="_blank" class="text-decoration-none">
                                                    <i class="fa-solid fa-file"></i> {{ $file->file_name }}
                                                </a>
                                                <small class="text-muted ms-2">({{ $file->formatted_file_size }})</small>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            @endif

                            @if($submission->texts->count() > 0)
                            <div class="mb-3">
                                <strong>النصوص:</strong>
                                @foreach($submission->texts as $text)
                                    <div class="bg-light p-3 rounded mt-2">
                                        {!! nl2br(e($text->content)) !!}
                                    </div>
                                @endforeach
                            </div>
                            @endif

                            @if($submission->links->count() > 0)
                            <div class="mb-3">
                                <strong>الروابط:</strong>
                                <div class="list-group mt-2">
                                    @foreach($submission->links as $link)
                                        <div class="list-group-item">
                                            <a href="{{ $link->url }}" target="_blank" class="text-decoration-none">
                                                <i class="fa-solid fa-link"></i> {{ $link->title ?: $link->url }}
                                            </a>
                                            @if($link->description)
                                                <br><small class="text-muted">{{ $link->description }}</small>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

                    @if($submission->status == 'graded')
                    <div class="card mt-3">
                        <div class="card-header">
                            <h5 class="card-title">نتيجة التصحيح</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <strong>الدرجة المحصل عليها:</strong>
                                <h4>{{ number_format($submission->marks_obtained, 2) }} / {{ number_format($submission->assignment->total_marks, 2) }}</h4>
                            </div>
                            @if($submission->feedback)
                            <div class="mb-3">
                                <strong>ملاحظات مختصرة:</strong>
                                <p>{{ $submission->feedback }}</p>
                            </div>
                            @endif
                            @if($submission->teacher_notes)
                            <div class="mb-3">
                                <strong>ملاحظات مفصلة:</strong>
                                <p class="bg-light p-3 rounded">{{ $submission->teacher_notes }}</p>
                            </div>
                            @endif
                            @if($submission->graded_at)
                            <div class="mb-3">
                                <strong>تاريخ التصحيح:</strong> {{ $submission->graded_at->format('Y-m-d H:i') }}
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif

                    @if($submission->requires_resubmission && $submission->canResubmit())
                    <div class="alert alert-warning mt-3">
                        <strong>طلب إعادة التسليم:</strong>
                        <p>{{ $submission->resubmission_reason }}</p>
                        <a href="{{ route('student.assignments.submit', $submission->assignment_id) }}" class="btn btn-warning">
                            <i class="fa-solid fa-redo"></i> إعادة تسليم
                        </a>
                    </div>
                    @endif
                </div>

                <div class="col-xl-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">معلومات الواجب</h5>
                        </div>
                        <div class="card-body">
                            <p><strong>العنوان:</strong> {{ $submission->assignment->title }}</p>
                            <p><strong>المادة:</strong> {{ $submission->assignment->subject->name ?? 'غير محدد' }}</p>
                            <p><strong>الدرجة الكلية:</strong> {{ number_format($submission->assignment->total_marks, 2) }}</p>
                            <p><strong>تاريخ الاستحقاق:</strong> {{ $submission->assignment->due_date->format('Y-m-d') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End::app-content -->
@stop

