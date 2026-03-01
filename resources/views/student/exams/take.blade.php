@extends('student.layouts.master')

@section('title', 'أداء الاختبار')

@push('styles')
<style>
.question-card {
    margin-bottom: 20px;
    padding: 20px;
    border: 1px solid #dee2e6;
    border-radius: 8px;
    background-color: #fff;
}

.question-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
    padding-bottom: 10px;
    border-bottom: 1px solid #dee2e6;
}

.question-content {
    font-size: 18px;
    font-weight: bold;
    margin-bottom: 20px;
}

.question-timer {
    position: fixed;
    top: 60px;
    left: 20px;
    background-color: #fff;
    padding: 15px;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    z-index: 1000;
}

.question-timer.warning {
    background-color: #fff3cd;
    border: 2px solid #ffc107;
}

.question-timer.danger {
    background-color: #f8d7da;
    border: 2px solid #dc3545;
}

.question-timer h4 {
    margin: 0;
    font-size: 24px;
}

.question-timer .time-remaining {
    font-size: 32px;
    font-weight: bold;
    color: #28a745;
}

.question-timer.warning .time-remaining {
    color: #ffc107;
}

.question-timer.danger .time-remaining {
    color: #dc3545;
}

.question-navigation {
    position: fixed;
    top: 200px;
    left: 20px;
    background-color: #fff;
    padding: 15px;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    z-index: 1000;
}

.question-navigation h5 {
    margin-top: 0;
}

.question-nav-buttons {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 5px;
    margin-top: 10px;
}

.question-nav-btn {
    padding: 8px;
    text-align: center;
    border: 1px solid #dee2e6;
    border-radius: 4px;
    background-color: #f8f9fa;
    cursor: pointer;
    transition: all 0.3s;
}

.question-nav-btn:hover {
    background-color: #e9ecef;
}

.question-nav-btn.answered {
    background-color: #28a745;
    color: white;
    border-color: #28a745;
}

.question-nav-btn.current {
    background-color: #007bff;
    color: white;
    border-color: #007bff;
}

.question-nav-btn.answered.current {
    background-color: #0056b3;
}

.exam-actions {
    position: fixed;
    bottom: 0;
    left: 0;
    right: 0;
    background-color: #fff;
    padding: 15px;
    border-top: 1px solid #dee2e6;
    box-shadow: 0 -2px 8px rgba(0,0,0,0.1);
    z-index: 1000;
}

.exam-actions .container {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.option-item {
    padding: 10px;
    margin-bottom: 10px;
    border: 1px solid #dee2e6;
    border-radius: 4px;
    cursor: pointer;
    transition: all 0.3s;
}

.option-item:hover {
    background-color: #f8f9fa;
}

.option-item.selected {
    background-color: #007bff;
    color: white;
    border-color: #007bff;
}

.blank-input {
    display: inline-block;
    width: 150px;
    margin: 0 5px;
    padding: 5px;
    border: 1px solid #ced4da;
    border-radius: 4px;
}

.matching-item {
    padding: 10px;
    margin-bottom: 10px;
    border: 1px solid #dee2e6;
    border-radius: 4px;
    background-color: #f8f9fa;
}

.ordering-item {
    padding: 10px;
    margin-bottom: 10px;
    border: 1px solid #dee2e6;
    border-radius: 4px;
    background-color: #f8f9fa;
    cursor: move;
}

.classification-category {
    margin-bottom: 20px;
    padding: 15px;
    border: 1px solid #dee2e6;
    border-radius: 8px;
    background-color: #f8f9fa;
}

.classification-category h6 {
    margin-top: 0;
    margin-bottom: 10px;
}

.classification-item {
    padding: 8px;
    margin-bottom: 5px;
    border: 1px solid #dee2e6;
    border-radius: 4px;
    cursor: move;
}
</style>
@endpush

@section('content')
<div class="page-header">
    <h1 class="page-title">{{ $exam->title }}</h1>
    <div class="page-subtitle">{{ $exam->description }}</div>
</div>

<!-- مؤقت الاختبار -->
<div class="question-timer" id="examTimer">
    <h4>الوقت المتبقي</h4>
    <div class="time-remaining" id="timeRemaining">
        {{ $exam->duration }}:00
    </div>
</div>

<!-- التنقل بين الأسئلة -->
<div class="question-navigation">
    <h5>الأسئلة</h5>
    <div class="question-nav-buttons" id="questionNavButtons">
        @foreach($questions as $index => $question)
            <div class="question-nav-btn" data-question="{{ $index }}" onclick="goToQuestion({{ $index }})">
                {{ $index + 1 }}
            </div>
        @endforeach
    </div>
</div>

<!-- الأسئلة -->
<form id="examForm" action="{{ route('student.exams.submit', $exam) }}" method="POST" class="mt-4">
    @csrf
    
    <div class="container" style="margin-left: 200px;">
        @foreach($questions as $index => $question)
            <div class="question-card" id="question-{{ $index }}" style="display: {{ $index == 0 ? 'block' : 'none' }};">
                <div class="question-header">
                    <h5>السؤال {{ $index + 1 }}</h5>
                    <span class="badge badge-primary">{{ $question->points }} نقطة</span>
                </div>
                
                <div class="question-content">
                    {{ $question->content }}
                </div>
                
                <!-- نوع السؤال: اختيار من متعدد -->
                @if($question->type == 'multiple_choice')
                    <div class="question-options">
                        @foreach($question->options as $option)
                            <div class="option-item" onclick="selectOption(this, {{ $index }}, {{ $option->id }})">
                                <input type="radio" name="answers[{{ $question->id }}]" 
                                       id="option-{{ $option->id }}" 
                                       value="{{ $option->id }}" 
                                       style="display: none;">
                                <label for="option-{{ $option->id }}">
                                    <strong>{{ chr(65 + $loop->index) }}.</strong> {{ $option->option_text }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                
                <!-- نوع السؤال: صواب وخطأ -->
                @elseif($question->type == 'true_false')
                    <div class="question-options">
                        <div class="option-item" onclick="selectOption(this, {{ $index }}, 'true')">
                            <input type="radio" name="answers[{{ $question->id }}]" 
                                   id="true-{{ $question->id }}" 
                                   value="true" 
                                   style="display: none;">
                            <label for="true-{{ $question->id }}">
                                <strong>صواب</strong>
                            </label>
                        </div>
                        <div class="option-item" onclick="selectOption(this, {{ $index }}, 'false')">
                            <input type="radio" name="answers[{{ $question->id }}]" 
                                   id="false-{{ $question->id }}" 
                                   value="false" 
                                   style="display: none;">
                            <label for="false-{{ $question->id }}">
                                <strong>خطأ</strong>
                            </label>
                        </div>
                    </div>
                
                <!-- نوع السؤال: مقال -->
                @elseif($question->type == 'essay')
                    <div class="question-essay">
                        <textarea name="answers[{{ $question->id }}]" 
                                  class="form-control" 
                                  rows="10" 
                                  placeholder="اكتب إجابتك هنا..."
                                  oninput="markAnswered({{ $index }})"></textarea>
                        
                        @if($question->essayQuestion?->allow_attachments)
                            <div class="mt-3">
                                <label>إرفاق ملفات (اختياري)</label>
                                <input type="file" name="attachments[{{ $question->id }}][]" 
                                       class="form-control" 
                                       multiple>
                            </div>
                        @endif
                    </div>
                
                <!-- نوع السؤال: ملء الفراغات -->
                @elseif($question->type == 'fill_blanks')
                    <div class="question-blanks">
                        <p>
                            @php
                                $content = $question->content;
                                $blanks = $question->blanks;
                                foreach($blanks as $blank) {
                                    $content = str_replace('____', 
                                        '<input type="text" 
                                               class="blank-input" 
                                               name="answers[' . $question->id . '][]" 
                                               placeholder="الفراغ ' . ($blank->blank_order + 1) . '"
                                               oninput="markAnswered(' . $index . ')">', 
                                        $content);
                                }
                            @endphp
                            {!! $content !!}
                        </p>
                    </div>
                
                <!-- نوع السؤال: مطابقة -->
                @elseif($question->type == 'matching')
                    <div class="question-matching">
                        <div class="row">
                            <div class="col-md-6">
                                <h6>المطابقات</h6>
                                @foreach($question->matchingPairs as $pair)
                                    <div class="matching-item">
                                        <strong>{{ $loop->index + 1 }}.</strong> {{ $pair->left_item }}
                                    </div>
                                @endforeach
                            </div>
                            <div class="col-md-6">
                                <h6>الإجابات</h6>
                                @foreach($question->matchingPairs as $pair)
                                    <select name="answers[{{ $question->id }}][{{ $pair->id }}]" 
                                            class="form-control mb-2"
                                            onchange="markAnswered({{ $index }})">
                                        <option value="">اختر الإجابة</option>
                                        @foreach($question->matchingPairs as $option)
                                            <option value="{{ $option->id }}">{{ $option->right_item }}</option>
                                        @endforeach
                                    </select>
                                @endforeach
                            </div>
                        </div>
                    </div>
                
                <!-- نوع السؤال: ترتيب -->
                @elseif($question->type == 'ordering')
                    <div class="question-ordering">
                        <div id="ordering-{{ $index }}" class="ordering-container">
                            @foreach($question->orderingItems as $item)
                                <div class="ordering-item" data-id="{{ $item->id }}">
                                    {{ $item->item_text }}
                                </div>
                            @endforeach
                        </div>
                        <input type="hidden" name="answers[{{ $question->id }}]" id="ordering-answer-{{ $index }}">
                    </div>
                
                <!-- نوع السؤال: تصنيف -->
                @elseif($question->type == 'classification')
                    <div class="question-classification">
                        <div class="row">
                            @foreach($question->categories as $category)
                                <div class="col-md-6">
                                    <div class="classification-category">
                                        <h6>{{ $category->name }}</h6>
                                        <div id="classification-{{ $category->id }}-{{ $index }}">
                                            <!-- العناصر ستضاف هنا عبر JavaScript -->
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <div class="classification-items mt-3">
                            <h6>العناصر</h6>
                            @foreach($question->classificationItems as $item)
                                <div class="classification-item" data-id="{{ $item->id }}">
                                    {{ $item->item_text }}
                                </div>
                            @endforeach
                        </div>
                    </div>
                
                <!-- أنواع الأسئلة الأخرى -->
                @else
                    <div class="question-other">
                        <textarea name="answers[{{ $question->id }}]" 
                                  class="form-control" 
                                  rows="5" 
                                  placeholder="اكتب إجابتك هنا..."
                                  oninput="markAnswered({{ $index }})"></textarea>
                    </div>
                @endif
            </div>
        @endforeach
    </div>
</form>

<!-- أزرار الإجراءات -->
<div class="exam-actions">
    <div class="container">
        <div>
            <button type="button" class="btn btn-secondary" id="prevBtn" onclick="prevQuestion()" disabled>
                <i class="fas fa-arrow-right"></i> السؤال السابق
            </button>
            <button type="button" class="btn btn-primary" id="nextBtn" onclick="nextQuestion()">
                السؤال التالي <i class="fas fa-arrow-left"></i>
            </button>
        </div>
        
        <button type="submit" class="btn btn-success" id="submitBtn" onclick="submitExam()">
            <i class="fas fa-check"></i> إنهاء الاختبار
        </button>
    </div>
</div>

@push('scripts')
<script>
let currentQuestion = 0;
let totalQuestions = {{ count($questions) }};
let timeRemaining = {{ $exam->duration * 60 }}; // بالثواني
let timerInterval;

// بدء المؤقت
function startTimer() {
    timerInterval = setInterval(function() {
        timeRemaining--;
        updateTimerDisplay();
        
        if (timeRemaining <= 0) {
            clearInterval(timerInterval);
            submitExam();
        }
    }, 1000);
}

// تحديث عرض المؤقت
function updateTimerDisplay() {
    const minutes = Math.floor(timeRemaining / 60);
    const seconds = timeRemaining % 60;
    const timerDisplay = document.getElementById('timeRemaining');
    const timerContainer = document.getElementById('examTimer');
    
    timerDisplay.textContent = minutes + ':' + (seconds < 10 ? '0' : '') + seconds;
    
    if (timeRemaining <= 300) { // 5 دقائق
        timerContainer.classList.add('warning');
    }
    
    if (timeRemaining <= 60) { // 1 دقيقة
        timerContainer.classList.remove('warning');
        timerContainer.classList.add('danger');
    }
}

// الانتقال إلى سؤال معين
function goToQuestion(index) {
    // إخفاء السؤال الحالي
    document.getElementById('question-' + currentQuestion).style.display = 'none';
    document.querySelector('.question-nav-btn[data-question="' + currentQuestion + '"]').classList.remove('current');
    
    // عرض السؤال الجديد
    currentQuestion = index;
    document.getElementById('question-' + currentQuestion).style.display = 'block';
    document.querySelector('.question-nav-btn[data-question="' + currentQuestion + '"]').classList.add('current');
    
    // تحديث أزرار التنقل
    document.getElementById('prevBtn').disabled = (currentQuestion === 0);
    document.getElementById('nextBtn').disabled = (currentQuestion === totalQuestions - 1);
}

// السؤال السابق
function prevQuestion() {
    if (currentQuestion > 0) {
        goToQuestion(currentQuestion - 1);
    }
}

// السؤال التالي
function nextQuestion() {
    if (currentQuestion < totalQuestions - 1) {
        goToQuestion(currentQuestion + 1);
    }
}

// تحديد خيار
function selectOption(element, questionIndex, value) {
    const options = element.parentElement.querySelectorAll('.option-item');
    options.forEach(opt => opt.classList.remove('selected'));
    element.classList.add('selected');
    
    const radio = element.querySelector('input[type="radio"]');
    radio.checked = true;
    
    markAnswered(questionIndex);
}

// وضع علامة على السؤال كمجاب
function markAnswered(questionIndex) {
    const navBtn = document.querySelector('.question-nav-btn[data-question="' + questionIndex + '"]');
    if (navBtn) {
        navBtn.classList.add('answered');
    }
}

// تقديم الاختبار
function submitExam() {
    if (confirm('هل أنت متأكد من إنهاء الاختبار؟ لا يمكن التراجع بعد التقديم.')) {
        clearInterval(timerInterval);
        document.getElementById('examForm').submit();
    }
}

// بدء المؤقت عند تحميل الصفحة
window.onload = function() {
    startTimer();
    markAnswered(0); // وضع علامة على السؤال الأول
};

// منع مغادرة الصفحة
window.onbeforeunload = function() {
    if (timeRemaining > 0) {
        return 'هل أنت متأكد من مغادرة الصفحة؟ سيتم فقدان إجاباتك.';
    }
};
</script>
@endpush
@endsection
