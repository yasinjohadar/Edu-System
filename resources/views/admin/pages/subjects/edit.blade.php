@extends('admin.layouts.master')

@section('page-title')
    تعديل المادة
@stop

@section('content')
    <!-- Start::app-content -->
    <div class="main-content app-content">
        <div class="container-fluid">
            <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
                <div class="my-auto">
                    <h5 class="page-title fs-21 mb-1">تعديل المادة</h5>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">بيانات المادة</h4>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.subjects.update', $subject->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">اسم المادة <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="name" value="{{ old('name', $subject->name) }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">رمز المادة</label>
                                            <input type="text" class="form-control" name="code" value="{{ old('code', $subject->code) }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">نوع المادة <span class="text-danger">*</span></label>
                                            <select class="form-control" name="type" required>
                                                <option value="required" {{ old('type', $subject->type) == 'required' ? 'selected' : '' }}>إجباري</option>
                                                <option value="optional" {{ old('type', $subject->type) == 'optional' ? 'selected' : '' }}>اختياري</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">عدد الحصص الأسبوعية</label>
                                            <input type="number" class="form-control" name="weekly_hours" value="{{ old('weekly_hours', $subject->weekly_hours) }}" min="0">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">الدرجة الكاملة</label>
                                            <input type="number" step="0.01" class="form-control" name="full_marks" value="{{ old('full_marks', $subject->full_marks) }}" min="0">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">درجة النجاح</label>
                                            <input type="number" step="0.01" class="form-control" name="pass_marks" value="{{ old('pass_marks', $subject->pass_marks) }}" min="0">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label class="form-label">الصفوف المرتبطة</label>
                                            <select class="form-control" name="classes[]" multiple>
                                                @foreach($classes as $class)
                                                    <option value="{{ $class->id }}" {{ in_array($class->id, old('classes', $subject->classes->pluck('id')->toArray())) ? 'selected' : '' }}>{{ $class->grade->name }} - {{ $class->name }}</option>
                                                @endforeach
                                            </select>
                                            <small class="text-muted">اضغط Ctrl للاختيار المتعدد</small>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label class="form-label">الوصف</label>
                                            <textarea class="form-control" name="description" rows="3">{{ old('description', $subject->description) }}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-check form-switch mb-3">
                                            <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active" {{ old('is_active', $subject->is_active) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="is_active">نشط</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary">تحديث</button>
                                    <a href="{{ route('admin.subjects.index') }}" class="btn btn-secondary">إلغاء</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

