@extends('admin.layouts.master')

@section('title', 'الاختبارات')

@section('content')
<div class="page-wrapper">
    <div class="content-wrapper">
        <!-- Page Header -->
        <div class="page-header">
            <div class="page-title">
                <h1>الاختبارات</h1>
                <a href="{{ route('admin.exams.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i>
                    اختبار جديد
                </a>
            </div>
        </div>

        <!-- Filters -->
        <div class="filters">
            <div class="filter-group">
                <label>بحث:</label>
                <input type="text" id="search" class="form-control" placeholder="ابحث بالاسم...">
            </div>
            <div class="filter-group">
                <label>النوع:</label>
                <select id="type" class="form-control">
                    <option value="">الكل</option>
                    <option value="quiz">اختبار قصير</option>
                    <option value="exam">امتحان</option>
                    <option value="midterm">امتحان نصفي</option>
                    <option value="final">امتحان نهائي</option>
                </select>
            </div>
            <div class="filter-group">
                <label>المادة:</label>
                <select id="subject_id" class="form-control">
                    <option value="">الكل</option>
                    @foreach($subjects as $subject)
                        <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="filter-group">
                <label>المرحلة:</label>
                <select id="grade_id" class="form-control">
                    <option value="">الكل</option>
                    @foreach($grades as $grade)
                        <option value="{{ $grade->id }}">{{ $grade->name }}</option>
                    @endforeach
                </select>
            </div>
            <button class="btn btn-secondary" onclick="applyFilters()">
                <i class="fas fa-filter"></i>
                تصفية
            </button>
        </div>

        <!-- Exams Table -->
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>الكود</th>
                        <th>العنوان</th>
                        <th>النوع</th>
                        <th>المادة</th>
                        <th>المرحلة</th>
                        <th>المدة</th>
                        <th>الدرجة الكلية</th>
                        <th>الدرجة الناجحة</th>
                        <th>وقت البدء</th>
                        <th>وقت الانتهاء</th>
                        <th>الحالة</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($exams as $exam)
                    <tr>
                        <td>{{ $exam->exam_code }}</td>
                        <td>{{ $exam->title }}</td>
                        <td>
                            <span class="badge badge-{{ $exam->type }}">
                                {{ $exam->type_name }}
                            </span>
                        </td>
                        <td>{{ $exam->subject->name ?? '-' }}</td>
                        <td>{{ $exam->grade->name ?? '-' }}</td>
                        <td>{{ $exam->duration }} دقيقة</td>
                        <td>{{ $exam->total_marks }}</td>
                        <td>{{ $exam->passing_marks }}</td>
                        <td>{{ $exam->start_time ? $exam->start_time->format('Y-m-d H:i') : '-' }}</td>
                        <td>{{ $exam->end_time ? $exam->end_time->format('Y-m-d H:i') : '-' }}</td>
                        <td>
                            @if($exam->is_published)
                                <span class="badge badge-success">منشور</span>
                            @else
                                <span class="badge badge-warning">مسودة</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.exams.edit', $exam) }}" class="btn btn-sm btn-info">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="{{ route('admin.exams.statistics', $exam) }}" class="btn btn-sm btn-primary">
                                <i class="fas fa-chart-bar"></i>
                            </a>
                            @if(!$exam->examResults()->exists())
                                <form action="{{ route('admin.exams.destroy', $exam) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('هل أنت متأكد من حذف هذا الاختبار؟')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="11" class="text-center">
                            لا توجد اختبارات
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($exams->hasPages())
            <div class="pagination">
                {{ $exams->links() }}
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
        
        const url = new URL(window.location.href);
        url.searchParams.set('search', search);
        url.searchParams.set('type', type);
        url.searchParams.set('subject_id', subjectId);
        url.searchParams.set('grade_id', gradeId);
        
        window.location.href = url.toString();
    }
</script>
@endpush
