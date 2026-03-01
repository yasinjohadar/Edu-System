@extends('admin.layouts.master')

@section('title', 'إنشاء سؤال جديد')

@section('content')
<div class="page-wrapper">
    <div class="content-wrapper">
        <!-- Page Header -->
        <div class="page-header">
            <div class="page-title">
                <h1>إنشاء سؤال جديد</h1>
                <a href="{{ route('admin.questions.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i>
                    رجوع
                </a>
            </div>
        </div>

        <!-- Question Type Selection -->
        <div class="form-container">
            <form action="{{ route('admin.questions.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Question Type -->
                <div class="form-section">
                    <h3>نوع السؤال</h3>
                    <div class="form-row">
                        <div class="question-type-cards">
                            <div class="type-card" data-type="multiple_choice">
                                <i class="fas fa-list-ul"></i>
                                <span>اختيار من متعدد</span>
                            </div>
                            <div class="type-card" data-type="true_false">
                                <i class="fas fa-check-square"></i>
                                <span>صواب وخطأ</span>
                            </div>
                            <div class="type-card" data-type="essay">
                                <i class="fas fa-align-left"></i>
                                <span>مقال</span>
                            </div>
                            <div class="type-card" data-type="fill_blanks">
                                <i class="fas fa-pen"></i>
                                <span>ملء الفراغات</span>
                            </div>
                            <div class="type-card" data-type="matching">
                                <i class="fas fa-random"></i>
                                <span>مطابقة</span>
                            </div>
                            <div class="type-card" data-type="ordering">
                                <i class="fas fa-sort"></i>
                                <span>ترتيب</span>
                            </div>
                            <div class="type-card" data-type="classification">
                                <i class="fas fa-layer-group"></i>
                                <span>تصنيف</span>
                            </div>
                            <div class="type-card" data-type="drag_drop">
                                <i class="fas fa-hand-pointer"></i>
                                <span>سحب وإفلات</span>
                            </div>
                            <div class="type-card" data-type="hotspot">
                                <i class="fas fa-crosshairs"></i>
                                <span>نقاط ساخنة</span>
                            </div>
                            <div class="type-card" data-type="audio">
                                <i class="fas fa-volume-up"></i>
                                <span>صوتي</span>
                            </div>
                            <div class="type-card" data-type="video">
                                <i class="fas fa-video"></i>
                                <span>فيديو</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Basic Information -->
                <div class="form-section">
                    <h3>المعلومات الأساسية</h3>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="content">نص السؤال <span class="required">*</span></label>
                            <textarea id="content" name="content" class="form-control" rows="5" required placeholder="اكتب نص السؤال هنا..."></textarea>
                        </div>
                        <div class="form-group">
                            <label for="explanation">الشرح</label>
                            <textarea id="explanation" name="explanation" class="form-control" rows="3" placeholder="شرح السؤال (اختياري)..."></textarea>
                        </div>
                    </div>
                    <div class="form-row">
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
                            <label for="difficulty">الصعوبة</label>
                            <select id="difficulty" name="difficulty" class="form-control">
                                <option value="easy">سهل</option>
                                <option value="medium">متوسط</option>
                                <option value="hard">صعب</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="points">الدرجات <span class="required">*</span></label>
                            <input type="number" id="points" name="points" class="form-control" min="0" step="0.01" required>
                        </div>
                        <div class="form-group">
                            <label for="time_limit">الوقت المحدد (ثواني)</label>
                            <input type="number" id="time_limit" name="time_limit" class="form-control" min="0" placeholder="0 = بدون حد">
                        </div>
                        <div class="form-group">
                            <label for="tags">الكلمات المفتاحية</label>
                            <input type="text" id="tags" name="tags" class="form-control" placeholder="فصل الكلمات بفاصلة...">
                        </div>
                        <div class="form-group">
                            <label class="checkbox-label">
                                <input type="checkbox" id="is_active" name="is_active" value="1" checked>
                                سؤال نشط
                            </label>
                        </div>
                    </div>

                <!-- Type Specific Fields (will be shown based on selected type) -->
                <div id="type-specific-fields" class="form-section" style="display: none;">
                </div>

                <!-- Submit Button -->
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i>
                        حفظ السؤال
                    </button>
                    <a href="{{ route('admin.questions.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i>
                        إلغاء
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Question type selection
    document.querySelectorAll('.type-card').forEach(card => {
        card.addEventListener('click', function() {
            document.querySelectorAll('.type-card').forEach(c => c.classList.remove('selected'));
            this.classList.add('selected');
            
            const type = this.dataset.type;
            const specificFields = document.getElementById('type-specific-fields');
            specificFields.style.display = 'block';
            
            // Load type-specific form
            // This would typically be done via AJAX to load the appropriate form
            // For now, we'll show a placeholder
            specificFields.innerHTML = '<p class="text-center">سيتم تحميل نموذج ' + type + '...</p>';
        });
    });
</script>
@endpush
