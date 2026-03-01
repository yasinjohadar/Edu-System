@extends('student.layouts.master')

@section('page-title')
لوحة تحكم ولي الأمر
@stop

@section('content')
<!-- Start::app-content -->
<div class="main-content app-content">
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <div>
                <h4 class="mb-0">مرحباً، {{ $parent->user->name }}</h4>
                <p class="mb-0 text-muted">لوحة تحكم ولي الأمر</p>
            </div>
        </div>
        <!-- End Page Header -->

        @if($children->count() > 0)
            <!-- row -->
            <div class="row">
                @foreach($children as $child)
                    <div class="col-xl-4 col-lg-6 col-md-6 col-xm-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title mb-0">{{ $child->user->name }}</h4>
                                <p class="mb-0 text-muted">رقم القيد: {{ $child->student_code }}</p>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="text-center">
                                            <h5 class="mb-1">{{ $childrenStats[$child->id]['attendance_rate'] }}%</h5>
                                            <p class="mb-0 text-muted fs-12">معدل الحضور</p>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="text-center">
                                            <h5 class="mb-1">{{ $childrenStats[$child->id]['average_grade'] }}</h5>
                                            <p class="mb-0 text-muted fs-12">المعدل التراكمي</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <p class="mb-1"><strong>الواجبات المعلقة:</strong> {{ $childrenStats[$child->id]['pending_assignments'] }}</p>
                                    <p class="mb-0">
                                        <strong>الحالة:</strong> 
                                        @if($child->status == 'active')
                                            <span class="badge bg-success">نشط</span>
                                        @elseif($child->status == 'graduated')
                                            <span class="badge bg-info">متخرج</span>
                                        @elseif($child->status == 'transferred')
                                            <span class="badge bg-warning">منقول</span>
                                        @else
                                            <span class="badge bg-danger">موقوف</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <!-- row closed -->
        @else
            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-body text-center">
                            <p class="mb-0">لا يوجد أبناء مرتبطين بهذا الحساب</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- row opened -->
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">معلومات ولي الأمر</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>الاسم:</strong> {{ $parent->user->name }}</p>
                                <p><strong>البريد الإلكتروني:</strong> {{ $parent->user->email }}</p>
                                <p><strong>الهاتف:</strong> {{ $parent->user->phone ?? 'غير محدد' }}</p>
                                <p><strong>صلة القرابة:</strong> 
                                    @if($parent->relationship == 'father')
                                        أب
                                    @elseif($parent->relationship == 'mother')
                                        أم
                                    @else
                                        ولي أمر
                                    @endif
                                </p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>المهنة:</strong> {{ $parent->occupation ?? 'غير محدد' }}</p>
                                <p><strong>مكان العمل:</strong> {{ $parent->workplace ?? 'غير محدد' }}</p>
                                <p><strong>هاتف العمل:</strong> {{ $parent->work_phone ?? 'غير محدد' }}</p>
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

