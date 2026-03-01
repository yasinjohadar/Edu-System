@extends('admin.layouts.master')

@section('title', 'بنك الأسئلة')

@section('content')
<div class="page-wrapper">
    <div class="content-wrapper">
        <!-- Page Header -->
        <div class="page-header">
            <div class="page-title">
                <h1>بنك الأسئلة</h1>
                <a href="{{ route('admin.questions.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i>
                    سؤال جديد
                </a>
            </div>
        </div>

        <!-- Filters -->
        <div class="filters">
            <div class="filter-group">
                <label>بحث:</label>
                <input type="text" id="search" class="form-control" placeholder="ابحث بالسؤال..." value="{{ request('search') }}">
            </div>
            <div class="filter-group">
                <label>النوع:</label>
                <select id="type" class="form-control">
                    <option value="">الكل</option>
                    <option value="multiple_choice" {{ request('type') == 'multiple_choice' ? 'selected' : '' }}>اختيار من متعدد</option>
                    <option value="true_false" {{ request('type') == 'true_false' ? 'selected' : '' }}>صواب وخطأ</option>
                    <option value="essay" {{ request('type') == 'essay' ? 'selected' : '' }}>مقال</option>
                    <option value="fill_blanks" {{ request('type') == 'fill_blanks' ? 'selected' : '' }}>ملء الفراغات</option>
                    <option value="matching" {{ request('type') == 'matching' ? 'selected' : '' }}>مطابقة</option>
                    <option value="ordering" {{ request('type') == 'ordering' ? 'selected' : '' }}>ترتيب</option>
                    <option value="classification" {{ request('type') == 'classification' ? 'selected' : '' }}>تصنيف</option>
                    <option value="drag_drop" {{ request('type') == 'drag_drop' ? 'selected' : '' }}>سحب وإفلات</option>
                    <option value="hotspot" {{ request('type') == 'hotspot' ? 'selected' : '' }}>نقاط ساخنة</option>
                    <option value="audio" {{ request('type') == 'audio' ? 'selected' : '' }}>صوتي</option>
                    <option value="video" {{ request('type') == 'video' ? 'selected' : '' }}>فيديو</option>
                </select>
            </div>
            <div class="filter-group">
                <label>المادة:</label>
                <select id="subject_id" class="form-control">
                    <option value="">الكل</option>
                    @foreach($subjects as $subject)
                        <option value="{{ $subject->id }}" {{ request('subject_id') == $subject->id ? 'selected' : '' }}>{{ $subject->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="filter-group">
                <label>المرحلة:</label>
                <select id="grade_id" class="form-control">
                    <option value="">الكل</option>
                    @foreach($grades as $grade)
                        <option value="{{ $grade->id }}" {{ request('grade_id') == $grade->id ? 'selected' : '' }}>{{ $grade->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="filter-group">
                <label>الصعوبة:</label>
                <select id="difficulty" class="form-control">
                    <option value="">الكل</option>
                    <option value="easy" {{ request('difficulty') == 'easy' ? 'selected' : '' }}>سهل</option>
                    <option value="medium" {{ request('difficulty') == 'medium' ? 'selected' : '' }}>متوسط</option>
                    <option value="hard" {{ request('difficulty') == 'hard' ? 'selected' : '' }}>صعب</option>
                </select>
            </div>
            <div class="filter-group">
                <label>الحالة:</label>
                <select id="is_active" class="form-control">
                    <option value="">الكل</option>
                    <option value="1" {{ request('is_active') == '1' ? 'selected' : '' }}>نشط</option>
                    <option value="0" {{ request('is_active') == '0' ? 'selected' : '' }}>غير نشط</option>
                </select>
            </div>
            <button class="btn btn-secondary" onclick="applyFilters()">
                <i class="fas fa-filter"></i>
                تصفية
            </button>
        </div>

        <!-- Questions Table -->
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>الكود</th>
                        <th>السؤال</th>
                        <th>النوع</th>
                        <th>المادة</th>
                        <th>المرحلة</th>
                        <th>الصعوبة</th>
                        <th>الدرجات</th>
                        <th>الحالة</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($questions as $question)
                    <tr>
                        <td>{{ $question->question_code }}</td>
                        <td>{{ Str::limit($question->content, 100) }}...</td>
                        <td>
                            <span class="badge badge-{{ $question->type }}">
                                {{ $question->type_name }}
                            </span>
                        </td>
                        <td>{{ $question->subject->name ?? '-' }}</td>
                        <td>{{ $question->grade->name ?? '-' }}</td>
                        <td>
                            <span class="badge badge-{{ $question->difficulty }}">
                                {{ $question->difficulty_name }}
                            </span>
                        </td>
                        <td>{{ $question->points }}</td>
                        <td>
                            @if($question->is_active)
                                <span class="badge badge-success">نشط</span>
                            @else
                                <span class="badge badge-warning">غير نشط</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.questions.show', $question) }}" class="btn btn-sm btn-info">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.questions.edit', $question) }}" class="btn btn-sm btn-primary">
                                <i class="fas fa-edit"></i>
                            </a>
                            @if(!$question->examQuestions()->exists())
                                <form action="{{ route('admin.questions.destroy', $question) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('هل أنت متأكد من حذف هذا السؤال؟')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center">
                            لا توجد أسئلة
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($questions->hasPages())
            <div class="pagination">
                {{ $questions->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
    function applyFilters() {
        const search = document.getElementById('search').value;
        const type = document.getElementById('type').value;
        const subjectId = document.getElementById('subject_id').value;
        const gradeId = document.getElementById('grade_id').value;
        const difficulty = document.getElementById('difficulty').value;
        const isActive = document.getElementById('is_active').value;
        
        const url = new URL(window.location.href);
        url.searchParams.set('search', search);
        url.searchParams.set('type', type);
        url.searchParams.set('subject_id', subjectId);
        url.searchParams.set('grade_id', gradeId);
        url.searchParams.set('difficulty', difficulty);
        url.searchParams.set('is_active', isActive);
        
        window.location.href = url.toString();
    }
</script>
@endpush
