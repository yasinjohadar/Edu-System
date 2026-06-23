@extends('admin.layouts.master')

@section('page-title')
    إضافة مادة جديدة
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
                    <h1>إضافة مادة جديدة</h1>
                    <p>تعريف مادة دراسية وربطها بالصفوف</p>
                </div>
                <a href="{{ route('admin.subjects.index') }}" class="admin-btn admin-btn-secondary">
                    <i class="ri-arrow-right-line"></i>
                    العودة للقائمة
                </a>
            </div>

            <div class="admin-page-card">
                <form action="{{ route('admin.subjects.store') }}" method="POST" class="admin-form" id="subject-create-form">
                    @csrf

                    <div class="admin-form-body">
                        <div class="admin-form-section">
                            <div class="admin-form-section-head">
                                <div class="admin-section-icon admin-section-icon-blue">
                                    <i class="ri-book-open-line"></i>
                                </div>
                                <div>
                                    <h3>بيانات المادة</h3>
                                    <p>الاسم والرمز والنوع</p>
                                </div>
                            </div>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="admin-form-field">
                                        <label class="admin-form-label">اسم المادة <span class="required">*</span></label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                                               name="name" value="{{ old('name') }}" placeholder="مثال: الرياضيات" required>
                                        @error('name')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="admin-form-field">
                                        <label class="admin-form-label">الاسم بالإنجليزية</label>
                                        <input type="text" class="form-control @error('name_en') is-invalid @enderror"
                                               name="name_en" value="{{ old('name_en') }}" placeholder="Mathematics">
                                        @error('name_en')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="admin-form-field">
                                        <label class="admin-form-label">رمز المادة</label>
                                        <input type="text" class="form-control @error('code') is-invalid @enderror"
                                               name="code" value="{{ old('code') }}" placeholder="MATH">
                                        @error('code')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="admin-form-field">
                                        <label class="admin-form-label">نوع المادة <span class="required">*</span></label>
                                        <select class="form-select @error('type') is-invalid @enderror"
                                                name="type" data-admin-choices required>
                                            <option value="required" {{ old('type') === 'required' ? 'selected' : '' }}>إجباري</option>
                                            <option value="optional" {{ old('type') === 'optional' ? 'selected' : '' }}>اختياري</option>
                                        </select>
                                        @error('type')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="admin-form-field">
                                        <label class="admin-form-label">عدد الحصص الأسبوعية</label>
                                        <input type="number" class="form-control @error('weekly_hours') is-invalid @enderror"
                                               name="weekly_hours" value="{{ old('weekly_hours', 0) }}" min="0">
                                        @error('weekly_hours')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="admin-form-field">
                                        <label class="admin-form-label">الوصف</label>
                                        <textarea class="form-control @error('description') is-invalid @enderror"
                                                  name="description" rows="3" placeholder="وصف اختياري للمادة">{{ old('description') }}</textarea>
                                        @error('description')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="admin-form-section">
                            <div class="admin-form-section-head">
                                <div class="admin-section-icon admin-section-icon-green">
                                    <i class="ri-bar-chart-line"></i>
                                </div>
                                <div>
                                    <h3>الدرجات</h3>
                                    <p>الدرجة الكاملة ودرجة النجاح</p>
                                </div>
                            </div>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="admin-form-field">
                                        <label class="admin-form-label">الدرجة الكاملة</label>
                                        <input type="number" step="0.01" class="form-control @error('full_marks') is-invalid @enderror"
                                               name="full_marks" value="{{ old('full_marks', 100) }}" min="0">
                                        @error('full_marks')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="admin-form-field">
                                        <label class="admin-form-label">درجة النجاح</label>
                                        <input type="number" step="0.01" class="form-control @error('pass_marks') is-invalid @enderror"
                                               name="pass_marks" value="{{ old('pass_marks', 50) }}" min="0">
                                        @error('pass_marks')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="admin-form-section">
                            <div class="admin-form-section-head">
                                <div class="admin-section-icon admin-section-icon-purple">
                                    <i class="ri-book-2-line"></i>
                                </div>
                                <div>
                                    <h3>الصفوف المرتبطة</h3>
                                    <p>حدد الصفوف التي تُدرَّس فيها هذه المادة</p>
                                </div>
                            </div>

                            <div class="admin-role-grid">
                                @foreach ($classes as $class)
                                    <label class="admin-role-chip">
                                        <input type="checkbox" name="classes[]" value="{{ $class->id }}"
                                               {{ in_array($class->id, old('classes', [])) ? 'checked' : '' }}>
                                        <span>
                                            <i class="ri-book-2-line"></i>
                                            {{ $class->grade->name }} — {{ $class->name }}
                                        </span>
                                    </label>
                                @endforeach
                            </div>
                            @error('classes')
                                <div class="text-danger small mt-2">{{ $message }}</div>
                            @enderror
                            @error('classes.*')
                                <div class="text-danger small mt-2">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="admin-form-section">
                            <div class="admin-form-section-head">
                                <div class="admin-section-icon admin-section-icon-amber">
                                    <i class="ri-toggle-line"></i>
                                </div>
                                <div>
                                    <h3>الحالة</h3>
                                    <p>تفعيل أو إيقاف المادة</p>
                                </div>
                            </div>

                            <div class="admin-form-switch-card">
                                <div class="switch-info">
                                    <strong>مادة نشطة</strong>
                                    <span>إظهار المادة في النظام والجداول</span>
                                </div>
                                <input class="form-check-input" type="checkbox" name="is_active" value="1"
                                       id="is_active" {{ old('is_active', true) ? 'checked' : '' }}>
                            </div>
                        </div>
                    </div>

                    <div class="admin-form-footer">
                        <a href="{{ route('admin.subjects.index') }}" class="admin-btn admin-btn-secondary">
                            <i class="ri-close-line"></i>
                            إلغاء
                        </a>
                        <button type="submit" class="admin-btn admin-btn-primary">
                            <i class="ri-save-line"></i>
                            حفظ المادة
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
            AdminTables.initAdminForm(document.getElementById('subject-create-form'));
        }
    });
</script>
@endpush
