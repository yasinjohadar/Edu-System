@extends('admin.layouts.master')

@section('page-title')
    تسليمات الواجب
@stop

@section('content')
    @if (\Session::has('success'))
        <div class="alert alert-success">
            <ul>
                <li>{!! \Session::get('success') !!}</li>
            </ul>
        </div>
    @endif

    <!-- Start::app-content -->
    <div class="main-content app-content">
        <div class="container-fluid">
            <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
                <div class="my-auto">
                    <h5 class="page-title fs-21 mb-1">تسليمات الواجب: {{ $assignment->title }}</h5>
                </div>
                <div>
                    <a href="{{ route('admin.assignments.show', $assignment->id) }}" class="btn btn-secondary btn-sm">رجوع</a>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <h6>إجمالي التسليمات</h6>
                            <h3>{{ $stats['total_submissions'] }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <h6>المصححة</h6>
                            <h3>{{ $stats['graded_submissions'] }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-warning text-white">
                        <div class="card-body">
                            <h6>المعلقة</h6>
                            <h3>{{ $stats['pending_submissions'] }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-danger text-white">
                        <div class="card-body">
                            <h6>المتأخرة</h6>
                            <h3>{{ $stats['late_submissions'] }}</h3>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header align-items-center d-flex gap-3">
                            <div class="flex-shrink-0">
                                <form action="{{ route('admin.assignments.submissions.index', $assignment->id) }}" method="GET" class="d-flex align-items-center gap-2">
                                    <select name="status" class="form-select" style="width: 150px;">
                                        <option value="">كل الحالات</option>
                                        <option value="submitted" {{ request('status') == 'submitted' ? 'selected' : '' }}>مُسلم</option>
                                        <option value="late" {{ request('status') == 'late' ? 'selected' : '' }}>متأخر</option>
                                        <option value="graded" {{ request('status') == 'graded' ? 'selected' : '' }}>مُصحح</option>
                                        <option value="returned" {{ request('status') == 'returned' ? 'selected' : '' }}>مُرجع</option>
                                    </select>
                                    <button type="submit" class="btn btn-secondary">بحث</button>
                                    <a href="{{ route('admin.assignments.submissions.index', $assignment->id) }}" class="btn btn-danger">مسح</a>
                                </form>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover align-middle table-nowrap mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>رقم التسليم</th>
                                            <th>الطالب</th>
                                            <th>المحاولة</th>
                                            <th>تاريخ التسليم</th>
                                            <th>الحالة</th>
                                            <th>الدرجة</th>
                                            <th>العمليات</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($submissions as $submission)
                                            <tr>
                                                <td>{{ $submission->id }}</td>
                                                <td><strong>{{ $submission->submission_number }}</strong></td>
                                                <td>
                                                    {{ $submission->student->user->name ?? 'غير محدد' }}
                                                    <br>
                                                    <small class="text-muted">{{ $submission->student->student_code }}</small>
                                                </td>
                                                <td>
                                                    <span class="badge bg-info">{{ $submission->attempt_number }}</span>
                                                    @if($submission->is_resubmission)
                                                        <br><small class="text-muted">إعادة تسليم</small>
                                                    @endif
                                                </td>
                                                <td>
                                                    {{ $submission->submitted_at->format('Y-m-d H:i') }}
                                                    @if($submission->is_late)
                                                        <br><small class="text-danger">متأخر {{ $submission->days_late }} يوم</small>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($submission->status == 'graded')
                                                        <span class="badge bg-success">{{ $submission->status_name }}</span>
                                                    @elseif($submission->status == 'late')
                                                        <span class="badge bg-danger">{{ $submission->status_name }}</span>
                                                    @elseif($submission->status == 'returned')
                                                        <span class="badge bg-warning">{{ $submission->status_name }}</span>
                                                    @else
                                                        <span class="badge bg-info">{{ $submission->status_name }}</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($submission->marks_obtained !== null)
                                                        <strong>{{ number_format($submission->marks_obtained, 2) }}</strong> / {{ number_format($assignment->total_marks, 2) }}
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="d-flex gap-2">
                                                        <a href="{{ route('admin.assignments.submissions.show', [$assignment->id, $submission->id]) }}" class="btn btn-sm btn-info" title="عرض">
                                                            <i class="fa-solid fa-eye"></i>
                                                        </a>
                                                        @if($submission->status != 'graded')
                                                        <a href="{{ route('admin.assignments.submissions.show', [$assignment->id, $submission->id]) }}#grade" class="btn btn-sm btn-success" title="تصحيح">
                                                            <i class="fa-solid fa-check"></i>
                                                        </a>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="8" class="text-center">لا توجد تسليمات</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-3">
                                {{ $submissions->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End::app-content -->
@stop

