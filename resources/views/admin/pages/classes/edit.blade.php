@extends('admin.layouts.master')

@section('page-title')
    تعديل الصف
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
                    <h1>تعديل الصف: {{ $class->name }}</h1>
                    <p>تحديث بيانات الصف الدراسي</p>
                </div>
                <a href="{{ route('admin.classes.index') }}" class="admin-btn admin-btn-secondary">
                    <i class="ri-arrow-right-line"></i>
                    العودة للقائمة
                </a>
            </div>

            <div class="admin-page-card">
                <form action="{{ route('admin.classes.update', $class->id) }}" method="POST" class="admin-form" id="class-edit-form">
                    @csrf
                    @method('PUT')

                    <div class="admin-form-body">
                        <div class="admin-form-section">
                            <div class="admin-form-section-head">
                                <div class="admin-section-icon admin-section-icon-blue">
                                    <i class="ri-book-2-line"></i>
                                </div>
                                <div>
                                    <h3>بيانات الصف</h3>
                                    <p>الاسم بالعربية والإنجليزية والمرحلة</p>
                                </div>
                            </div>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="admin-form-field">
                                        <label class="admin-form-label">المرحلة <span class="required">*</span></label>
                                        <select class="form-select @error('grade_id') is-invalid @enderror"
                                                name="grade_id" data-admin-choices required>
                                            <option value="">اختر المرحلة</option>
                                            @foreach ($grades as $grade)
                                                <option value="{{ $grade->id }}" {{ old('grade_id', $class->grade_id) == $grade->id ? 'selected' : '' }}>{{ $grade->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('grade_id')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="admin-form-field">
                                        <label class="admin-form-label">اسم الصف <span class="required">*</span></label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                                               name="name" value="{{ old('name', $class->name) }}" required>
                                        @error('name')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="admin-form-field">
                                        <label class="admin-form-label">الاسم بالإنجليزية</label>
                                        <input type="text" class="form-control @error('name_en') is-invalid @enderror"
                                               name="name_en" value="{{ old('name_en', $class->name_en) }}">
                                        @error('name_en')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="admin-form-field">
                                        <label class="admin-form-label">الترتيب</label>
                                        <input type="number" class="form-control @error('order') is-invalid @enderror"
                                               name="order" value="{{ old('order', $class->order) }}" min="0">
                                        @error('order')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="admin-form-field">
                                        <label class="admin-form-label">الوصف</label>
                                        <textarea class="form-control @error('description') is-invalid @enderror"
                                                  name="description" rows="3">{{ old('description', $class->description) }}</textarea>
                                        @error('description')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
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
                                    <p>تفعيل أو إيقاف الصف</p>
                                </div>
                            </div>

                            <div class="admin-form-switch-card">
                                <div class="switch-info">
                                    <strong>صف نشط</strong>
                                    <span>إظهار الصف في النظام والتسجيل</span>
                                </div>
                                <input class="form-check-input" type="checkbox" name="is_active" value="1"
                                       id="is_active" {{ old('is_active', $class->is_active) ? 'checked' : '' }}>
                            </div>
                        </div>
                    </div>

                    <div class="admin-form-footer">
                        <a href="{{ route('admin.classes.index') }}" class="admin-btn admin-btn-secondary">
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
            AdminTables.initAdminForm(document.getElementById('class-edit-form'));
        }
    });
</script>
@endpush
