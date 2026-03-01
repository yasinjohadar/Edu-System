@extends('admin.layouts.master')

@section('title', 'تعديل الاختبار: ' . $exam->title)

@section('content')
<div class="page-wrapper">
    <div class="content-wrapper">
        <!-- Page Header -->
        <div class="page-header">
            <div class="page-title">
                <h1>تعديل الاختبار: {{ $exam->title }}</h1>
                <a href="{{ route('admin.exams.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i>
                    رجوع
                </a>
            </div>
        </div>

        <!-- Form -->
        <div class="form-container">
            <form action="{{ route('admin.exams.update', $exam) }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Basic Information -->
                <div class="form-section">
                    <h3>المعلومات الأساسية</h3>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="title">العنوان <span class="required">*</span></label>
                            <input type="text" id="title" name="title" class="form-control" value="{{ old('title', $exam->title) }}" required>
                        </div>
                        <div class="form-group">
                            <label for="description">الوصف</label>
                            <textarea id="description" name="description" class="form-control" rows="3">{{ old('description', $exam->description) }}</textarea>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="type">النوع</label>
                            <select id="type" name="type" class="form-control">
                                <option value="quiz" {{ $exam->type == 'quiz' ? 'selected' : '' }}>اختبار قصير</option>
                                <option value="exam" {{ $exam->type == 'exam' ? 'selected' : '' }}>امتحان</option>
                                <option value="midterm" {{ $exam->type == 'midterm' ? 'selected' : '' }}>امتحان نصفي</option>
                                <option value="final" {{ $exam->type == 'final' ? 'selected' : '' }}>امتحان نهائي</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="subject_id">المادة</label>
                            <select id="subject_id" name="subject_id" class="form-control">
                                <option value="">اختر المادة</option>
                                @foreach($subjects as $subject)
                                    <option value="{{ $subject->id }}" {{ old('subject_id', $exam->subject_id) == $subject->id ? 'selected' : '' }}>{{ $subject->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="grade_id">المرحلة</label>
                            <select id="grade_id" name="grade_id" class="form-control">
                                <option value="">اختر المرحلة</option>
                                @foreach($grades as $grade)
                                    <option value="{{ $grade->id }}" {{ old('grade_id', $exam->grade_id) == $grade->id ? 'selected' : '' }}>{{ $grade->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="section_id">الشعبة</label>
                            <select id="section_id" name="section_id" class="form-control">
                                <option value="">اختر الشعبة</option>
                                @foreach($sections as $section)
                                    <option value="{{ $section->id }}" {{ old('section_id', $exam->section_id) == $section->id ? 'selected' : '' }}>{{ $section->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="teacher_id">المعلم</label>
                            <select id="teacher_id" name="teacher_id" class="form-control">
                                <option value="">اختر المعلم</option>
                                @foreach($teachers as $teacher)
                                    <option value="{{ $teacher->id }}" {{ old('teacher_id', $exam->teacher_id) == $teacher->id ? 'selected' : '' }}>{{ $teacher->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Exam Settings -->
                    <div class="form-section">
                        <h3>إعدادات الاختبار</h3>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="duration">المدة (دقائق) <span class="required">*</span></label>
                                <input type="number" id="duration" name="duration" class="form-control" min="1" value="{{ old('duration', $exam->duration) }}" required>
                            </div>
                            <div class="form-group">
                                <label for="total_marks">الدرجة الكلية <span class="required">*</span></label>
                                <input type="number" id="total_marks" name="total_marks" class="form-control" min="1" step="0.01" value="{{ old('total_marks', $exam->total_marks) }}" required>
                            </div>
                            <div class="form-group">
                                <label for="passing_marks">الدرجة الناجحة <span class="required">*</span></label>
                                <input type="number" id="passing_marks" name="passing_marks" class="form-control" min="0" step="0.01" value="{{ old('passing_marks', $exam->passing_marks) }}" required>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="start_time">وقت البدء</label>
                                <input type="datetime-local" id="start_time" name="start_time" class="form-control" value="{{ old('start_time', $exam->start_time) }}">
                            </div>
                            <div class="form-group">
                                <label for="end_time">وقت الانتهاء</label>
                                <input type="datetime-local" id="end_time" name="end_time" class="form-control" value="{{ old('end_time', $exam->end_time) }}">
                            </div>
                        </div>
                    </div>

                    <!-- Display Options -->
                    <div class="form-section">
                        <h3>خيارات العرض</h3>
                        <div class="form-row">
                            <div class="form-group">
                                <label class="checkbox-label">
                                    <input type="checkbox" id="is_published" name="is_published" value="1" {{ $exam->is_published ? 'checked' : '' }}>
                                    نشر الاختبار فوراً
                                </label>
                            </div>
                            <div class="form-group">
                                <label class="checkbox-label">
                                    <input type="checkbox" id="is_active" name="is_active" value="1" {{ $exam->is_active ? 'checked' : '' }} checked>
                                    اختبار نشط
                                </label>
                            </div>
                            <div class="form-group">
                                <label class="checkbox-label">
                                    <input type="checkbox" id="allow_review" name="allow_review" value="1" {{ $exam->allow_review ? 'checked' : '' }}>
                                    السماح بمراجعة الإجابات
                                </label>
                            </div>
                            <div class="form-group">
                                <label class="checkbox-label">
                                    <input type="checkbox" id="show_results" name="show_results" value="1" {{ $exam->show_results ? 'checked' : '' }}>
                                    إظهار النتائج للطلاب
                                </label>
                            </div>
                            <div class="form-group">
                                <label class="checkbox-label">
                                    <input type="checkbox" id="show_answers" name="show_answers" value="1" {{ $exam->show_answers ? 'checked' : '' }}>
                                    إظهار الإجابات الصحيحة
                                </label>
                            </div>
                            <div class="form-group">
                                <label class="checkbox-label">
                                    <input type="checkbox" id="randomize_questions" name="randomize_questions" value="1" {{ $exam->randomize_questions ? 'checked' : '' }}>
                                    ترتيب عشوائي للأسئلة
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i>
                            حفظ التغييرات
                        </button>
                        <a href="{{ route('admin.exams.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i>
                            إلغاء
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Form validation
    document.querySelector('form').addEventListener('submit', function(e) {
        const title = document.getElementById('title').value;
        const type = document.getElementById('type').value;
        const duration = document.getElementById('duration').value;
        const totalMarks = document.getElementById('total_marks').value;
        const passingMarks = document.getElementById('passing_marks').value;
        
        if (!title || !type || !duration || !totalMarks || !passingMarks) {
            e.preventDefault();
            alert('يرجى ملء جميع الحقول المطلوبة');
            return false;
        }
        
        if (parseFloat(passingMarks) > parseFloat(totalMarks)) {
            e.preventDefault();
            alert('الدرجة الناجحة يجب أن تكون أقل من أو تساوي الدرجة الكلية');
            return false;
        }
        
        return true;
    });
</script>
@endpush
