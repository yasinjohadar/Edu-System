@extends('student.layouts.master')

@section('page-title')
لوحة تحكم الطالب
@stop

@section('content')
<!-- Start::app-content -->
<div class="main-content app-content">
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <div>
                <h4 class="mb-0">مرحباً، {{ $student->user->name }}</h4>
                <p class="mb-0 text-muted">لوحة تحكم الطالب</p>
            </div>
        </div>
        <!-- End Page Header -->

        <!-- row -->
        <div class="row">
            <div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
                <div class="card overflow-hidden sales-card bg-primary-gradient">
                    <div class="px-3 pt-3 pb-2 pt-0">
                        <div>
                            <h6 class="mb-3 fs-12 text-fixed-white">معدل الحضور</h6>
                        </div>
                        <div class="pb-0 mt-0">
                            <div class="d-flex">
                                <div>
                                    <h4 class="fs-20 fw-bold mb-1 text-fixed-white">{{ $stats['attendance_rate'] }}%</h4>
                                    <p class="mb-0 fs-12 text-fixed-white op-7">هذا الشهر</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
                <div class="card overflow-hidden sales-card bg-success-gradient">
                    <div class="px-3 pt-3 pb-2 pt-0">
                        <div>
                            <h6 class="mb-3 fs-12 text-fixed-white">المعدل التراكمي</h6>
                        </div>
                        <div class="pb-0 mt-0">
                            <div class="d-flex">
                                <div>
                                    <h4 class="fs-20 fw-bold mb-1 text-fixed-white">{{ $stats['average_grade'] }}</h4>
                                    <p class="mb-0 fs-12 text-fixed-white op-7">الفصل الحالي</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
                <div class="card overflow-hidden sales-card bg-warning-gradient">
                    <div class="px-3 pt-3 pb-2 pt-0">
                        <div>
                            <h6 class="mb-3 fs-12 text-fixed-white">الفواتير المعلقة</h6>
                        </div>
                        <div class="pb-0 mt-0">
                            <div class="d-flex">
                                <div>
                                    <h4 class="fs-20 fw-bold mb-1 text-fixed-white">{{ $stats['pending_invoices'] }}</h4>
                                    <p class="mb-0 fs-12 text-fixed-white op-7">فواتير تحتاج دفع</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
                <div class="card overflow-hidden sales-card bg-info-gradient">
                    <div class="px-3 pt-3 pb-2 pt-0">
                        <div>
                            <h6 class="mb-3 fs-12 text-fixed-white">رقم القيد</h6>
                        </div>
                        <div class="pb-0 mt-0">
                            <div class="d-flex">
                                <div>
                                    <h4 class="fs-20 fw-bold mb-1 text-fixed-white">{{ $student->student_code }}</h4>
                                    <p class="mb-0 fs-12 text-fixed-white op-7">رقم القيد الفريد</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- row closed -->

        <!-- row opened -->
        <div class="row">
            <div class="col-xl-6">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">آخر الحضور</h4>
                    </div>
                    <div class="card-body">
                        @if($recentAttendances->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>التاريخ</th>
                                            <th>الفصل</th>
                                            <th>الحالة</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($recentAttendances as $attendance)
                                            <tr>
                                                <td>{{ $attendance->date->format('Y-m-d') }}</td>
                                                <td>{{ $attendance->section->name ?? '-' }}</td>
                                                <td>
                                                    @if($attendance->status == 'present')
                                                        <span class="badge bg-success">حاضر</span>
                                                    @elseif($attendance->status == 'absent')
                                                        <span class="badge bg-danger">غائب</span>
                                                    @elseif($attendance->status == 'late')
                                                        <span class="badge bg-warning">متأخر</span>
                                                    @else
                                                        <span class="badge bg-info">معذور</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="text-center mt-3">
                                <a href="{{ route('student.attendance.index') }}" class="btn btn-primary btn-sm">عرض الكل</a>
                            </div>
                        @else
                            <p class="text-center text-muted">لا توجد سجلات حضور</p>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-xl-6">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">آخر الدرجات</h4>
                    </div>
                    <div class="card-body">
                        @if($recentGrades->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>المادة</th>
                                            <th>الامتحان</th>
                                            <th>الدرجة</th>
                                            <th>التاريخ</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($recentGrades as $grade)
                                            <tr>
                                                <td>{{ $grade->subject->name }}</td>
                                                <td>{{ $grade->exam_name }}</td>
                                                <td>
                                                    <strong>{{ $grade->marks_obtained }} / {{ $grade->total_marks }}</strong>
                                                    <br><small class="text-muted">{{ $grade->percentage }}%</small>
                                                </td>
                                                <td>{{ $grade->exam_date->format('Y-m-d') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="text-center mt-3">
                                <a href="{{ route('student.grades.index') }}" class="btn btn-primary btn-sm">عرض الكل</a>
                            </div>
                        @else
                            <p class="text-center text-muted">لا توجد درجات</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <!-- row closed -->

        <!-- row opened -->
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">معلومات الطالب</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>الاسم:</strong> {{ $student->user->name }}</p>
                                <p><strong>البريد الإلكتروني:</strong> {{ $student->user->email }}</p>
                                <p><strong>الهاتف:</strong> {{ $student->user->phone ?? 'غير محدد' }}</p>
                                <p><strong>تاريخ الميلاد:</strong> {{ $student->date_of_birth ? $student->date_of_birth->format('Y-m-d') : 'غير محدد' }}</p>
                                @if($student->class)
                                    <p><strong>الصف:</strong> {{ $student->class->name }}</p>
                                @endif
                                @if($student->section)
                                    <p><strong>الفصل:</strong> {{ $student->section->name }}</p>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <p><strong>الجنس:</strong> {{ $student->gender == 'male' ? 'ذكر' : 'أنثى' }}</p>
                                <p><strong>تاريخ التسجيل:</strong> {{ $student->enrollment_date ? $student->enrollment_date->format('Y-m-d') : 'غير محدد' }}</p>
                                <p><strong>الحالة:</strong> 
                                    @if($student->status == 'active')
                                        <span class="badge bg-success">نشط</span>
                                    @elseif($student->status == 'graduated')
                                        <span class="badge bg-info">متخرج</span>
                                    @elseif($student->status == 'transferred')
                                        <span class="badge bg-warning">منقول</span>
                                    @else
                                        <span class="badge bg-danger">موقوف</span>
                                    @endif
                                </p>
                                <p><strong>الاستعارات النشطة:</strong> {{ $stats['active_borrowings'] }}</p>
                                <p><strong>المحاضرات القادمة:</strong> {{ $stats['upcoming_lectures'] }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- row closed -->

    </div>
</div>
<!-- End::app-content -->
@stop

