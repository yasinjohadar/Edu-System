@extends('admin.layouts.master')

@section('page-title')
    إنشاء مستخدم جديد
@stop

@section('content')

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="إغلاق"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="إغلاق"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li class="small">{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="إغلاق"></button>
        </div>
    @endif

    <div class="main-content app-content">
        <div class="container-fluid">

            <div class="admin-page-header">
                <div class="page-title-wrap">
                    <h1>إنشاء مستخدم جديد</h1>
                    <p>أضف حساباً جديداً وحدد الأدوار والصلاحيات</p>
                </div>
                <a href="{{ route('users.index') }}" class="admin-btn admin-btn-secondary">
                    <i class="ri-arrow-right-line"></i>
                    العودة للقائمة
                </a>
            </div>

            <div class="admin-page-card">
                <form method="POST" action="{{ route('users.store') }}" enctype="multipart/form-data" class="admin-form" id="user-create-form">
                    @csrf

                    <div class="admin-form-body">

                        {{-- المعلومات الأساسية --}}
                        <div class="admin-form-section">
                            <div class="admin-form-section-head">
                                <div class="admin-section-icon admin-section-icon-blue">
                                    <i class="ri-user-line"></i>
                                </div>
                                <div>
                                    <h3>المعلومات الأساسية</h3>
                                    <p>الاسم وبيانات الاتصال</p>
                                </div>
                            </div>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="admin-form-field">
                                        <label class="admin-form-label">الاسم الكامل <span class="required">*</span></label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                                               name="name" placeholder="أدخل الاسم الكامل" value="{{ old('name') }}" required>
                                        @error('name')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="admin-form-field">
                                        <label class="admin-form-label">اسم المستخدم</label>
                                        <input type="text" class="form-control @error('username') is-invalid @enderror"
                                               name="username" placeholder="اسم المستخدم (اختياري)" value="{{ old('username') }}">
                                        @error('username')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="admin-form-field">
                                        <label class="admin-form-label">البريد الإلكتروني <span class="required">*</span></label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                                               name="email" placeholder="example@domain.com" value="{{ old('email') }}"
                                               autocomplete="off" required>
                                        @error('email')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="admin-form-field">
                                        <label class="admin-form-label">رقم الهاتف</label>
                                        <input type="tel" class="form-control @error('phone') is-invalid @enderror"
                                               name="phone" placeholder="05xxxxxxxx" value="{{ old('phone') }}">
                                        @error('phone')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- كلمة المرور --}}
                        <div class="admin-form-section">
                            <div class="admin-form-section-head">
                                <div class="admin-section-icon admin-section-icon-green">
                                    <i class="ri-lock-password-line"></i>
                                </div>
                                <div>
                                    <h3>كلمة المرور</h3>
                                    <p>تعيين كلمة مرور آمنة للحساب</p>
                                </div>
                            </div>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="admin-form-field">
                                        <label class="admin-form-label">كلمة المرور <span class="required">*</span></label>
                                        <div class="admin-password-wrap">
                                            <input type="password" id="password" class="form-control @error('password') is-invalid @enderror"
                                                   name="password" placeholder="••••••••" required>
                                            <button type="button" class="admin-password-toggle" data-toggle-password="password" aria-label="إظهار كلمة المرور">
                                                <i class="ri-eye-line"></i>
                                            </button>
                                        </div>
                                        @error('password')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="admin-form-field">
                                        <label class="admin-form-label">تأكيد كلمة المرور <span class="required">*</span></label>
                                        <div class="admin-password-wrap">
                                            <input type="password" id="password_confirmation" class="form-control @error('password_confirmation') is-invalid @enderror"
                                                   name="password_confirmation" placeholder="••••••••" required>
                                            <button type="button" class="admin-password-toggle" data-toggle-password="password_confirmation" aria-label="إظهار كلمة المرور">
                                                <i class="ri-eye-line"></i>
                                            </button>
                                        </div>
                                        @error('password_confirmation')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- الحساب والصورة --}}
                        <div class="admin-form-section">
                            <div class="admin-form-section-head">
                                <div class="admin-section-icon admin-section-icon-amber">
                                    <i class="ri-settings-3-line"></i>
                                </div>
                                <div>
                                    <h3>إعدادات الحساب</h3>
                                    <p>الصورة والحالة والتفعيل</p>
                                </div>
                            </div>

                            <div class="row g-3 align-items-start">
                                <div class="col-md-6">
                                    <div class="admin-form-field">
                                        <label class="admin-form-label">صورة المستخدم</label>
                                        <div class="admin-photo-zone">
                                            <div class="admin-photo-preview-wrap">
                                                <img id="photo-preview" src="{{ asset('assets/images/faces/default-avatar.jpg') }}"
                                                     alt="صورة المستخدم" class="admin-photo-preview">
                                                <input type="file" name="photo" id="photo-input" accept="image/*"
                                                       data-photo-preview="photo-preview">
                                            </div>
                                            <div class="admin-photo-actions">
                                                <label for="photo-input" class="admin-photo-btn mb-0">
                                                    <i class="ri-image-add-line"></i>
                                                    اختر صورة
                                                </label>
                                                <p class="admin-photo-hint">JPG أو PNG — بحد أقصى 2 ميجابايت</p>
                                            </div>
                                        </div>
                                        @error('photo')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="admin-form-field mb-3">
                                        <label class="admin-form-label">حالة المستخدم</label>
                                        <select class="form-select @error('status') is-invalid @enderror" name="status" data-admin-choices>
                                            <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>نشط</option>
                                            <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>غير نشط</option>
                                            <option value="banned" {{ old('status') == 'banned' ? 'selected' : '' }}>محظور</option>
                                        </select>
                                        @error('status')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="admin-form-switch-card">
                                        <div class="switch-info">
                                            <strong>تفعيل الحساب</strong>
                                            <span>السماح بتسجيل الدخول فور الإنشاء</span>
                                        </div>
                                        <input class="form-check-input" type="checkbox" name="is_active" value="1"
                                               id="is_active" {{ old('is_active', true) ? 'checked' : '' }}>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- الأدوار --}}
                        <div class="admin-form-section">
                            <div class="admin-form-section-head">
                                <div class="admin-section-icon admin-section-icon-purple">
                                    <i class="ri-shield-user-line"></i>
                                </div>
                                <div>
                                    <h3>الأدوار والصلاحيات</h3>
                                    <p>حدد دوراً واحداً أو أكثر للمستخدم</p>
                                </div>
                            </div>

                            <div class="admin-role-grid">
                                @foreach ($roles as $role)
                                    <label class="admin-role-chip">
                                        <input type="checkbox" name="roles[]" value="{{ $role->name }}"
                                               {{ in_array($role->name, old('roles', [])) ? 'checked' : '' }}>
                                        <span>
                                            <i class="ri-user-star-line"></i>
                                            {{ $role->name }}
                                        </span>
                                    </label>
                                @endforeach
                            </div>
                            @error('roles')
                                <div class="text-danger small mt-2">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>

                    <div class="admin-form-footer">
                        <a href="{{ route('users.index') }}" class="admin-btn admin-btn-secondary">
                            <i class="ri-close-line"></i>
                            إلغاء
                        </a>
                        <button type="submit" class="admin-btn admin-btn-primary">
                            <i class="ri-save-line"></i>
                            حفظ بيانات المستخدم
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
            AdminTables.initAdminForm(document.getElementById('user-create-form'));
        }
    });
</script>
@endpush
