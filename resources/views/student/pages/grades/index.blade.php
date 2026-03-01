@extends('student.layouts.master')

@section('page-title')
    الدرجات والتقييمات
@stop

@section('content')
    <!-- Start::app-content -->
    <div class="main-content app-content">
        <div class="container-fluid">
            <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
                <div class="my-auto">
                    <h5 class="page-title fs-21 mb-1">الدرجات والتقييمات</h5>
                </div>
            </div>

            <!-- إحصائيات -->
            <div class="row mb-4">
                <div class="col-xl-4 col-lg-6 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="avatar avatar-md bg-primary text-white rounded">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M12 2L2 7l10 5 10-5-10-5z"></path>
                                            <path d="M2 17l10 5 10-5"></path>
                                            <path d="M2 12l10 5 10-5"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-0">إجمالي الامتحانات</h6>
                                    <h4 class="mb-0">{{ $stats['total_exams'] }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-6 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="avatar avatar-md bg-success text-white rounded">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                            <polyline points="22 4 12 14.01 9 11.01"></polyline>
                                        </svg>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-0">المعدل التراكمي</h6>
                                    <h4 class="mb-0">{{ $stats['average_grade'] }}%</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-6 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="avatar avatar-md bg-info text-white rounded">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                            <polyline points="14 2 14 8 20 8"></polyline>
                                            <line x1="16" y1="13" x2="8" y2="13"></line>
                                            <line x1="16" y1="17" x2="8" y2="17"></line>
                                            <polyline points="10 9 9 9 8 9"></polyline>
                                        </svg>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-0">عدد المواد</h6>
                                    <h4 class="mb-0">{{ $stats['grades_by_subject']->count() }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- المعدلات حسب المادة -->
            @if($stats['grades_by_subject']->count() > 0)
                <div class="row mb-4">
                    <div class="col-xl-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title">المعدلات حسب المادة</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>المادة</th>
                                                <th>المعدل</th>
                                                <th>عدد الامتحانات</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($stats['grades_by_subject'] as $subjectGrade)
                                                <tr>
                                                    <td>{{ $subjectGrade['subject']->name }}</td>
                                                    <td><strong>{{ $subjectGrade['average'] }}%</strong></td>
                                                    <td>{{ $subjectGrade['count'] }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header align-items-center d-flex gap-3">
                            <div class="flex-shrink-0">
                                <form action="{{ route('student.grades.index') }}" method="GET" class="d-flex align-items-center gap-2">
                                    <select name="subject_id" class="form-select" style="width: 200px;">
                                        <option value="">كل المواد</option>
                                        @foreach($subjects as $subject)
                                            <option value="{{ $subject->id }}" {{ request('subject_id') == $subject->id ? 'selected' : '' }}>{{ $subject->name }}</option>
                                        @endforeach
                                    </select>
                                    <select name="exam_type" class="form-select" style="width: 150px;">
                                        <option value="">كل الأنواع</option>
                                        <option value="quiz" {{ request('exam_type') == 'quiz' ? 'selected' : '' }}>اختبار</option>
                                        <option value="midterm" {{ request('exam_type') == 'midterm' ? 'selected' : '' }}>نصفي</option>
                                        <option value="final" {{ request('exam_type') == 'final' ? 'selected' : '' }}>نهائي</option>
                                        <option value="assignment" {{ request('exam_type') == 'assignment' ? 'selected' : '' }}>واجب</option>
                                    </select>
                                    <button type="submit" class="btn btn-secondary">بحث</button>
                                    <a href="{{ route('student.grades.index') }}" class="btn btn-danger">مسح</a>
                                </form>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover align-middle table-nowrap mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>المادة</th>
                                            <th>نوع الامتحان</th>
                                            <th>اسم الامتحان</th>
                                            <th>الدرجة</th>
                                            <th>النسبة</th>
                                            <th>التقدير</th>
                                            <th>التاريخ</th>
                                            <th>المعلم</th>
                                            <th>العمليات</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($grades as $grade)
                                            <tr>
                                                <td>{{ $grade->id }}</td>
                                                <td>{{ $grade->subject->name }}</td>
                                                <td>
                                                    @if($grade->exam_type == 'quiz')
                                                        <span class="badge bg-info">اختبار</span>
                                                    @elseif($grade->exam_type == 'midterm')
                                                        <span class="badge bg-warning">نصفي</span>
                                                    @elseif($grade->exam_type == 'final')
                                                        <span class="badge bg-danger">نهائي</span>
                                                    @else
                                                        <span class="badge bg-secondary">{{ $grade->exam_type }}</span>
                                                    @endif
                                                </td>
                                                <td>{{ $grade->exam_name }}</td>
                                                <td>
                                                    <strong>{{ $grade->marks_obtained }} / {{ $grade->total_marks }}</strong>
                                                </td>
                                                <td>
                                                    @if($grade->percentage >= 90)
                                                        <span class="badge bg-success">{{ $grade->percentage }}%</span>
                                                    @elseif($grade->percentage >= 75)
                                                        <span class="badge bg-primary">{{ $grade->percentage }}%</span>
                                                    @elseif($grade->percentage >= 60)
                                                        <span class="badge bg-warning">{{ $grade->percentage }}%</span>
                                                    @else
                                                        <span class="badge bg-danger">{{ $grade->percentage }}%</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($grade->grade)
                                                        <span class="badge bg-info">{{ $grade->grade }}</span>
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                <td>{{ $grade->exam_date->format('Y-m-d') }}</td>
                                                <td>{{ $grade->teacher->user->name ?? '-' }}</td>
                                                <td>
                                                    <a href="{{ route('student.grades.show', $grade->id) }}" class="btn btn-sm btn-info" title="عرض">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                            <circle cx="12" cy="12" r="3"></circle>
                                                        </svg>
                                                    </a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="10" class="text-center">لا توجد درجات</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-3">
                                {{ $grades->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End::app-content -->
@stop

