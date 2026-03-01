@extends('admin.layouts.master')

@section('title', 'إنشاء اختبار جديد')

@section('content')
<div class="page-wrapper">
    <div class="content-wrapper">
        <!-- Page Header -->
        <div class="page-header">
            <div class="page-title">
                <h1>إنشاء اختبار جديد</h1>
                <a href="{{ route('admin.exams.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i>
                    رجوع
                </a>
            </div>
        </div>

        <!-- Form -->
        <div class="form-container">
            <form action="{{ route('admin.exams.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Basic Information -->
                <div class="form-section">
                    <h3>المعلومات الأساسية</h3>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="title">العنوان <span class="required">*</span></label>
                            <input type="text" id="title" name="title" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="description">الوصف</label>
                            <textarea id="description" name="description" class="form-control" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="type">النوع <span class="required">*</span></label>
                            <select id="type" name="type" class="form-control" required>
                                <option value="">اختر النوع</option>
                                <option value="quiz">اختبار قصير</option>
                                <option value="exam">امتحان</option>
                                <option value="midterm">امتحان نصفي</option>
                                <option value="final">امتحان نهائي</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="subject_id">المادة</label>
                            <select id="subject_id" name="subject_id" class="form-control">
                                <option value="">اختر المادة</option>
                                @foreach($subjects as $subject)
                                    <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="grade_id">المرحلة</label>
                            <select id="grade_id" name="grade_id" class="form-control">
                                <option value="">اختر المرحلة</option>
                                @foreach($grades as $grade)
                                    <option value="{{ $grade->id }}">{{ $grade->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="section_id">الشعبة</label>
                            <select id="section_id" name="section_id" class="form-control">
                                <option value="">اختر الشعبة</option>
                                @foreach($sections as $section)
                                    <option value="{{ $section->id }}">{{ $section->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="teacher_id">المعلم</label>
                            <select id="teacher_id" name="teacher_id" class="form-control">
                                <option value="">اختر المعلم</option>
                                @foreach($teachers as $teacher)
                                    <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
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
                                <input type="number" id="duration" name="duration" class="form-control" min="1" required>
                            </div>
                            <div class="form-group">
                                <label for="total_marks">الدرجة الكلية <span class="required">*</span></label>
                                <input type="number" id="total_marks" name="total_marks" class="form-control" min="1" step="0.01" required>
                            </div>
                            <div class="form-group">
                                <label for="passing_marks">الدرجة الناجحة <span class="required">*</span></label>
                                <input type="number" id="passing_marks" name="passing_marks" class="form-control" min="0" step="0.01" required>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="start_time">وقت البدء</label>
                                <input type="datetime-local" id="start_time" name="start_time" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="end_time">وقت الانتهاء</label>
                                <input type="datetime-local" id="end_time" name="end_time" class="form-control">
                            </div>
                        </div>
                    </div>

                    <!-- Display Options -->
                    <div class="form-section">
                        <h3>خيارات العرض</h3>
                        <div class="form-row">
                            <div class="form-group">
                                <label class="checkbox-label">
                                    <input type="checkbox" id="is_published" name="is_published" value="1">
                                    نشر الاختبار فوراً
                                </label>
                            </div>
                            <div class="form-group">
                                <label class="checkbox-label">
                                    <input type="checkbox" id="is_active" name="is_active" value="1" checked>
                                    اختبار نشط
                                </label>
                            </div>
                            <div class="form-group">
                                <label class="checkbox-label">
                                    <input type="checkbox" id="allow_review" name="allow_review" value="1">
                                    السماح بمراجعة الإجابات
                                </label>
                            </div>
                            <div class="form-group">
                                <label class="checkbox-label">
                                    <input type="checkbox" id="show_results" name="show_results" value="1">
                                    إظهار النتائج للطلاب
                                </label>
                            </div>
                            <div class="form-group">
                                <label class="checkbox-label">
                                    <input type="checkbox" id="show_answers" name="show_answers" value="1">
                                    إظهار الإجابات الصحيحة
                                </label>
                            </div>
                            <div class="form-group">
                                <label class="checkbox-label">
                                    <input type="checkbox" id="randomize_questions" name="randomize_questions" value="1">
                                    ترتيب عشوائي للأسئلة
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i>
                            حفظ الاختبار
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
