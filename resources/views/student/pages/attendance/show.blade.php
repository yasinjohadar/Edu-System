@extends('student.layouts.master')

@section('page-title')
    تفاصيل الحضور
@stop

@section('content')
    <!-- Start::app-content -->
    <div class="main-content app-content">
        <div class="container-fluid">
            <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
                <div class="my-auto">
                    <h5 class="page-title fs-21 mb-1">تفاصيل الحضور</h5>
                </div>
                <div>
                    <a href="{{ route('student.attendance.index') }}" class="btn btn-secondary btn-sm">رجوع</a>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">معلومات الحضور</h5>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <strong>التاريخ:</strong> {{ $attendance->date->format('Y-m-d') }}
                                </div>
                                <div class="col-md-6">
                                    <strong>الفصل:</strong> {{ $attendance->section->name ?? '-' }}
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <strong>الحالة:</strong>
                                    @if($attendance->status == 'present')
                                        <span class="badge bg-success">حاضر</span>
                                    @elseif($attendance->status == 'absent')
                                        <span class="badge bg-danger">غائب</span>
                                    @elseif($attendance->status == 'late')
                                        <span class="badge bg-warning">متأخر</span>
                                    @else
                                        <span class="badge bg-info">معذور</span>
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    <strong>سجل بواسطة:</strong> {{ $attendance->markedBy->name ?? '-' }}
                                </div>
                            </div>
                            @if($attendance->check_in_time)
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <strong>وقت الحضور:</strong> {{ $attendance->check_in_time->format('Y-m-d H:i') }}
                                    </div>
                                    @if($attendance->check_out_time)
                                        <div class="col-md-6">
                                            <strong>وقت المغادرة:</strong> {{ $attendance->check_out_time->format('Y-m-d H:i') }}
                                        </div>
                                    @endif
                                </div>
                            @endif
                            @if($attendance->notes)
                                <div class="mb-3">
                                    <strong>الملاحظات:</strong>
                                    <p>{{ $attendance->notes }}</p>
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

