@extends('admin.layouts.master')

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
                    <a href="{{ route('admin.assignments.submissions.index', $assignment->id) }}" class="btn btn-secondary btn-sm">رجوع</a>
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
                                    <strong>الطالب:</strong> {{ $submission->student->user->name ?? 'غير محدد' }}
                                    <br><small class="text-muted">{{ $submission->student->student_code }}</small>
                                </div>
                                <div class="col-md-6">
                                    <strong>المحاولة:</strong> <span class="badge bg-info">{{ $submission->attempt_number }}</span>
                                    @if($submission->is_resubmission)
                                        <br><small class="text-muted">إعادة تسليم</small>
                                    @endif
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <strong>تاريخ التسليم:</strong> {{ $submission->submitted_at->format('Y-m-d H:i') }}
                                    @if($submission->is_late)
                                        <br><small class="text-danger">متأخر {{ $submission->days_late }} يوم</small>
                                    @endif
                                </div>
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
                            </div>
                            @if($submission->student_notes)
                            <div class="mb-3">
                                <strong>ملاحظات الطالب:</strong>
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
                                            <span class="badge bg-secondary">{{ $file->file_category_name }}</span>
                                        </div>
                                    @endforeach
                                </div>
                                <a href="{{ route('admin.assignments.submissions.download-files', [$assignment->id, $submission->id]) }}" class="btn btn-sm btn-primary mt-2">
                                    <i class="fa-solid fa-download"></i> تحميل جميع الملفات (ZIP)
                                </a>
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
                                            <span class="badge bg-info ms-2">{{ $link->link_type_name }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

                    @if($submission->status != 'graded')
                    <div class="card mt-3" id="grade">
                        <div class="card-header">
                            <h5 class="card-title">تصحيح الواجب</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.assignments.submissions.grade', [$assignment->id, $submission->id]) }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label">الدرجة المحصل عليها <span class="text-danger">*</span></label>
                                    <input type="number" name="marks_obtained" class="form-control" step="0.01" min="0" max="{{ $assignment->total_marks }}" value="{{ old('marks_obtained', $submission->marks_obtained) }}" required>
                                    <small class="text-muted">من أصل {{ number_format($assignment->total_marks, 2) }}</small>
                                    @error('marks_obtained')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">ملاحظات مختصرة</label>
                                    <textarea name="feedback" class="form-control" rows="3">{{ old('feedback', $submission->feedback) }}</textarea>
                                    @error('feedback')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">ملاحظات مفصلة</label>
                                    <textarea name="teacher_notes" class="form-control" rows="5">{{ old('teacher_notes', $submission->teacher_notes) }}</textarea>
                                    @error('teacher_notes')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="requires_resubmission" value="1" id="requires_resubmission" {{ old('requires_resubmission', $submission->requires_resubmission) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="requires_resubmission">طلب إعادة التسليم</label>
                                    </div>
                                </div>
                                <div class="mb-3" id="resubmission_reason_div" style="display: none;">
                                    <label class="form-label">سبب طلب الإعادة</label>
                                    <textarea name="resubmission_reason" class="form-control" rows="3">{{ old('resubmission_reason', $submission->resubmission_reason) }}</textarea>
                                    @error('resubmission_reason')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa-solid fa-check"></i> حفظ التصحيح
                                </button>
                            </form>
                        </div>
                    </div>
                    @else
                    <div class="card mt-3">
                        <div class="card-header">
                            <h5 class="card-title">نتيجة التصحيح</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <strong>الدرجة المحصل عليها:</strong>
                                <h4>{{ number_format($submission->marks_obtained, 2) }} / {{ number_format($assignment->total_marks, 2) }}</h4>
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
                                <br><small class="text-muted">بواسطة: {{ $submission->grader->name ?? 'غير محدد' }}</small>
                            </div>
                            @endif
                            @if($submission->requires_resubmission)
                            <div class="alert alert-warning">
                                <strong>طلب إعادة التسليم:</strong>
                                <p>{{ $submission->resubmission_reason }}</p>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>

                <div class="col-xl-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">معلومات الواجب</h5>
                        </div>
                        <div class="card-body">
                            <p><strong>العنوان:</strong> {{ $assignment->title }}</p>
                            <p><strong>المادة:</strong> {{ $assignment->subject->name ?? 'غير محدد' }}</p>
                            <p><strong>الدرجة الكلية:</strong> {{ number_format($assignment->total_marks, 2) }}</p>
                            <p><strong>تاريخ الاستحقاق:</strong> {{ $assignment->due_date->format('Y-m-d') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End::app-content -->
@stop

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const requiresResubmission = document.getElementById('requires_resubmission');
    const resubmissionReasonDiv = document.getElementById('resubmission_reason_div');
    
    if (requiresResubmission && resubmissionReasonDiv) {
        requiresResubmission.addEventListener('change', function() {
            resubmissionReasonDiv.style.display = this.checked ? 'block' : 'none';
        });
        
        // Set initial state
        resubmissionReasonDiv.style.display = requiresResubmission.checked ? 'block' : 'none';
    }
});
</script>
@endpush

