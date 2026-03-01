@extends('admin.layouts.master')

@section('page-title')
    تفاصيل الخريج
@stop

@section('content')
    <div class="main-content app-content">
        <div class="container-fluid">
            <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
                <div class="my-auto">
                    <h5 class="page-title fs-21 mb-1">تفاصيل الخريج</h5>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">بيانات الخريج</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>الاسم:</strong> {{ $alumni->name }}</p>
                                    <p><strong>البريد الإلكتروني:</strong> {{ $alumni->email }}</p>
                                    <p><strong>رقم الهاتف:</strong> {{ $alumni->phone ?? '-' }}</p>
                                    <p><strong>تاريخ التخرج:</strong> {{ $alumni->graduation_date->format('Y-m-d') }}</p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>الدرجة:</strong> {{ $alumni->degree ?? '-' }}</p>
                                    <p><strong>التخصص:</strong> {{ $alumni->major ?? '-' }}</p>
                                    <p><strong>الوظيفة الحالية:</strong> {{ $alumni->current_job ?? '-' }}</p>
                                    <p><strong>الشركة:</strong> {{ $alumni->company ?? '-' }}</p>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-12">
                                    <p><strong>العنوان:</strong> {{ $alumni->address ?? '-' }}</p>
                                    <p><strong>الحالة:</strong> 
                                        @if($alumni->is_active)
                                            <span class="badge bg-success">نشط</span>
                                        @else
                                            <span class="badge bg-danger">غير نشط</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-12">
                                    <a href="{{ route('admin.alumni.edit', $alumni) }}" class="btn btn-primary">تعديل</a>
                                    <a href="{{ route('admin.alumni.index') }}" class="btn btn-secondary">رجوع</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

