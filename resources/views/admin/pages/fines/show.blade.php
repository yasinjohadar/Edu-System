@extends('admin.layouts.master')

@section('page-title')
    تفاصيل الغرامة
@stop

@section('content')
    <!-- Start::app-content -->
    <div class="main-content app-content">
        <div class="container-fluid">
            <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
                <div class="my-auto">
                    <h5 class="page-title fs-21 mb-1">تفاصيل الغرامة</h5>
                </div>
                <div>
                    @if($fine->status == 'pending')
                        <form action="{{ route('admin.fines.pay', $fine->id) }}" method="POST" style="display: inline;">
                            @csrf
                            <button type="submit" class="btn btn-success btn-sm">تسجيل الدفع</button>
                        </form>
                    @endif
                    <a href="{{ route('admin.fines.edit', $fine->id) }}" class="btn btn-warning btn-sm">تعديل</a>
                    <a href="{{ route('admin.fines.index') }}" class="btn btn-secondary btn-sm">رجوع</a>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">معلومات الغرامة</h5>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <strong>رقم الغرامة:</strong> {{ $fine->fine_number }}
                                </div>
                                <div class="col-md-6">
                                    <strong>الحالة:</strong>
                                    @if($fine->status == 'paid')
                                        <span class="badge bg-success">مدفوعة</span>
                                    @elseif($fine->status == 'waived')
                                        <span class="badge bg-info">معفاة</span>
                                    @else
                                        <span class="badge bg-warning">معلقة</span>
                                    @endif
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <strong>الطالب:</strong> {{ $fine->student->user->name ?? '-' }}
                                </div>
                                <div class="col-md-6">
                                    <strong>المبلغ:</strong> <span class="text-danger fw-bold">{{ number_format($fine->amount, 2) }} ر.س</span>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <strong>النوع:</strong> {{ $fine->type_name }}
                                </div>
                                <div class="col-md-6">
                                    <strong>تاريخ الاستحقاق:</strong> {{ $fine->due_date->format('Y-m-d') }}
                                </div>
                            </div>
                            @if($fine->borrowing)
                                <div class="row mb-3">
                                    <div class="col-md-12">
                                        <strong>الاستعارة:</strong> 
                                        <a href="{{ route('admin.book-borrowings.show', $fine->borrowing->id) }}">
                                            {{ $fine->borrowing->borrowing_number }} - {{ $fine->borrowing->book->title }}
                                        </a>
                                    </div>
                                </div>
                            @endif
                            @if($fine->reason)
                                <div class="mb-3">
                                    <strong>السبب:</strong>
                                    <p>{{ $fine->reason }}</p>
                                </div>
                            @endif
                            @if($fine->paid_date)
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <strong>تاريخ الدفع:</strong> {{ $fine->paid_date->format('Y-m-d') }}
                                    </div>
                                    <div class="col-md-6">
                                        <strong>دفع بواسطة:</strong> {{ $fine->payer->name ?? '-' }}
                                    </div>
                                </div>
                            @endif
                            @if($fine->notes)
                                <div class="mb-3">
                                    <strong>ملاحظات:</strong>
                                    <p>{{ $fine->notes }}</p>
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

