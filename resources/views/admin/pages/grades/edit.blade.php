@extends('admin.layouts.master')

@section('page-title')
    تعديل المرحلة
@stop

@section('content')
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li class="small">{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="main-content app-content">
        <div class="container-fluid">

            <div class="admin-page-header">
                <div class="page-title-wrap">
                    <h1>تعديل المرحلة: {{ $grade->name }}</h1>
                    <p>تحديث بيانات المرحلة التعليمية</p>
                </div>
                <a href="{{ route('admin.grades.index') }}" class="admin-btn admin-btn-secondary">
                    <i class="ri-arrow-right-line"></i>
                    العودة للقائمة
                </a>
            </div>

            <div class="admin-page-card">
                <form action="{{ route('admin.grades.update', $grade->id) }}" method="POST" class="admin-form" id="grade-edit-form">
                    @csrf
                    @method('PUT')

                    <div class="admin-form-body">
                        <div class="admin-form-section">
                            <div class="admin-form-section-head">
                                <div class="admin-section-icon admin-section-icon-blue">
                                    <i class="ri-graduation-cap-line"></i>
                                </div>
                                <div>
                                    <h3>بيانات المرحلة</h3>
                                    <p>الاسم بالعربية والإنجليزية</p>
                                </div>
                            </div>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="admin-form-field">
                                        <label class="admin-form-label">اسم المرحلة <span class="required">*</span></label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                                               name="name" value="{{ old('name', $grade->name) }}" required>
                                        @error('name')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="admin-form-field">
                                        <label class="admin-form-label">الاسم بالإنجليزية</label>
                                        <input type="text" class="form-control @error('name_en') is-invalid @enderror"
                                               name="name_en" value="{{ old('name_en', $grade->name_en) }}">
                                        @error('name_en')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="admin-form-field">
                                        <label class="admin-form-label">الوصف</label>
                                        <textarea class="form-control @error('description') is-invalid @enderror"
                                                  name="description" rows="3">{{ old('description', $grade->description) }}</textarea>
                                        @error('description')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="admin-form-section">
                            <div class="admin-form-section-head">
                                <div class="admin-section-icon admin-section-icon-green">
                                    <i class="ri-calendar-line"></i>
                                </div>
                                <div>
                                    <h3>الأعمار والرسوم</h3>
                                    <p>تحديد الفئة العمرية والتكلفة والترتيب</p>
                                </div>
                            </div>

                            <div class="row g-3">
                                <div class="col-md-3">
                                    <div class="admin-form-field">
                                        <label class="admin-form-label">الحد الأدنى للعمر</label>
                                        <input type="number" class="form-control @error('min_age') is-invalid @enderror"
                                               name="min_age" value="{{ old('min_age', $grade->min_age) }}" min="0">
                                        @error('min_age')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="admin-form-field">
                                        <label class="admin-form-label">الحد الأقصى للعمر</label>
                                        <input type="number" class="form-control @error('max_age') is-invalid @enderror"
                                               name="max_age" value="{{ old('max_age', $grade->max_age) }}" min="0">
                                        @error('max_age')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="admin-form-field">
                                        <label class="admin-form-label">الرسوم الدراسية (ر.س)</label>
                                        <input type="number" step="0.01" class="form-control @error('fees') is-invalid @enderror"
                                               name="fees" value="{{ old('fees', $grade->fees) }}" min="0">
                                        @error('fees')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="admin-form-field">
                                        <label class="admin-form-label">الترتيب</label>
                                        <input type="number" class="form-control @error('order') is-invalid @enderror"
                                               name="order" value="{{ old('order', $grade->order) }}" min="0">
                                        @error('order')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="admin-form-section">
                            <div class="admin-form-section-head">
                                <div class="admin-section-icon admin-section-icon-amber">
                                    <i class="ri-toggle-line"></i>
                                </div>
                                <div>
                                    <h3>الحالة</h3>
                                    <p>تفعيل أو إيقاف المرحلة</p>
                                </div>
                            </div>

                            <div class="admin-form-switch-card">
                                <div class="switch-info">
                                    <strong>مرحلة نشطة</strong>
                                    <span>إظهار المرحلة في النظام والتسجيل</span>
                                </div>
                                <input class="form-check-input" type="checkbox" name="is_active" value="1"
                                       id="is_active" {{ old('is_active', $grade->is_active) ? 'checked' : '' }}>
                            </div>
                        </div>
                    </div>

                    <div class="admin-form-footer">
                        <a href="{{ route('admin.grades.index') }}" class="admin-btn admin-btn-secondary">
                            <i class="ri-close-line"></i>
                            إلغاء
                        </a>
                        <button type="submit" class="admin-btn admin-btn-primary">
                            <i class="ri-save-line"></i>
                            حفظ التعديلات
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
@stop

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (window.AdminTables && AdminTables.initAdminForm) {
            AdminTables.initAdminForm(document.getElementById('grade-edit-form'));
        }
    });
</script>
@endpush
