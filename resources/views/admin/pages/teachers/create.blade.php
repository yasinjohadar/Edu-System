@extends('admin.layouts.master')

@section('page-title')
    إضافة معلم جديد
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
                    <h1>إضافة معلم جديد</h1>
                    <p>تسجيل معلم جديد في النظام</p>
                </div>
                <a href="{{ route('admin.teachers.index') }}" class="admin-btn admin-btn-secondary">
                    <i class="ri-arrow-right-line"></i>
                    العودة للقائمة
                </a>
            </div>

            <div class="admin-page-card">
                <form action="{{ route('admin.teachers.store') }}" method="POST" enctype="multipart/form-data" class="admin-form" id="teacher-create-form">
                    @csrf

                    <div class="admin-form-body">
                        <div class="admin-form-section">
                            <div class="admin-form-section-head">
                                <div class="admin-section-icon admin-section-icon-blue">
                                    <i class="ri-user-line"></i>
                                </div>
                                <div>
                                    <h3>البيانات الأساسية</h3>
                                    <p>الاسم وبيانات الحساب</p>
                                </div>
                            </div>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="admin-form-field">
                                        <label class="admin-form-label">الاسم الكامل <span class="required">*</span></label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                                               name="name" value="{{ old('name') }}" required>
                                        @error('name')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="admin-form-field">
                                        <label class="admin-form-label">البريد الإلكتروني <span class="required">*</span></label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                                               name="email" value="{{ old('email') }}" required>
                                        @error('email')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="admin-form-field">
                                        <label class="admin-form-label">رقم الهاتف</label>
                                        <input type="text" class="form-control @error('phone') is-invalid @enderror"
                                               name="phone" value="{{ old('phone') }}">
                                        @error('phone')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="admin-form-field">
                                        <label class="admin-form-label">رقم المعلم <span class="required">*</span></label>
                                        <input type="text" class="form-control @error('teacher_code') is-invalid @enderror"
                                               name="teacher_code" value="{{ old('teacher_code') }}" required>
                                        @error('teacher_code')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="admin-form-field">
                                        <label class="admin-form-label">كلمة المرور <span class="required">*</span></label>
                                        <input type="password" class="form-control @error('password') is-invalid @enderror"
                                               name="password" required>
                                        @error('password')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="admin-form-field">
                                        <label class="admin-form-label">تأكيد كلمة المرور <span class="required">*</span></label>
                                        <input type="password" class="form-control" name="password_confirmation" required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="admin-form-section">
                            <div class="admin-form-section-head">
                                <div class="admin-section-icon admin-section-icon-green">
                                    <i class="ri-profile-line"></i>
                                </div>
                                <div>
                                    <h3>البيانات الشخصية والمهنية</h3>
                                    <p>التفاصيل الشخصية والمؤهلات</p>
                                </div>
                            </div>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="admin-form-field">
                                        <label class="admin-form-label">تاريخ الميلاد</label>
                                        <input type="date" class="form-control @error('date_of_birth') is-invalid @enderror"
                                               name="date_of_birth" value="{{ old('date_of_birth') }}">
                                        @error('date_of_birth')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="admin-form-field">
                                        <label class="admin-form-label">الجنس</label>
                                        <select class="form-select @error('gender') is-invalid @enderror" name="gender" data-admin-choices>
                                            <option value="">اختر</option>
                                            <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>ذكر</option>
                                            <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>أنثى</option>
                                        </select>
                                        @error('gender')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="admin-form-field">
                                        <label class="admin-form-label">العنوان</label>
                                        <textarea class="form-control @error('address') is-invalid @enderror"
                                                  name="address" rows="2">{{ old('address') }}</textarea>
                                        @error('address')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="admin-form-field">
                                        <label class="admin-form-label">تاريخ التعيين</label>
                                        <input type="date" class="form-control @error('hire_date') is-invalid @enderror"
                                               name="hire_date" value="{{ old('hire_date') }}">
                                        @error('hire_date')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="admin-form-field">
                                        <label class="admin-form-label">المؤهل العلمي</label>
                                        <input type="text" class="form-control @error('qualification') is-invalid @enderror"
                                               name="qualification" value="{{ old('qualification') }}">
                                        @error('qualification')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="admin-form-field">
                                        <label class="admin-form-label">التخصص</label>
                                        <input type="text" class="form-control @error('specialization') is-invalid @enderror"
                                               name="specialization" value="{{ old('specialization') }}">
                                        @error('specialization')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="admin-form-field">
                                        <label class="admin-form-label">سنوات الخبرة</label>
                                        <input type="text" class="form-control @error('experience_years') is-invalid @enderror"
                                               name="experience_years" value="{{ old('experience_years') }}">
                                        @error('experience_years')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="admin-form-field">
                                        <label class="admin-form-label">الراتب</label>
                                        <input type="number" step="0.01" class="form-control @error('salary') is-invalid @enderror"
                                               name="salary" value="{{ old('salary') }}" min="0">
                                        @error('salary')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="admin-form-field">
                                        <label class="admin-form-label">الحالة <span class="required">*</span></label>
                                        <select class="form-select @error('status') is-invalid @enderror" name="status" data-admin-choices required>
                                            <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>نشط</option>
                                            <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>غير نشط</option>
                                            <option value="on_leave" {{ old('status') == 'on_leave' ? 'selected' : '' }}>في إجازة</option>
                                            <option value="resigned" {{ old('status') == 'resigned' ? 'selected' : '' }}>استقال</option>
                                        </select>
                                        @error('status')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="admin-form-field">
                                        <label class="admin-form-label">المواد التي يدرسها</label>
                                        <select class="form-select @error('subjects') is-invalid @enderror" name="subjects[]" data-admin-choices multiple>
                                            @foreach ($subjects as $subject)
                                                <option value="{{ $subject->id }}" {{ in_array($subject->id, old('subjects', [])) ? 'selected' : '' }}>
                                                    {{ $subject->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('subjects')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="admin-form-field">
                                        <label class="admin-form-label">ملاحظات</label>
                                        <textarea class="form-control @error('notes') is-invalid @enderror"
                                                  name="notes" rows="3">{{ old('notes') }}</textarea>
                                        @error('notes')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="admin-form-field">
                                        <label class="admin-form-label">الصورة</label>
                                        <input type="file" class="form-control @error('photo') is-invalid @enderror"
                                               name="photo" accept="image/*">
                                        @error('photo')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="admin-form-footer">
                        <a href="{{ route('admin.teachers.index') }}" class="admin-btn admin-btn-secondary">
                            <i class="ri-close-line"></i>
                            إلغاء
                        </a>
                        <button type="submit" class="admin-btn admin-btn-primary">
                            <i class="ri-save-line"></i>
                            حفظ المعلم
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
            AdminTables.initAdminForm(document.getElementById('teacher-create-form'));
        }
    });
</script>
@endpush
