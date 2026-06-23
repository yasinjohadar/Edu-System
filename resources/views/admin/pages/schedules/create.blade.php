@extends('admin.layouts.master')

@section('page-title')
    إضافة جدول دراسي جديد
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

    @if ($errors->has('time'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ $errors->first('time') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="main-content app-content">
        <div class="container-fluid">

            <div class="admin-page-header">
                <div class="page-title-wrap">
                    <h1>إضافة جدول دراسي جديد</h1>
                    <p>تعريف حصة دراسية في الجدول</p>
                </div>
                <a href="{{ route('admin.schedules.index') }}" class="admin-btn admin-btn-secondary">
                    <i class="ri-arrow-right-line"></i>
                    العودة للقائمة
                </a>
            </div>

            <div class="admin-page-card">
                <form action="{{ route('admin.schedules.store') }}" method="POST" class="admin-form" id="schedule-create-form">
                    @csrf

                    <div class="admin-form-body">
                        <div class="admin-form-section">
                            <div class="admin-form-section-head">
                                <div class="admin-section-icon admin-section-icon-blue">
                                    <i class="ri-calendar-schedule-line"></i>
                                </div>
                                <div>
                                    <h3>معلومات الجدول</h3>
                                    <p>الفصل والمادة والمعلم</p>
                                </div>
                            </div>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="admin-form-field">
                                        <label class="admin-form-label">الفصل الدراسي <span class="required">*</span></label>
                                        <select name="section_id" class="form-select @error('section_id') is-invalid @enderror" data-admin-choices required>
                                            <option value="">— اختر الفصل —</option>
                                            @foreach ($sections as $section)
                                                <option value="{{ $section->id }}" {{ old('section_id') == $section->id ? 'selected' : '' }}>
                                                    {{ $section->class->name }} - {{ $section->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('section_id')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="admin-form-field">
                                        <label class="admin-form-label">المادة الدراسية <span class="required">*</span></label>
                                        <select name="subject_id" class="form-select @error('subject_id') is-invalid @enderror" data-admin-choices required>
                                            <option value="">— اختر المادة —</option>
                                            @foreach ($subjects as $subject)
                                                <option value="{{ $subject->id }}" {{ old('subject_id') == $subject->id ? 'selected' : '' }}>
                                                    {{ $subject->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('subject_id')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="admin-form-field">
                                        <label class="admin-form-label">المعلم <span class="required">*</span></label>
                                        <select name="teacher_id" class="form-select @error('teacher_id') is-invalid @enderror" data-admin-choices required>
                                            <option value="">— اختر المعلم —</option>
                                            @foreach ($teachers as $teacher)
                                                <option value="{{ $teacher->id }}" {{ old('teacher_id') == $teacher->id ? 'selected' : '' }}>
                                                    {{ $teacher->user->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('teacher_id')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="admin-form-field">
                                        <label class="admin-form-label">يوم الأسبوع <span class="required">*</span></label>
                                        <select name="day_of_week" class="form-select @error('day_of_week') is-invalid @enderror" data-admin-choices required>
                                            <option value="">— اختر اليوم —</option>
                                            @foreach ($days as $key => $day)
                                                <option value="{{ $key }}" {{ old('day_of_week') == $key ? 'selected' : '' }}>
                                                    {{ $day }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('day_of_week')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="admin-form-section">
                            <div class="admin-form-section-head">
                                <div class="admin-section-icon admin-section-icon-green">
                                    <i class="ri-time-line"></i>
                                </div>
                                <div>
                                    <h3>التوقيت والقاعة</h3>
                                    <p>أوقات الحصة والترتيب</p>
                                </div>
                            </div>

                            <div class="row g-3">
                                <div class="col-md-4">
                                    <div class="admin-form-field">
                                        <label class="admin-form-label">وقت البداية <span class="required">*</span></label>
                                        <input type="time" name="start_time" class="form-control @error('start_time') is-invalid @enderror"
                                               value="{{ old('start_time', '08:00') }}" required>
                                        @error('start_time')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="admin-form-field">
                                        <label class="admin-form-label">وقت النهاية <span class="required">*</span></label>
                                        <input type="time" name="end_time" class="form-control @error('end_time') is-invalid @enderror"
                                               value="{{ old('end_time', '09:00') }}" required>
                                        @error('end_time')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="admin-form-field">
                                        <label class="admin-form-label">ترتيب الحصة</label>
                                        <input type="number" name="order" class="form-control @error('order') is-invalid @enderror"
                                               value="{{ old('order', 0) }}" min="0">
                                        @error('order')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="admin-form-field">
                                        <label class="admin-form-label">القاعة/الفصل الدراسي</label>
                                        <input type="text" name="room" class="form-control @error('room') is-invalid @enderror"
                                               value="{{ old('room') }}" placeholder="مثال: قاعة 101">
                                        @error('room')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="admin-form-field">
                                        <label class="admin-form-label">ملاحظات</label>
                                        <textarea name="notes" class="form-control @error('notes') is-invalid @enderror"
                                                  rows="3" placeholder="أي ملاحظات إضافية...">{{ old('notes') }}</textarea>
                                        @error('notes')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
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
                                    <p>تفعيل أو إيقاف الجدول</p>
                                </div>
                            </div>

                            <input type="hidden" name="is_active" value="0">
                            <div class="admin-form-switch-card">
                                <div class="switch-info">
                                    <strong>جدول نشط</strong>
                                    <span>إظهار الحصة في الجدول الدراسي</span>
                                </div>
                                <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active"
                                       {{ old('is_active', '1') == '1' ? 'checked' : '' }}>
                            </div>
                        </div>
                    </div>

                    <div class="admin-form-footer">
                        <a href="{{ route('admin.schedules.index') }}" class="admin-btn admin-btn-secondary">
                            <i class="ri-close-line"></i>
                            إلغاء
                        </a>
                        <button type="submit" class="admin-btn admin-btn-primary">
                            <i class="ri-save-line"></i>
                            حفظ الجدول
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
            AdminTables.initAdminForm(document.getElementById('schedule-create-form'));
        }
    });
</script>
@endpush
