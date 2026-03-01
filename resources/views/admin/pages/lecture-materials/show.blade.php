@extends('admin.layouts.master')

@section('page-title')
    تفاصيل المادة التعليمية
@stop

@section('content')
    <!-- Start::app-content -->
    <div class="main-content app-content">
        <div class="container-fluid">
            <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
                <div class="my-auto">
                    <h5 class="page-title fs-21 mb-1">تفاصيل المادة التعليمية</h5>
                </div>
                <div>
                    <a href="{{ route('admin.lecture-materials.edit', $material->id) }}" class="btn btn-warning btn-sm">تعديل</a>
                    <a href="{{ route('admin.lecture-materials.index') }}" class="btn btn-secondary btn-sm">رجوع</a>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">{{ $material->title }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <strong>المحاضرة:</strong> {{ $material->lecture->title }}
                                </div>
                                <div class="col-md-6">
                                    <strong>النوع:</strong>
                                    @if($material->type == 'file')
                                        <span class="badge bg-primary">ملف</span>
                                    @elseif($material->type == 'link')
                                        <span class="badge bg-info">رابط</span>
                                    @else
                                        <span class="badge bg-secondary">{{ $material->type }}</span>
                                    @endif
                                </div>
                            </div>
                            @if($material->description)
                                <div class="mb-3">
                                    <strong>الوصف:</strong>
                                    <p>{{ $material->description }}</p>
                                </div>
                            @endif
                            @if($material->file_path)
                                <div class="mb-3">
                                    <strong>الملف:</strong> {{ $material->file_name }}<br>
                                    <strong>الحجم:</strong> {{ $material->formatted_file_size }}<br>
                                    <a href="{{ asset('storage/' . $material->file_path) }}" target="_blank" class="btn btn-sm btn-primary mt-2">تحميل</a>
                                </div>
                            @endif
                            @if($material->external_url)
                                <div class="mb-3">
                                    <strong>الرابط الخارجي:</strong>
                                    <a href="{{ $material->external_url }}" target="_blank" class="btn btn-sm btn-info mt-2">فتح الرابط</a>
                                </div>
                            @endif
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <strong>التحميلات:</strong> {{ $material->download_count }}
                                </div>
                                <div class="col-md-6">
                                    <strong>الحالة:</strong>
                                    @if($material->is_active)
                                        <span class="badge bg-success">نشط</span>
                                    @else
                                        <span class="badge bg-danger">غير نشط</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End::app-content -->
@stop

