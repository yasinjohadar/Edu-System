@extends('admin.layouts.master')

@section('page-title')
    تعديل الدرجة
@stop

@section('content')
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Start::app-content -->
    <div class="main-content app-content">
        <div class="container-fluid">
            <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
                <div class="my-auto">
                    <h5 class="page-title fs-21 mb-1">تعديل الدرجة</h5>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">معلومات الدرجة</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.grade-records.update', $gradeRecord->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">الطالب</label>
                                        <input type="text" class="form-control" value="{{ $gradeRecord->student->user->name ?? 'غير محدد' }} ({{ $gradeRecord->student->student_code }})" disabled>
                                        <small class="text-muted">لا يمكن تغيير الطالب</small>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">المادة الدراسية <span class="text-danger">*</span></label>
                                        <select name="subject_id" class="form-select" required>
                                            @foreach($subjects as $subject)
                                                <option value="{{ $subject->id }}" {{ old('subject_id', $gradeRecord->subject_id) == $subject->id ? 'selected' : '' }}>
                                                    {{ $subject->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('subject_id')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">المعلم</label>
                                        <select name="teacher_id" class="form-select">
                                            <option value="">-- اختر المعلم --</option>
                                            @foreach($teachers as $teacher)
                                                <option value="{{ $teacher->id }}" {{ old('teacher_id', $gradeRecord->teacher_id) == $teacher->id ? 'selected' : '' }}>
                                                    {{ $teacher->user->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">نوع التقييم <span class="text-danger">*</span></label>
                                        <select name="exam_type" class="form-select" required>
                                            @foreach($examTypes as $key => $name)
                                                <option value="{{ $key }}" {{ old('exam_type', $gradeRecord->exam_type) == $key ? 'selected' : '' }}>
                                                    {{ $name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('exam_type')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">اسم التقييم <span class="text-danger">*</span></label>
                                        <input type="text" name="exam_name" class="form-control" value="{{ old('exam_name', $gradeRecord->exam_name) }}" required>
                                        @error('exam_name')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">الدرجة المحصل عليها <span class="text-danger">*</span></label>
                                        <input type="number" name="marks_obtained" class="form-control" step="0.01" min="0" value="{{ old('marks_obtained', $gradeRecord->marks_obtained) }}" required>
                                        @error('marks_obtained')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">الدرجة الكلية <span class="text-danger">*</span></label>
                                        <input type="number" name="total_marks" class="form-control" step="0.01" min="0" value="{{ old('total_marks', $gradeRecord->total_marks) }}" required>
                                        @error('total_marks')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">تاريخ التقييم <span class="text-danger">*</span></label>
                                        <input type="date" name="exam_date" class="form-control" value="{{ old('exam_date', $gradeRecord->exam_date->format('Y-m-d')) }}" required>
                                        @error('exam_date')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">السنة الدراسية <span class="text-danger">*</span></label>
                                        <input type="text" name="academic_year" class="form-control" value="{{ old('academic_year', $gradeRecord->academic_year) }}" required>
                                        @error('academic_year')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">الفصل الدراسي <span class="text-danger">*</span></label>
                                        <select name="semester" class="form-select" required>
                                            @foreach($semesters as $key => $name)
                                                <option value="{{ $key }}" {{ old('semester', $gradeRecord->semester) == $key ? 'selected' : '' }}>
                                                    {{ $name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('semester')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">ملاحظات</label>
                                        <textarea name="notes" class="form-control" rows="3">{{ old('notes', $gradeRecord->notes) }}</textarea>
                                        @error('notes')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">الحالة</label>
                                        <select name="is_published" class="form-select">
                                            <option value="0" {{ old('is_published', $gradeRecord->is_published) == '0' ? 'selected' : '' }}>مسودة</option>
                                            <option value="1" {{ old('is_published', $gradeRecord->is_published) == '1' ? 'selected' : '' }}>منشور</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="alert alert-info">
                                    <strong>النسبة المئوية والدرجة الحرفية:</strong> سيتم حسابها تلقائياً عند الحفظ بناءً على الدرجة المحصل عليها والدرجة الكلية.
                                </div>

                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-left: 5px; vertical-align: middle;">
                                            <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                                            <polyline points="17 21 17 13 7 13 7 21"></polyline>
                                            <polyline points="7 3 7 8 15 8"></polyline>
                                        </svg>
                                        تحديث
                                    </button>
                                    <a href="{{ route('admin.grade-records.index') }}" class="btn btn-secondary">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-left: 5px; vertical-align: middle;">
                                            <line x1="18" y1="6" x2="6" y2="18"></line>
                                            <line x1="6" y1="6" x2="18" y2="18"></line>
                                        </svg>
                                        إلغاء
                                    </a>
                                </div>
                            </form>
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
    const marksObtainedInput = document.querySelector('input[name="marks_obtained"]');
    const totalMarksInput = document.querySelector('input[name="total_marks"]');
    
    function validateMarks() {
        const totalMarks = parseFloat(totalMarksInput.value) || 0;
        const marksObtained = parseFloat(marksObtainedInput.value) || 0;
        
        if (marksObtained > totalMarks) {
            marksObtainedInput.setCustomValidity('الدرجة المحصل عليها لا يمكن أن تتجاوز الدرجة الكلية (' + totalMarks + ')');
        } else {
            marksObtainedInput.setCustomValidity('');
        }
    }
    
    marksObtainedInput.addEventListener('input', validateMarks);
    totalMarksInput.addEventListener('input', validateMarks);
});
</script>
@endpush

