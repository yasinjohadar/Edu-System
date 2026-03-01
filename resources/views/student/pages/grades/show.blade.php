@extends('student.layouts.master')

@section('page-title')
    تفاصيل الدرجة
@stop

@section('content')
    <!-- Start::app-content -->
    <div class="main-content app-content">
        <div class="container-fluid">
            <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
                <div class="my-auto">
                    <h5 class="page-title fs-21 mb-1">تفاصيل الدرجة</h5>
                </div>
                <div>
                    <a href="{{ route('student.grades.index') }}" class="btn btn-secondary btn-sm">رجوع</a>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">{{ $grade->exam_name }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <strong>المادة:</strong> {{ $grade->subject->name }}
                                </div>
                                <div class="col-md-6">
                                    <strong>نوع الامتحان:</strong>
                                    @if($grade->exam_type == 'quiz')
                                        <span class="badge bg-info">اختبار</span>
                                    @elseif($grade->exam_type == 'midterm')
                                        <span class="badge bg-warning">نصفي</span>
                                    @elseif($grade->exam_type == 'final')
                                        <span class="badge bg-danger">نهائي</span>
                                    @else
                                        <span class="badge bg-secondary">{{ $grade->exam_type }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <strong>الدرجة:</strong> 
                                    <h4 class="d-inline-block">{{ $grade->marks_obtained }} / {{ $grade->total_marks }}</h4>
                                </div>
                                <div class="col-md-6">
                                    <strong>النسبة:</strong>
                                    @if($grade->percentage >= 90)
                                        <span class="badge bg-success fs-16">{{ $grade->percentage }}%</span>
                                    @elseif($grade->percentage >= 75)
                                        <span class="badge bg-primary fs-16">{{ $grade->percentage }}%</span>
                                    @elseif($grade->percentage >= 60)
                                        <span class="badge bg-warning fs-16">{{ $grade->percentage }}%</span>
                                    @else
                                        <span class="badge bg-danger fs-16">{{ $grade->percentage }}%</span>
                                    @endif
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <strong>التقدير:</strong>
                                    @if($grade->grade)
                                        <span class="badge bg-info fs-16">{{ $grade->grade }}</span>
                                    @else
                                        -
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    <strong>تاريخ الامتحان:</strong> {{ $grade->exam_date->format('Y-m-d') }}
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <strong>المعلم:</strong> {{ $grade->teacher->user->name ?? '-' }}
                                </div>
                                @if($grade->academic_year)
                                    <div class="col-md-6">
                                        <strong>السنة الدراسية:</strong> {{ $grade->academic_year }}
                                    </div>
                                @endif
                            </div>
                            @if($grade->notes)
                                <div class="mb-3">
                                    <strong>ملاحظات:</strong>
                                    <p>{{ $grade->notes }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End::app-content -->
@stop

