@extends('admin.layouts.master')

@section('page-title')
    تعديل المستخدم
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
                    <h1>تعديل المستخدم: {{ $user->name }}</h1>
                    <p>تحديث البيانات والأدوار — كلمة المرور تُغيّر بشكل مستقل</p>
                </div>
                <div class="d-flex flex-wrap gap-2">
                    <button type="button" class="admin-btn admin-btn-key"
                            data-bs-toggle="modal" data-bs-target="#change_password{{ $user->id }}">
                        <i class="ri-lock-password-line"></i>
                        تغيير كلمة المرور
                    </button>
                    <a href="{{ route('users.index') }}" class="admin-btn admin-btn-secondary">
                        <i class="ri-arrow-right-line"></i>
                        العودة للقائمة
                    </a>
                </div>
            </div>

            <div class="admin-page-card">
                <form method="POST" action="{{ route('users.update', $user->id) }}" enctype="multipart/form-data"
                      class="admin-form" id="user-edit-form">
                    @csrf
                    @method('PUT')

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
                                               name="name" placeholder="أدخل الاسم الكامل"
                                               value="{{ old('name', $user->name) }}" required>
                                        @error('name')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="admin-form-field">
                                        <label class="admin-form-label">اسم المستخدم</label>
                                        <input type="text" class="form-control @error('username') is-invalid @enderror"
                                               name="username" placeholder="اسم المستخدم (اختياري)"
                                               value="{{ old('username', $user->username) }}">
                                        @error('username')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="admin-form-field">
                                        <label class="admin-form-label">البريد الإلكتروني <span class="required">*</span></label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                                               name="email" placeholder="example@domain.com"
                                               value="{{ old('email', $user->email) }}" autocomplete="off" required>
                                        @error('email')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="admin-form-field">
                                        <label class="admin-form-label">رقم الهاتف</label>
                                        <input type="tel" class="form-control @error('phone') is-invalid @enderror"
                                               name="phone" placeholder="05xxxxxxxx"
                                               value="{{ old('phone', $user->phone) }}">
                                        @error('phone')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- إعدادات الحساب --}}
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
                                                <img id="photo-preview"
                                                     src="{{ $user->photo ? asset('storage/' . $user->photo) : asset('assets/images/faces/default-avatar.jpg') }}"
                                                     alt="صورة المستخدم" class="admin-photo-preview">
                                                <input type="file" name="photo" id="photo-input" accept="image/*"
                                                       data-photo-preview="photo-preview">
                                            </div>
                                            <div class="admin-photo-actions">
                                                <label for="photo-input" class="admin-photo-btn mb-0">
                                                    <i class="ri-image-add-line"></i>
                                                    تغيير الصورة
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
                                            <option value="active" {{ old('status', $user->status) == 'active' ? 'selected' : '' }}>نشط</option>
                                            <option value="inactive" {{ old('status', $user->status) == 'inactive' ? 'selected' : '' }}>غير نشط</option>
                                            <option value="banned" {{ old('status', $user->status) == 'banned' ? 'selected' : '' }}>محظور</option>
                                        </select>
                                        @error('status')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="admin-form-switch-card">
                                        <div class="switch-info">
                                            <strong>تفعيل الحساب</strong>
                                            <span>السماح للمستخدم بتسجيل الدخول</span>
                                        </div>
                                        <input class="form-check-input" type="checkbox" name="is_active" value="1"
                                               id="is_active" {{ old('is_active', $user->is_active) ? 'checked' : '' }}>
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
                                               {{ in_array($role->name, old('roles', $user->getRoleNames()->toArray())) ? 'checked' : '' }}>
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
                            حفظ التعديلات
                        </button>
                    </div>

                </form>
            </div>

        </div>
    </div>

    @include('admin.pages.users.change_password')

@stop

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (window.AdminTables && AdminTables.initAdminForm) {
            AdminTables.initAdminForm(document.getElementById('user-edit-form'));
        }
    });
</script>
@endpush
