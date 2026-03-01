@extends('admin.layouts.master')

@section('title', 'نتائج الاختبارات')

@section('content')
<div class="page-header">
    <h1 class="page-title">نتائج الاختبارات</h1>
    <div class="page-subtitle">عرض وإدارة نتائج الاختبارات</div>
</div>

<!-- فلتر النتائج -->
<div class="card mb-4">
    <div class="card-body">
        <form action="{{ route('admin.exam-results.index') }}" method="GET" class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <label>الاختبار</label>
                    <select name="exam_id" class="form-control">
                        <option value="">الكل</option>
                        @foreach($exams as $exam)
                            <option value="{{ $exam->id }}" {{ request('exam_id') == $exam->id ? 'selected' : '' }}>
                                {{ $exam->title }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="form-group">
                    <label>الطالب</label>
                    <select name="student_id" class="form-control">
                        <option value="">الكل</option>
                        @foreach($students as $student)
                            <option value="{{ $student->id }}" {{ request('student_id') == $student->id ? 'selected' : '' }}>
                                {{ $student->full_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="form-group">
                    <label>الحالة</label>
                    <select name="status" class="form-control">
                        <option value="">الكل</option>
                        <option value="passed" {{ request('status') == 'passed' ? 'selected' : '' }}>ناجح</option>
                        <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>راسب</option>
                        <option value="absent" {{ request('status') == 'absent' ? 'selected' : '' }}>غائب</option>
                    </select>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="form-group">
                    <label>&nbsp;</label>
                    <div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i> بحث
                        </button>
                        <a href="{{ route('admin.exam-results.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> إلغاء
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- إحصائيات سريعة -->
@if(request()->has('exam_id'))
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <h5 class="card-title">عدد الطلاب</h5>
                <h2>{{ $statistics['total_students'] ?? 0 }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <h5 class="card-title">الناجحون</h5>
                <h2>{{ $statistics['passed'] ?? 0 }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-danger text-white">
            <div class="card-body">
                <h5 class="card-title">الراسبون</h5>
                <h2>{{ $statistics['failed'] ?? 0 }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <h5 class="card-title">الغائبون</h5>
                <h2>{{ $statistics['absent'] ?? 0 }}</h2>
            </div>
        </div>
    </div>
</div>
@endif

<!-- قائمة النتائج -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">قائمة النتائج</h3>
        @if(request()->has('exam_id'))
            <a href="{{ route('admin.exam-results.export', ['exam_id' => request('exam_id')]) }}" class="btn btn-success">
                <i class="fas fa-file-export"></i> تصدير إلى CSV
            </a>
        @endif
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>الطالب</th>
                        <th>الاختبار</th>
                        <th>الدرجة</th>
                        <th>النسبة</th>
                        <th>الحالة</th>
                        <th>الوقت المستغرق</th>
                        <th>تاريخ الإنجاز</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($results as $result)
                        <tr>
                            <td>{{ $result->id }}</td>
                            <td>
                                <strong>{{ $result->student->full_name }}</strong>
                                <br>
                                <small class="text-muted">{{ $result->student->student_code }}</small>
                            </td>
                            <td>
                                <strong>{{ $result->exam->title }}</strong>
                                <br>
                                <small class="text-muted">{{ $result->exam->exam_code }}</small>
                            </td>
                            <td>
                                <strong>{{ $result->score }} / {{ $result->exam->total_points }}</strong>
                            </td>
                            <td>
                                <div class="progress" style="height: 20px;">
                                    <div class="progress-bar {{ $result->percentage >= 50 ? 'bg-success' : 'bg-danger' }}" 
                                         role="progressbar" 
                                         style="width: {{ $result->percentage }}%">
                                        {{ number_format($result->percentage, 2) }}%
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if($result->status == 'passed')
                                    <span class="badge badge-success">ناجح</span>
                                @elseif($result->status == 'failed')
                                    <span class="badge badge-danger">راسب</span>
                                @else
                                    <span class="badge badge-warning">غائب</span>
                                @endif
                            </td>
                            <td>
                                @if($result->time_spent)
                                    {{ floor($result->time_spent / 60) }} دقيقة
                                    {{ $result->time_spent % 60 }} ثانية
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                {{ $result->completed_at ? $result->completed_at->format('Y-m-d H:i') : '-' }}
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('admin.exam-results.show', $result) }}" class="btn btn-sm btn-info" title="عرض">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.exam-results.edit', $result) }}" class="btn btn-sm btn-warning" title="تعديل">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center">
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle"></i> لا توجد نتائج حالياً
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($results->hasPages())
            <div class="pagination-wrapper">
                {{ $results->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
