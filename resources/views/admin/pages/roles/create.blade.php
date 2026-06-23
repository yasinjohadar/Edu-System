@extends('admin.layouts.master')

@section('page-title')
    إنشاء دور جديد
@stop

@section('content')
    @if (\Session::has('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {!! \Session::get('success') !!}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

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
                    <h1>إنشاء دور جديد</h1>
                    <p>تعريف دور جديد وربطه بالصلاحيات المناسبة</p>
                </div>
                <a href="{{ route('roles.index') }}" class="admin-btn admin-btn-secondary">
                    <i class="ri-arrow-right-line"></i>
                    العودة للقائمة
                </a>
            </div>

            <div class="admin-page-card">
                <form method="POST" action="{{ route('roles.store') }}" class="admin-form" id="role-create-form">
                    @csrf

                    <div class="admin-form-body">
                        <div class="admin-form-section">
                            <div class="admin-form-section-head">
                                <div class="admin-section-icon admin-section-icon-blue">
                                    <i class="ri-shield-user-line"></i>
                                </div>
                                <div>
                                    <h3>بيانات الدور</h3>
                                    <p>اسم الدور كما يظهر في النظام</p>
                                </div>
                            </div>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="admin-form-field">
                                        <label class="admin-form-label">اسم الدور <span class="required">*</span></label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                                               name="name" value="{{ old('name') }}"
                                               placeholder="مثال: supervisor" required>
                                        @error('name')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="admin-form-section">
                            <div class="admin-form-section-head">
                                <div class="admin-section-icon admin-section-icon-purple">
                                    <i class="ri-key-2-line"></i>
                                </div>
                                <div>
                                    <h3>الصلاحيات</h3>
                                    <p>حدد الصلاحيات الممنوحة لهذا الدور</p>
                                </div>
                            </div>

                            @include('admin.partials.role-permissions-picker', [
                                'permissionGroups' => $permissionGroups,
                                'selectedPermissions' => old('permissions', []),
                            ])
                            @error('permissions')<div class="text-danger small mt-2">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="admin-form-footer">
                        <a href="{{ route('roles.index') }}" class="admin-btn admin-btn-secondary">
                            <i class="ri-close-line"></i>
                            إلغاء
                        </a>
                        <button type="submit" class="admin-btn admin-btn-primary">
                            <i class="ri-save-line"></i>
                            حفظ الدور
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
            AdminTables.initAdminForm(document.getElementById('role-create-form'));
        }
    });
</script>
@endpush
