@extends('admin.layouts.master')

@section('page-title')
    تعديل المرحلة
@stop

@section('content')
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Start::app-content -->
    <div class="main-content app-content">
        <div class="container-fluid">

            <!-- Page Header -->
            <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
                <div class="my-auto">
                    <h5 class="page-title fs-21 mb-1">تعديل المرحلة</h5>
                </div>
            </div>
            <!-- Page Header Close -->

            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">بيانات المرحلة</h4>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.grades.update', $grade->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">اسم المرحلة <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="name" value="{{ old('name', $grade->name) }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">اسم المرحلة بالإنجليزية</label>
                                            <input type="text" class="form-control" name="name_en" value="{{ old('name_en', $grade->name_en) }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">الحد الأدنى للعمر</label>
                                            <input type="number" class="form-control" name="min_age" value="{{ old('min_age', $grade->min_age) }}" min="0">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">الحد الأقصى للعمر</label>
                                            <input type="number" class="form-control" name="max_age" value="{{ old('max_age', $grade->max_age) }}" min="0">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">الرسوم الدراسية</label>
                                            <input type="number" step="0.01" class="form-control" name="fees" value="{{ old('fees', $grade->fees) }}" min="0">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">الترتيب</label>
                                            <input type="number" class="form-control" name="order" value="{{ old('order', $grade->order) }}">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label class="form-label">الوصف</label>
                                            <textarea class="form-control" name="description" rows="3">{{ old('description', $grade->description) }}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-check form-switch mb-3">
                                            <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active" {{ old('is_active', $grade->is_active) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="is_active">نشط</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary">تحديث</button>
                                    <a href="{{ route('admin.grades.index') }}" class="btn btn-secondary">إلغاء</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <!-- End::app-content -->
@stop

