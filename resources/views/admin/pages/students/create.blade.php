@extends('admin.layouts.master')

@section('page-title')
    إضافة طالب جديد
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
                    <h1>إضافة طالب جديد</h1>
                    <p>تسجيل طالب جديد في النظام</p>
                </div>
                <a href="{{ route('admin.students.index') }}" class="admin-btn admin-btn-secondary">
                    <i class="ri-arrow-right-line"></i>
                    العودة للقائمة
                </a>
            </div>

            <div class="admin-page-card">
                <form action="{{ route('admin.students.store') }}" method="POST" enctype="multipart/form-data" class="admin-form" id="student-create-form">
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
                                        <label class="admin-form-label">رقم القيد <span class="required">*</span></label>
                                        <input type="text" class="form-control @error('student_code') is-invalid @enderror"
                                               name="student_code" value="{{ old('student_code') }}" required>
                                        @error('student_code')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
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
                                    <h3>البيانات الشخصية</h3>
                                    <p>التفاصيل الشخصية والعنوان</p>
                                </div>
                            </div>

                            <div class="row g-3">
                                <div class="col-md-4">
                                    <div class="admin-form-field">
                                        <label class="admin-form-label">تاريخ الميلاد</label>
                                        <input type="date" class="form-control @error('date_of_birth') is-invalid @enderror"
                                               name="date_of_birth" value="{{ old('date_of_birth') }}">
                                        @error('date_of_birth')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
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
                                <div class="col-md-4">
                                    <div class="admin-form-field">
                                        <label class="admin-form-label">تاريخ التسجيل</label>
                                        <input type="date" class="form-control @error('enrollment_date') is-invalid @enderror"
                                               name="enrollment_date" value="{{ old('enrollment_date', date('Y-m-d')) }}">
                                        @error('enrollment_date')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
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
                            </div>
                        </div>

                        <div class="admin-form-section">
                            <div class="admin-form-section-head">
                                <div class="admin-section-icon admin-section-icon-amber">
                                    <i class="ri-book-open-line"></i>
                                </div>
                                <div>
                                    <h3>البيانات الأكاديمية</h3>
                                    <p>الصف والفصل والحالة</p>
                                </div>
                            </div>

                            <div class="row g-3">
                                <div class="col-md-4">
                                    <div class="admin-form-field">
                                        <label class="admin-form-label">الصف</label>
                                        <select class="form-select @error('class_id') is-invalid @enderror" name="class_id" id="class_id" data-admin-choices>
                                            <option value="">اختر الصف</option>
                                            @foreach ($classes as $class)
                                                <option value="{{ $class->id }}" {{ old('class_id') == $class->id ? 'selected' : '' }}>
                                                    {{ $class->grade->name }} - {{ $class->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('class_id')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="admin-form-field">
                                        <label class="admin-form-label">الفصل</label>
                                        <select class="form-select @error('section_id') is-invalid @enderror" name="section_id" id="section_id" data-admin-choices>
                                            <option value="">اختر الفصل</option>
                                            @foreach ($sections as $section)
                                                <option value="{{ $section->id }}" data-class-id="{{ $section->class_id }}"
                                                        {{ old('section_id') == $section->id ? 'selected' : '' }}>
                                                    {{ $section->class->grade->name }} - {{ $section->class->name }} - {{ $section->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('section_id')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="admin-form-field">
                                        <label class="admin-form-label">الحالة <span class="required">*</span></label>
                                        <select class="form-select @error('status') is-invalid @enderror" name="status" data-admin-choices required>
                                            <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>نشط</option>
                                            <option value="graduated" {{ old('status') == 'graduated' ? 'selected' : '' }}>متخرج</option>
                                            <option value="transferred" {{ old('status') == 'transferred' ? 'selected' : '' }}>منقول</option>
                                            <option value="suspended" {{ old('status') == 'suspended' ? 'selected' : '' }}>معلق</option>
                                        </select>
                                        @error('status')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="admin-form-section">
                            <div class="admin-form-section-head">
                                <div class="admin-section-icon admin-section-icon-blue">
                                    <i class="ri-parent-line"></i>
                                </div>
                                <div>
                                    <h3>أولياء الأمور</h3>
                                    <p>بيانات التواصل وربط أولياء الأمور</p>
                                </div>
                            </div>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="admin-form-field">
                                        <label class="admin-form-label">اسم ولي الأمر الأساسي</label>
                                        <input type="text" class="form-control @error('parent_guardian') is-invalid @enderror"
                                               name="parent_guardian" value="{{ old('parent_guardian') }}">
                                        @error('parent_guardian')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="admin-form-field">
                                        <label class="admin-form-label">جهة الاتصال في الطوارئ</label>
                                        <input type="text" class="form-control @error('emergency_contact') is-invalid @enderror"
                                               name="emergency_contact" value="{{ old('emergency_contact') }}">
                                        @error('emergency_contact')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="admin-form-field">
                                        <label class="admin-form-label">ربط بأولياء الأمور المسجلين</label>
                                        <select class="form-select @error('parent_ids') is-invalid @enderror" name="parent_ids[]" data-admin-choices multiple>
                                            @foreach ($parents as $parent)
                                                <option value="{{ $parent->id }}" {{ in_array($parent->id, old('parent_ids', [])) ? 'selected' : '' }}>
                                                    {{ $parent->user->name }} - {{ $parent->parent_code }} ({{ $parent->relationship }})
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('parent_ids')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="admin-form-section">
                            <div class="admin-form-section-head">
                                <div class="admin-section-icon admin-section-icon-green">
                                    <i class="ri-folder-upload-line"></i>
                                </div>
                                <div>
                                    <h3>الملفات</h3>
                                    <p>الصورة والشهادات</p>
                                </div>
                            </div>

                            <div class="row g-3">
                                <div class="col-md-4">
                                    <div class="admin-form-field">
                                        <label class="admin-form-label">صورة الطالب</label>
                                        <input type="file" class="form-control @error('photo') is-invalid @enderror"
                                               name="photo" accept="image/*">
                                        @error('photo')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="admin-form-field">
                                        <label class="admin-form-label">شهادة الميلاد</label>
                                        <input type="file" class="form-control @error('birth_certificate') is-invalid @enderror"
                                               name="birth_certificate" accept=".pdf,.jpg,.jpeg,.png">
                                        @error('birth_certificate')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="admin-form-field">
                                        <label class="admin-form-label">الشهادة الصحية</label>
                                        <input type="file" class="form-control @error('health_certificate') is-invalid @enderror"
                                               name="health_certificate" accept=".pdf,.jpg,.jpeg,.png">
                                        @error('health_certificate')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="admin-form-section">
                            <div class="admin-form-section-head">
                                <div class="admin-section-icon admin-section-icon-amber">
                                    <i class="ri-sticky-note-line"></i>
                                </div>
                                <div>
                                    <h3>ملاحظات</h3>
                                    <p>ملاحظات طبية إضافية</p>
                                </div>
                            </div>

                            <div class="admin-form-field">
                                <label class="admin-form-label">ملاحظات طبية</label>
                                <textarea class="form-control @error('medical_notes') is-invalid @enderror"
                                          name="medical_notes" rows="3">{{ old('medical_notes') }}</textarea>
                                @error('medical_notes')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>

                    <div class="admin-form-footer">
                        <a href="{{ route('admin.students.index') }}" class="admin-btn admin-btn-secondary">
                            <i class="ri-close-line"></i>
                            إلغاء
                        </a>
                        <button type="submit" class="admin-btn admin-btn-primary">
                            <i class="ri-save-line"></i>
                            حفظ الطالب
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
            AdminTables.initAdminForm(document.getElementById('student-create-form'));
        }

        const classSelect = document.getElementById('class_id');
        const sectionSelect = document.getElementById('section_id');
        if (!classSelect || !sectionSelect) return;

        const allSections = Array.from(sectionSelect.options);

        function filterSections() {
            const selectedClassId = classSelect.value;
            sectionSelect.innerHTML = '<option value="">اختر الفصل</option>';

            allSections.forEach(function (option) {
                if (!option.value) return;
                if (!selectedClassId || option.dataset.classId == selectedClassId) {
                    sectionSelect.appendChild(option.cloneNode(true));
                }
            });
        }

        classSelect.addEventListener('change', filterSections);
        filterSections();
    });
</script>
@endpush
