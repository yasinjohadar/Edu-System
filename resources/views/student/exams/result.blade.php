@extends('student.layouts.master')

@section('title', 'نتيجة الاختبار')

@section('content')
<div class="page-header">
    <h1 class="page-title">نتيجة الاختبار</h1>
    <div class="page-subtitle">{{ $result->exam->title }}</div>
</div>

<!-- معلومات الاختبار -->
<div class="card mb-4">
    <div class="card-header">
        <h3 class="card-title">معلومات الاختبار</h3>
    </div>
    <div class="card-body">
        <table class="table table-bordered">
            <tr>
                <th>عنوان الاختبار:</th>
                <td>{{ $result->exam->title }}</td>
            </tr>
            <tr>
                <th>الكود:</th>
                <td>{{ $result->exam->exam_code }}</td>
            </tr>
            <tr>
                <th>المادة:</th>
                <td>{{ $result->exam->subject->name ?? '-' }}</td>
            </tr>
            <tr>
                <th>المدة:</th>
                <td>{{ $result->exam->duration }} دقيقة</td>
            </tr>
            <tr>
                <th>عدد الأسئلة:</th>
                <td>{{ $result->exam->questions_count ?? 0 }}</td>
            </tr>
            <tr>
                <th>الدرجة الكلية:</th>
                <td>{{ $result->exam->total_points }}</td>
            </tr>
        </table>
    </div>
</div>

<!-- النتيجة -->
<div class="card mb-4">
    <div class="card-header">
        <h3 class="card-title">النتيجة</h3>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-3">
                <div class="stat-box">
                    <h5>الدرجة</h5>
                    <h2>{{ $result->score }} / {{ $result->exam->total_points }}</h2>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-box">
                    <h5>النسبة المئوية</h5>
                    <h2>{{ number_format($result->percentage, 2) }}%</h2>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-box">
                    <h5>الحالة</h5>
                    <h2>
                        @if($result->status == 'passed')
                            <span class="text-success">ناجح</span>
                        @elseif($result->status == 'failed')
                            <span class="text-danger">راسب</span>
                        @else
                            <span class="text-warning">غائب</span>
                        @endif
                    </h2>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-box">
                    <h5>الوقت المستغرق</h5>
                    <h2>
                        @if($result->time_spent)
                            {{ floor($result->time_spent / 60) }} دقيقة
                            {{ $result->time_spent % 60 }} ثانية
                        @else
                            -
                        @endif
                    </h2>
                </div>
            </div>
        </div>
        
        <div class="progress mt-4" style="height: 30px;">
            <div class="progress-bar {{ $result->percentage >= 50 ? 'bg-success' : 'bg-danger' }}" 
                 role="progressbar" 
                 style="width: {{ $result->percentage }}%">
                {{ number_format($result->percentage, 2) }}%
            </div>
        </div>
        
        <div class="mt-3">
            <strong>تاريخ الإنجاز:</strong> {{ $result->completed_at ? $result->completed_at->format('Y-m-d H:i') : '-' }}
        </div>
    </div>
</div>

<!-- الإجابات -->
@if($result->exam->show_answers)
<div class="card">
    <div class="card-header">
        <h3 class="card-title">الإجابات</h3>
    </div>
    <div class="card-body">
        @forelse($result->answers as $answer)
            <div class="answer-item mb-4 p-4 border rounded">
                <h5>السؤال {{ $answer->question_order }}</h5>
                <p class="question-content">{{ $answer->question->content }}</p>
                
                <div class="answer-details">
                    <h6>إجابتك:</h6>
                    
                    @if($answer->question->type == 'multiple_choice')
                        <p>{{ $answer->selected_option?->option_text ?? 'لم يتم الاختيار' }}</p>
                        
                        <div class="correct-answer mt-2">
                            <strong>الإجابة الصحيحة:</strong>
                            <p>{{ $answer->question->correctOption?->option_text ?? '-' }}</p>
                        </div>
                    
                    @elseif($answer->question->type == 'true_false')
                        <p>{{ $answer->is_correct ? 'صواب' : 'خطأ' }}</p>
                        
                        <div class="correct-answer mt-2">
                            <strong>الإجابة الصحيحة:</strong>
                            <p>{{ $answer->question->booleanAnswer?->is_correct ? 'صواب' : 'خطأ' }}</p>
                        </div>
                    
                    @elseif($answer->question->type == 'essay')
                        <p>{{ $answer->text_answer ?? 'لم يتم الإجابة' }}</p>
                        
                        @if($answer->essayEvaluation)
                            <div class="essay-evaluation mt-3">
                                <h6>التقييم:</h6>
                                <p><strong>الدرجة:</strong> {{ $answer->essayEvaluation->score }}</p>
                                <p><strong>التعليق:</strong> {{ $answer->essayEvaluation->comments }}</p>
                            </div>
                        @endif
                    
                    @elseif($answer->question->type == 'fill_blanks')
                        <p>{{ $answer->text_answer ?? 'لم يتم الإجابة' }}</p>
                        
                        <div class="correct-answer mt-2">
                            <strong>الإجابة الصحيحة:</strong>
                            <p>{{ $answer->question->blanks->pluck('answer')->implode(', ') }}</p>
                        </div>
                    
                    @elseif($answer->question->type == 'matching')
                        <p>{{ $answer->text_answer ?? 'لم يتم الإجابة' }}</p>
                        
                        <div class="correct-answer mt-2">
                            <strong>الإجابة الصحيحة:</strong>
                            <ul>
                                @foreach($answer->question->matchingPairs as $pair)
                                    <li>{{ $pair->left_item }} = {{ $pair->right_item }}</li>
                                @endforeach
                            </ul>
                        </div>
                    
                    @elseif($answer->question->type == 'ordering')
                        <p>{{ $answer->text_answer ?? 'لم يتم الإجابة' }}</p>
                        
                        <div class="correct-answer mt-2">
                            <strong>الترتيب الصحيح:</strong>
                            <ol>
                                @foreach($answer->question->orderingItems->sortBy('correct_order') as $item)
                                    <li>{{ $item->item_text }}</li>
                                @endforeach
                            </ol>
                        </div>
                    
                    @else
                        <p>{{ $answer->text_answer ?? 'لم يتم الإجابة' }}</p>
                    @endif
                </div>
                
                <div class="score mt-2">
                    <strong>الدرجة:</strong> {{ $answer->score }} / {{ $answer->question->points }}
                </div>
            </div>
        @empty
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i> لا توجد إجابات
            </div>
        @endforelse
    </div>
</div>
@endif

<!-- أزرار الإجراءات -->
<div class="mt-4">
    <a href="{{ route('student.exams.index') }}" class="btn btn-primary">
        <i class="fas fa-arrow-left"></i> عودة للاختبارات
    </a>
    @if($result->exam->allow_review)
        <a href="{{ route('student.exams.review', $result) }}" class="btn btn-info">
            <i class="fas fa-eye"></i> مراجعة الاختبار
        </a>
    @endif
</div>

<style>
.stat-box {
    text-align: center;
    padding: 20px;
    background-color: #f8f9fa;
    border-radius: 8px;
}

.stat-box h5 {
    margin-bottom: 10px;
    color: #6c757d;
}

.stat-box h2 {
    margin: 0;
    color: #343a40;
}

.answer-item {
    background-color: #f8f9fa;
}

.question-content {
    font-size: 18px;
    font-weight: bold;
    margin-bottom: 15px;
}

.answer-details {
    margin-top: 15px;
}

.correct-answer {
    padding: 10px;
    background-color: #d4edda;
    border-left: 4px solid #28a745;
}

.essay-evaluation {
    padding: 10px;
    background-color: #fff3cd;
    border-left: 4px solid #ffc107;
}

.score {
    padding: 10px;
    background-color: #e9ecef;
    border-radius: 4px;
}
</style>
@endsection
