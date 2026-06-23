@extends('admin.layouts.master')

@section('page-title')
    تعديل الفصل
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
                    <h1>تعديل الفصل: {{ $section->name }}</h1>
                    <p>تحديث بيانات الفصل الدراسي</p>
                </div>
                <a href="{{ route('admin.sections.index') }}" class="admin-btn admin-btn-secondary">
                    <i class="ri-arrow-right-line"></i>
                    العودة للقائمة
                </a>
            </div>

            <div class="admin-page-card">
                <form action="{{ route('admin.sections.update', $section->id) }}" method="POST" class="admin-form" id="section-edit-form">
                    @csrf
                    @method('PUT')

                    <div class="admin-form-body">
                        <div class="admin-form-section">
                            <div class="admin-form-section-head">
                                <div class="admin-section-icon admin-section-icon-blue">
                                    <i class="ri-group-line"></i>
                                </div>
                                <div>
                                    <h3>بيانات الفصل</h3>
                                    <p>الاسم والصف والسعة</p>
                                </div>
                            </div>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="admin-form-field">
                                        <label class="admin-form-label">الصف <span class="required">*</span></label>
                                        <select class="form-select @error('class_id') is-invalid @enderror"
                                                name="class_id" data-admin-choices required>
                                            <option value="">اختر الصف</option>
                                            @foreach ($classes as $class)
                                                <option value="{{ $class->id }}" {{ old('class_id', $section->class_id) == $class->id ? 'selected' : '' }}>{{ $class->grade->name }} — {{ $class->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('class_id')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="admin-form-field">
                                        <label class="admin-form-label">اسم الفصل <span class="required">*</span></label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                                               name="name" value="{{ old('name', $section->name) }}" required>
                                        @error('name')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="admin-form-field">
                                        <label class="admin-form-label">الاسم بالإنجليزية</label>
                                        <input type="text" class="form-control @error('name_en') is-invalid @enderror"
                                               name="name_en" value="{{ old('name_en', $section->name_en) }}">
                                        @error('name_en')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="admin-form-field">
                                        <label class="admin-form-label">السعة القصوى <span class="required">*</span></label>
                                        <input type="number" class="form-control @error('capacity') is-invalid @enderror"
                                               name="capacity" value="{{ old('capacity', $section->capacity) }}" min="1" required>
                                        @error('capacity')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="admin-form-field">
                                        <label class="admin-form-label">الوصف</label>
                                        <textarea class="form-control @error('description') is-invalid @enderror"
                                                  name="description" rows="3">{{ old('description', $section->description) }}</textarea>
                                        @error('description')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="admin-form-section">
                            <div class="admin-form-section-head">
                                <div class="admin-section-icon admin-section-icon-green">
                                    <i class="ri-user-star-line"></i>
                                </div>
                                <div>
                                    <h3>المعلم الرئيسي</h3>
                                    <p>تعيين معلم مسؤول عن الفصل</p>
                                </div>
                            </div>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="admin-form-field">
                                        <label class="admin-form-label">المعلم الرئيسي</label>
                                        <select class="form-select @error('class_teacher_id') is-invalid @enderror"
                                                name="class_teacher_id" data-admin-choices>
                                            <option value="">اختر المعلم</option>
                                            @foreach ($teachers as $teacher)
                                                <option value="{{ $teacher->id }}" {{ old('class_teacher_id', $section->class_teacher_id) == $teacher->id ? 'selected' : '' }}>{{ $teacher->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('class_teacher_id')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
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
                                    <p>تفعيل أو إيقاف الفصل</p>
                                </div>
                            </div>

                            <div class="admin-form-switch-card">
                                <div class="switch-info">
                                    <strong>فصل نشط</strong>
                                    <span>إظهار الفصل في النظام والتسجيل</span>
                                </div>
                                <input class="form-check-input" type="checkbox" name="is_active" value="1"
                                       id="is_active" {{ old('is_active', $section->is_active) ? 'checked' : '' }}>
                            </div>
                        </div>
                    </div>

                    <div class="admin-form-footer">
                        <a href="{{ route('admin.sections.index') }}" class="admin-btn admin-btn-secondary">
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
            AdminTables.initAdminForm(document.getElementById('section-edit-form'));
        }
    });
</script>
@endpush
