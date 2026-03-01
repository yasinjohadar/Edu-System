@extends('student.layouts.master')

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
                    <a href="{{ route('student.library.borrowings') }}" class="btn btn-secondary btn-sm">رجوع</a>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-8">
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
                                    <strong>المؤلف:</strong> {{ $borrowing->book->author }}
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <strong>التصنيف:</strong> <span class="badge bg-info">{{ $borrowing->book->category->name }}</span>
                                </div>
                                <div class="col-md-6">
                                    <strong>ISBN:</strong> {{ $borrowing->book->isbn ?? '-' }}
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
                        </div>
                    </div>
                </div>
                <div class="col-xl-4">
                    @if($borrowing->book->cover_image)
                        <div class="card">
                            <div class="card-body text-center">
                                <img src="{{ asset('storage/' . $borrowing->book->cover_image) }}" alt="{{ $borrowing->book->title }}" class="img-fluid rounded mb-3" style="max-height: 300px;">
                            </div>
                        </div>
                    @endif
                    @if($borrowing->fine)
                        <div class="card mt-3">
                            <div class="card-header bg-warning">
                                <h5 class="card-title mb-0">غرامة</h5>
                            </div>
                            <div class="card-body">
                                <p><strong>المبلغ:</strong> <span class="text-danger">{{ number_format($borrowing->fine->amount, 2) }} ر.س</span></p>
                                <p><strong>السبب:</strong> {{ $borrowing->fine->reason ?? '-' }}</p>
                                <p><strong>الحالة:</strong>
                                    @if($borrowing->fine->status == 'paid')
                                        <span class="badge bg-success">مدفوعة</span>
                                    @elseif($borrowing->fine->status == 'waived')
                                        <span class="badge bg-info">معفاة</span>
                                    @else
                                        <span class="badge bg-warning">معلقة</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!-- End::app-content -->
@stop

