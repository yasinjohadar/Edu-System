@extends('student.layouts.master')

@section('page-title')
    الواجبات
@stop

@section('content')
    <!-- Start::app-content -->
    <div class="main-content app-content">
        <div class="container-fluid">
            <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
                <div class="my-auto">
                    <h5 class="page-title fs-21 mb-1">الواجبات</h5>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <h6>إجمالي الواجبات</h6>
                            <h3>{{ $stats['total_assignments'] }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <h6>المُسلمة</h6>
                            <h3>{{ $stats['submitted_assignments'] }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-warning text-white">
                        <div class="card-body">
                            <h6>المعلقة</h6>
                            <h3>{{ $stats['pending_assignments'] }}</h3>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header align-items-center d-flex gap-3">
                            <div class="flex-shrink-0">
                                <form action="{{ route('student.assignments.index') }}" method="GET" class="d-flex align-items-center gap-2">
                                    <select name="subject_id" class="form-select" style="width: 150px;">
                                        <option value="">كل المواد</option>
                                        @foreach($subjects as $subject)
                                            <option value="{{ $subject->id }}" {{ request('subject_id') == $subject->id ? 'selected' : '' }}>{{ $subject->name }}</option>
                                        @endforeach
                                    </select>
                                    <select name="status" class="form-select" style="width: 150px;">
                                        <option value="">كل الحالات</option>
                                        <option value="new" {{ request('status') == 'new' ? 'selected' : '' }}>جديدة</option>
                                        <option value="submitted" {{ request('status') == 'submitted' ? 'selected' : '' }}>مُسلمة</option>
                                        <option value="graded" {{ request('status') == 'graded' ? 'selected' : '' }}>مُصححة</option>
                                    </select>
                                    <button type="submit" class="btn btn-secondary">بحث</button>
                                    <a href="{{ route('student.assignments.index') }}" class="btn btn-danger">مسح</a>
                                </form>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover align-middle table-nowrap mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>العنوان</th>
                                            <th>المادة</th>
                                            <th>المعلم</th>
                                            <th>تاريخ الاستحقاق</th>
                                            <th>الدرجة الكلية</th>
                                            <th>حالة التسليم</th>
                                            <th>العمليات</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($assignments as $assignment)
                                            @php
                                                $studentSubmission = $assignment->submissions()->where('student_id', auth()->user()->student->id)->latest('attempt_number')->first();
                                            @endphp
                                            <tr>
                                                <td>{{ $assignment->id }}</td>
                                                <td>
                                                    <strong>{{ $assignment->title }}</strong>
                                                    @if($assignment->isOverdue() && !$studentSubmission)
                                                        <br><small class="text-danger">متأخر</small>
                                                    @endif
                                                </td>
                                                <td>{{ $assignment->subject->name ?? 'غير محدد' }}</td>
                                                <td>{{ $assignment->teacher->user->name ?? 'غير محدد' }}</td>
                                                <td>
                                                    {{ $assignment->due_date->format('Y-m-d') }}
                                                    @if($assignment->isOverdue())
                                                        <br><small class="text-danger">متأخر</small>
                                                    @endif
                                                </td>
                                                <td><strong>{{ number_format($assignment->total_marks, 2) }}</strong></td>
                                                <td>
                                                    @if($studentSubmission)
                                                        @if($studentSubmission->status == 'graded')
                                                            <span class="badge bg-success">مُصحح</span>
                                                            <br><small>{{ number_format($studentSubmission->marks_obtained, 2) }} / {{ number_format($assignment->total_marks, 2) }}</small>
                                                        @elseif($studentSubmission->status == 'late')
                                                            <span class="badge bg-danger">متأخر</span>
                                                        @elseif($studentSubmission->status == 'returned')
                                                            <span class="badge bg-warning">يحتاج إعادة</span>
                                                        @else
                                                            <span class="badge bg-info">مُسلم</span>
                                                        @endif
                                                    @else
                                                        <span class="badge bg-secondary">غير مُسلم</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="d-flex gap-2">
                                                        <a href="{{ route('student.assignments.show', $assignment->id) }}" class="btn btn-sm btn-info" title="عرض">
                                                            <i class="fa-solid fa-eye"></i>
                                                        </a>
                                                        @if(!$studentSubmission || ($studentSubmission->requires_resubmission && $assignment->canSubmit(auth()->user()->student)))
                                                        <a href="{{ route('student.assignments.submit', $assignment->id) }}" class="btn btn-sm btn-primary" title="تسليم">
                                                            <i class="fa-solid fa-upload"></i>
                                                        </a>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="8" class="text-center">لا توجد واجبات</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-3">
                                {{ $assignments->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End::app-content -->
@stop

