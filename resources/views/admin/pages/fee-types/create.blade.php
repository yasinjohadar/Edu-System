@extends('admin.layouts.master')

@section('page-title')
    إضافة نوع رسوم جديد
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
                    <h1>إضافة نوع رسوم جديد</h1>
                    <p>تعريف نوع رسوم جديد مع الفئة والمبلغ الافتراضي</p>
                </div>
                <a href="{{ route('admin.fee-types.index') }}" class="admin-btn admin-btn-secondary">
                    <i class="ri-arrow-right-line"></i>
                    العودة للقائمة
                </a>
            </div>

            <div class="admin-page-card">
                <form action="{{ route('admin.fee-types.store') }}" method="POST" class="admin-form" id="fee-type-create-form">
                    @csrf

                    <div class="admin-form-body">
                        <div class="admin-form-section">
                            <div class="admin-form-section-head">
                                <div class="admin-section-icon admin-section-icon-blue">
                                    <i class="ri-money-dollar-circle-line"></i>
                                </div>
                                <div>
                                    <h3>بيانات نوع الرسوم</h3>
                                    <p>الاسم والرمز والفئة</p>
                                </div>
                            </div>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="admin-form-field">
                                        <label class="admin-form-label">الاسم <span class="required">*</span></label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                                               name="name" value="{{ old('name') }}" placeholder="مثال: رسوم دراسية" required>
                                        @error('name')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="admin-form-field">
                                        <label class="admin-form-label">الاسم بالإنجليزية</label>
                                        <input type="text" class="form-control @error('name_en') is-invalid @enderror"
                                               name="name_en" value="{{ old('name_en') }}" placeholder="Tuition Fee">
                                        @error('name_en')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="admin-form-field">
                                        <label class="admin-form-label">الرمز <span class="required">*</span></label>
                                        <input type="text" class="form-control @error('code') is-invalid @enderror"
                                               name="code" value="{{ old('code') }}" placeholder="TUITION-001" required>
                                        <small class="admin-form-hint">رمز فريد لنوع الرسوم</small>
                                        @error('code')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="admin-form-field">
                                        <label class="admin-form-label">الفئة <span class="required">*</span></label>
                                        <select class="form-select @error('category') is-invalid @enderror"
                                                name="category" data-admin-choices required>
                                            @foreach ($categories as $key => $label)
                                                <option value="{{ $key }}" {{ old('category') === $key ? 'selected' : '' }}>{{ $label }}</option>
                                            @endforeach
                                        </select>
                                        @error('category')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="admin-form-field">
                                        <label class="admin-form-label">الوصف</label>
                                        <textarea class="form-control @error('description') is-invalid @enderror"
                                                  name="description" rows="3" placeholder="وصف اختياري لنوع الرسوم">{{ old('description') }}</textarea>
                                        @error('description')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="admin-form-section">
                            <div class="admin-form-section-head">
                                <div class="admin-section-icon admin-section-icon-green">
                                    <i class="ri-price-tag-3-line"></i>
                                </div>
                                <div>
                                    <h3>المبلغ والتكرار</h3>
                                    <p>المبلغ الافتراضي وإعدادات التكرار</p>
                                </div>
                            </div>

                            <div class="row g-3">
                                <div class="col-md-4">
                                    <div class="admin-form-field">
                                        <label class="admin-form-label">المبلغ الافتراضي <span class="required">*</span></label>
                                        <input type="number" class="form-control @error('default_amount') is-invalid @enderror"
                                               name="default_amount" step="0.01" min="0"
                                               value="{{ old('default_amount', 0) }}" required>
                                        @error('default_amount')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="admin-form-field">
                                        <label class="admin-form-label">ترتيب العرض</label>
                                        <input type="number" class="form-control @error('sort_order') is-invalid @enderror"
                                               name="sort_order" min="0" value="{{ old('sort_order', 0) }}">
                                        @error('sort_order')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="col-md-4" id="recurring_period_wrap" style="{{ old('is_recurring') ? '' : 'display:none;' }}">
                                    <div class="admin-form-field">
                                        <label class="admin-form-label">فترة التكرار</label>
                                        <select class="form-select @error('recurring_period') is-invalid @enderror"
                                                name="recurring_period" data-admin-choices>
                                            @foreach ($recurringPeriods as $key => $label)
                                                <option value="{{ $key }}" {{ old('recurring_period') === $key ? 'selected' : '' }}>{{ $label }}</option>
                                            @endforeach
                                        </select>
                                        @error('recurring_period')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row g-3 mt-1">
                                <div class="col-md-6">
                                    <label class="admin-form-switch-card">
                                        <input type="checkbox" name="is_recurring" id="is_recurring" value="1"
                                               {{ old('is_recurring') ? 'checked' : '' }}>
                                        <span class="admin-form-switch-track"></span>
                                        <span class="admin-form-switch-label">
                                            <strong>رسوم متكررة</strong>
                                            <small>يتم تحصيلها بشكل دوري</small>
                                        </span>
                                    </label>
                                </div>
                                <div class="col-md-6">
                                    <div class="admin-form-field">
                                        <label class="admin-form-label">الحالة</label>
                                        <select class="form-select" name="is_active" data-admin-choices>
                                            <option value="1" {{ old('is_active', '1') == '1' ? 'selected' : '' }}>نشط</option>
                                            <option value="0" {{ old('is_active') == '0' ? 'selected' : '' }}>غير نشط</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="admin-form-footer">
                        <button type="submit" class="admin-btn admin-btn-primary">
                            <i class="ri-save-line"></i>
                            حفظ نوع الرسوم
                        </button>
                        <a href="{{ route('admin.fee-types.index') }}" class="admin-btn admin-btn-secondary">إلغاء</a>
                    </div>
                </form>
            </div>

        </div>
    </div>
@stop

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        AdminTables.initAdminForm('#fee-type-create-form');

        const recurringCheckbox = document.getElementById('is_recurring');
        const periodWrap = document.getElementById('recurring_period_wrap');

        function toggleRecurringPeriod() {
            periodWrap.style.display = recurringCheckbox.checked ? '' : 'none';
        }

        recurringCheckbox.addEventListener('change', toggleRecurringPeriod);
        toggleRecurringPeriod();
    });
</script>
@endpush
