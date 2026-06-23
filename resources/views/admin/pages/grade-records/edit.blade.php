@extends('admin.layouts.master')

@section('page-title')
    تعديل الدرجة
@stop

@section('content')
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li class="small">{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="main-content app-content">
        <div class="container-fluid">

            <div class="admin-page-header">
                <div class="page-title-wrap">
                    <h1>تعديل الدرجة</h1>
                    <p>{{ $gradeRecord->student->user->name ?? 'غير محدد' }} — {{ $gradeRecord->subject->name }}</p>
                </div>
                <a href="{{ route('admin.grade-records.index') }}" class="admin-btn admin-btn-secondary">
                    <i class="ri-arrow-right-line"></i>
                    العودة للقائمة
                </a>
            </div>

            <div class="admin-page-card">
                <form action="{{ route('admin.grade-records.update', $gradeRecord->id) }}" method="POST" class="admin-form" id="grade-record-edit-form">
                    @csrf
                    @method('PUT')

                    <div class="admin-form-body">
                        <div class="admin-form-section">
                            <div class="admin-form-section-head">
                                <div class="admin-section-icon admin-section-icon-blue">
                                    <i class="ri-user-line"></i>
                                </div>
                                <div>
                                    <h3>الطالب والمادة</h3>
                                    <p>بيانات أساسية للتقييم</p>
                                </div>
                            </div>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="admin-form-field">
                                        <label class="admin-form-label">الطالب</label>
                                        <input type="text" class="form-control" disabled
                                               value="{{ $gradeRecord->student->user->name ?? 'غير محدد' }} ({{ $gradeRecord->student->student_code }})">
                                        <small class="text-muted">لا يمكن تغيير الطالب</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="admin-form-field">
                                        <label class="admin-form-label">المادة الدراسية <span class="required">*</span></label>
                                        <select name="subject_id" class="form-select @error('subject_id') is-invalid @enderror" data-admin-choices required>
                                            @foreach ($subjects as $subject)
                                                <option value="{{ $subject->id }}" {{ old('subject_id', $gradeRecord->subject_id) == $subject->id ? 'selected' : '' }}>
                                                    {{ $subject->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('subject_id')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="admin-form-field">
                                        <label class="admin-form-label">المعلم</label>
                                        <select name="teacher_id" class="form-select" data-admin-choices>
                                            <option value="">اختر المعلم</option>
                                            @foreach ($teachers as $teacher)
                                                <option value="{{ $teacher->id }}" {{ old('teacher_id', $gradeRecord->teacher_id) == $teacher->id ? 'selected' : '' }}>
                                                    {{ $teacher->user->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="admin-form-field">
                                        <label class="admin-form-label">نوع التقييم <span class="required">*</span></label>
                                        <select name="exam_type" class="form-select @error('exam_type') is-invalid @enderror" data-admin-choices required>
                                            @foreach ($examTypes as $key => $name)
                                                <option value="{{ $key }}" {{ old('exam_type', $gradeRecord->exam_type) == $key ? 'selected' : '' }}>{{ $name }}</option>
                                            @endforeach
                                        </select>
                                        @error('exam_type')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="admin-form-section">
                            <div class="admin-form-section-head">
                                <div class="admin-section-icon admin-section-icon-green">
                                    <i class="ri-bar-chart-box-line"></i>
                                </div>
                                <div>
                                    <h3>الدرجات والتفاصيل</h3>
                                    <p>يتم حساب النسبة والدرجة الحرفية تلقائياً</p>
                                </div>
                            </div>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="admin-form-field">
                                        <label class="admin-form-label">اسم التقييم <span class="required">*</span></label>
                                        <input type="text" name="exam_name" class="form-control @error('exam_name') is-invalid @enderror"
                                               value="{{ old('exam_name', $gradeRecord->exam_name) }}" required>
                                        @error('exam_name')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="admin-form-field">
                                        <label class="admin-form-label">الدرجة المحصلة <span class="required">*</span></label>
                                        <input type="number" name="marks_obtained" class="form-control @error('marks_obtained') is-invalid @enderror"
                                               step="0.01" min="0" value="{{ old('marks_obtained', $gradeRecord->marks_obtained) }}" required>
                                        @error('marks_obtained')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="admin-form-field">
                                        <label class="admin-form-label">الدرجة الكلية <span class="required">*</span></label>
                                        <input type="number" name="total_marks" class="form-control @error('total_marks') is-invalid @enderror"
                                               step="0.01" min="0" value="{{ old('total_marks', $gradeRecord->total_marks) }}" required>
                                        @error('total_marks')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="admin-form-field">
                                        <label class="admin-form-label">تاريخ التقييم <span class="required">*</span></label>
                                        <input type="date" name="exam_date" class="form-control @error('exam_date') is-invalid @enderror"
                                               value="{{ old('exam_date', $gradeRecord->exam_date->format('Y-m-d')) }}" required>
                                        @error('exam_date')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="admin-form-field">
                                        <label class="admin-form-label">السنة الدراسية <span class="required">*</span></label>
                                        <input type="text" name="academic_year" class="form-control @error('academic_year') is-invalid @enderror"
                                               value="{{ old('academic_year', $gradeRecord->academic_year) }}" required>
                                        @error('academic_year')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="admin-form-field">
                                        <label class="admin-form-label">الفصل الدراسي <span class="required">*</span></label>
                                        <select name="semester" class="form-select @error('semester') is-invalid @enderror" data-admin-choices required>
                                            @foreach ($semesters as $key => $name)
                                                <option value="{{ $key }}" {{ old('semester', $gradeRecord->semester) == $key ? 'selected' : '' }}>{{ $name }}</option>
                                            @endforeach
                                        </select>
                                        @error('semester')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="admin-form-field">
                                        <label class="admin-form-label">ملاحظات</label>
                                        <textarea name="notes" class="form-control" rows="2">{{ old('notes', $gradeRecord->notes) }}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="admin-form-field">
                                        <label class="admin-form-label">حالة النشر</label>
                                        <select name="is_published" class="form-select" data-admin-choices>
                                            @php $publishedVal = old('is_published', $gradeRecord->is_published ? '1' : '0'); @endphp
                                            <option value="0" {{ $publishedVal == '0' ? 'selected' : '' }}>مسودة</option>
                                            <option value="1" {{ $publishedVal == '1' ? 'selected' : '' }}>منشور</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-3 p-3 rounded" style="background: rgba(59,130,246,0.06); border: 1px solid rgba(59,130,246,0.15);">
                                <small class="text-muted">
                                    الحالية: <strong>{{ number_format($gradeRecord->percentage, 2) }}%</strong> —
                                    الدرجة الحرفية: <span class="admin-badge admin-badge-role">{{ $gradeRecord->grade }}</span>
                                </small>
                            </div>
                        </div>
                    </div>

                    <div class="admin-form-footer">
                        <a href="{{ route('admin.grade-records.index') }}" class="admin-btn admin-btn-secondary">
                            <i class="ri-close-line"></i>
                            إلغاء
                        </a>
                        <button type="submit" class="admin-btn admin-btn-primary">
                            <i class="ri-save-line"></i>
                            حفظ التعديلات
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
@stop

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (window.AdminTables && AdminTables.initAdminForm) {
            AdminTables.initAdminForm(document.getElementById('grade-record-edit-form'));
        }

        const marksObtainedInput = document.querySelector('input[name="marks_obtained"]');
        const totalMarksInput = document.querySelector('input[name="total_marks"]');

        function validateMarks() {
            const totalMarks = parseFloat(totalMarksInput.value) || 0;
            const marksObtained = parseFloat(marksObtainedInput.value) || 0;
            if (marksObtained > totalMarks) {
                marksObtainedInput.setCustomValidity('لا يمكن أن تتجاوز الدرجة الكلية (' + totalMarks + ')');
            } else {
                marksObtainedInput.setCustomValidity('');
            }
        }

        marksObtainedInput?.addEventListener('input', validateMarks);
        totalMarksInput?.addEventListener('input', validateMarks);
    });
</script>
@endpush
