@extends('admin.layouts.master')

@section('page-title')
    تفاصيل الاستعارة
@stop

@section('content')
    <!-- Start::app-content -->
    <div class="main-content app-content">
        <div class="container-fluid">
            <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
                <div class="my-auto">
                    <h5 class="page-title fs-21 mb-1">تفاصيل الاستعارة</h5>
                </div>
                <div>
                    @if($borrowing->status == 'borrowed' || $borrowing->status == 'overdue')
                        <a href="{{ route('admin.book-borrowings.edit', $borrowing->id) }}" class="btn btn-warning btn-sm">إرجاع الكتاب</a>
                    @endif
                    <a href="{{ route('admin.book-borrowings.index') }}" class="btn btn-secondary btn-sm">رجوع</a>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">معلومات الاستعارة</h5>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <strong>رقم الاستعارة:</strong> {{ $borrowing->borrowing_number }}
                                </div>
                                <div class="col-md-6">
                                    <strong>الحالة:</strong>
                                    @if($borrowing->status == 'borrowed')
                                        <span class="badge bg-primary">مستعار</span>
                                    @elseif($borrowing->status == 'returned')
                                        <span class="badge bg-success">مُرجع</span>
                                    @elseif($borrowing->status == 'overdue')
                                        <span class="badge bg-danger">متأخر</span>
                                    @else
                                        <span class="badge bg-warning">مفقود</span>
                                    @endif
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <strong>الكتاب:</strong> {{ $borrowing->book->title }}
                                </div>
                                <div class="col-md-6">
                                    <strong>الطالب:</strong> {{ $borrowing->student->user->name ?? '-' }}
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <strong>تاريخ الاستعارة:</strong> {{ $borrowing->borrow_date->format('Y-m-d') }}
                                </div>
                                <div class="col-md-6">
                                    <strong>تاريخ الاستحقاق:</strong> {{ $borrowing->due_date->format('Y-m-d') }}
                                    @if($borrowing->isOverdue())
                                        <br><small class="text-danger">متأخر {{ $borrowing->days_overdue }} يوم</small>
                                    @endif
                                </div>
                            </div>
                            @if($borrowing->return_date)
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <strong>تاريخ الإرجاع:</strong> {{ $borrowing->return_date->format('Y-m-d') }}
                                    </div>
                                    <div class="col-md-6">
                                        <strong>مُرجع بواسطة:</strong> {{ $borrowing->returner->name ?? '-' }}
                                    </div>
                                </div>
                            @endif
                            @if($borrowing->notes)
                                <div class="mb-3">
                                    <strong>ملاحظات:</strong>
                                    <p>{{ $borrowing->notes }}</p>
                                </div>
                            @endif
                            @if($borrowing->fine)
                                <div class="alert alert-warning">
                                    <strong>غرامة:</strong> {{ number_format($borrowing->fine->amount, 2) }} ر.س - {{ $borrowing->fine->type_name }}
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

