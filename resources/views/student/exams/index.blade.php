@extends('student.layouts.master')

@section('title', 'الاختبارات المتاحة')

@section('content')
<div class="page-header">
    <h1 class="page-title">الاختبارات المتاحة</h1>
    <div class="page-subtitle">قائمة الاختبارات المتاحة لك</div>
</div>

<!-- فلتر الاختبارات -->
<div class="card mb-4">
    <div class="card-body">
        <form action="{{ route('student.exams.index') }}" method="GET" class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label>المادة</label>
                    <select name="subject_id" class="form-control">
                        <option value="">الكل</option>
                        @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}" {{ request('subject_id') == $subject->id ? 'selected' : '' }}>
                                {{ $subject->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="form-group">
                    <label>الحالة</label>
                    <select name="status" class="form-control">
                        <option value="">الكل</option>
                        <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>متاح</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>مكتمل</option>
                        <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>منتهي</option>
                    </select>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="form-group">
                    <label>&nbsp;</label>
                    <div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i> بحث
                        </button>
                        <a href="{{ route('student.exams.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> إلغاء
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- قائمة الاختبارات -->
<div class="row">
    @forelse($exams as $exam)
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card exam-card">
                <div class="card-header">
                    <h5 class="card-title">{{ $exam->title }}</h5>
                    @if($exam->is_published)
                        <span class="badge badge-success">منشور</span>
                    @else
                        <span class="badge badge-secondary">غير منشور</span>
                    @endif
                </div>
                <div class="card-body">
                    <p class="card-text">{{ Str::limit($exam->description, 100) }}</p>
                    
                    <ul class="exam-info">
                        <li>
                            <i class="fas fa-book"></i>
                            <span>المادة:</span>
                            <strong>{{ $exam->subject->name ?? '-' }}</strong>
                        </li>
                        <li>
                            <i class="fas fa-clock"></i>
                            <span>المدة:</span>
                            <strong>{{ $exam->duration }} دقيقة</strong>
                        </li>
                        <li>
                            <i class="fas fa-star"></i>
                            <span>الدرجة الكلية:</span>
                            <strong>{{ $exam->total_points }}</strong>
                        </li>
                        <li>
                            <i class="fas fa-question-circle"></i>
                            <span>عدد الأسئلة:</span>
                            <strong>{{ $exam->questions_count ?? 0 }}</strong>
                        </li>
                        <li>
                            <i class="fas fa-calendar"></i>
                            <span>تاريخ البدء:</span>
                            <strong>{{ $exam->start_at ? $exam->start_at->format('Y-m-d H:i') : '-' }}</strong>
                        </li>
                        <li>
                            <i class="fas fa-calendar-check"></i>
                            <span>تاريخ الانتهاء:</span>
                            <strong>{{ $exam->end_at ? $exam->end_at->format('Y-m-d H:i') : '-' }}</strong>
                        </li>
                    </ul>
                    
                    @if($exam->has_attempted)
                        <div class="alert alert-info mt-3">
                            <i class="fas fa-info-circle"></i>
                            لقد قمت بهذا الاختبار بالفعل
                        </div>
                    @endif
                </div>
                <div class="card-footer">
                    @if($exam->is_available && !$exam->has_attempted)
                        <a href="{{ route('student.exams.take', $exam) }}" class="btn btn-primary btn-block">
                            <i class="fas fa-play"></i> بدء الاختبار
                        </a>
                    @elseif($exam->has_attempted)
                        <a href="{{ route('student.exams.result', $exam->result_id) }}" class="btn btn-info btn-block">
                            <i class="fas fa-chart-line"></i> عرض النتيجة
                        </a>
                    @else
                        <button class="btn btn-secondary btn-block" disabled>
                            <i class="fas fa-lock"></i> غير متاح
                        </button>
                    @endif
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i> لا توجد اختبارات متاحة حالياً
            </div>
        </div>
    @endforelse
</div>

@if($exams->hasPages())
    <div class="pagination-wrapper">
        {{ $exams->links() }}
    </div>
@endif

<style>
.exam-card {
    height: 100%;
    transition: transform 0.3s;
}

.exam-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.exam-info {
    list-style: none;
    padding: 0;
    margin: 15px 0;
}

.exam-info li {
    padding: 5px 0;
    display: flex;
    align-items: center;
}

.exam-info i {
    margin-right: 10px;
    color: #6c757d;
}

.exam-info span {
    margin-right: 5px;
    color: #6c757d;
}

.exam-info strong {
    margin-right: auto;
}
</style>
@endsection
