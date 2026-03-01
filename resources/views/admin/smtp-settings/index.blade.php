@extends('admin.layouts.master')

@section('page-title')
    إعدادات SMTP
@stop

@section('content')
    <!-- Start::app-content -->
    <div class="main-content app-content">
        <div class="container-fluid">
            <!-- Page Header -->
            <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
                <div>
                    <h4 class="mb-0">إعدادات SMTP</h4>
                    <p class="mb-0 text-muted">إدارة إعدادات خادم البريد الإلكتروني</p>
                </div>
                <div class="main-dashboard-header-right">
                    <a href="{{ route('admin.smtp-settings.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> إضافة إعدادات جديدة
                    </a>
                </div>
            </div>
            <!-- End Page Header -->
            
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body p-0">
                            @if(session('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif

                            @if(session('error'))
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif

                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead>
                                        <tr>
                                            <th>الاسم</th>
                                            <th>المضيف</th>
                                            <th>المنفذ</th>
                                            <th>عنوان المرسل</th>
                                            <th>التشفير</th>
                                            <th>الحالة</th>
                                            <th>الافتراضي</th>
                                            <th>الإجراءات</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($smtpSettings as $setting)
                                            <tr>
                                                <td>
                                                    <h6 class="mb-0">{{ $setting->name }}</h6>
                                                </td>
                                                <td>{{ $setting->host }}</td>
                                                <td>{{ $setting->port }}</td>
                                                <td>{{ $setting->from_address }}</td>
                                                <td>
                                                    <span class="badge badge-info">{{ $setting->encryption_name }}</span>
                                                </td>
                                                <td>
                                                    @if($setting->is_active)
                                                        <span class="badge badge-success">نشط</span>
                                                    @else
                                                        <span class="badge badge-danger">غير نشط</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($setting->is_default)
                                                        <span class="badge badge-primary">افتراضي</span>
                                                    @else
                                                        <span class="badge badge-secondary">-</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="btn-group">
                                                        <a href="{{ route('admin.smtp-settings.edit', $setting->id) }}" 
                                                           class="btn btn-sm btn-info" title="تعديل">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        
                                                        @if(!$setting->is_default)
                                                            <form action="{{ route('admin.smtp-settings.set-default', $setting->id) }}" 
                                                                  method="POST" style="display: inline;">
                                                                @csrf
                                                                <button type="submit" 
                                                                        class="btn btn-sm btn-warning" 
                                                                        title="تعيين كافتراضي"
                                                                        onclick="return confirm('هل تريد تعيين هذه الإعدادات كافتراضية؟')">
                                                                    <i class="fas fa-star"></i>
                                                                </button>
                                                            </form>
                                                        @endif

                                                        <form action="{{ route('admin.smtp-settings.toggle-active', $setting->id) }}" 
                                                              method="POST" style="display: inline;">
                                                            @csrf
                                                            <button type="submit" 
                                                                    class="btn btn-sm {{ $setting->is_active ? 'btn-danger' : 'btn-success' }}" 
                                                                    title="{{ $setting->is_active ? 'تعطيل' : 'تفعيل' }}">
                                                                <i class="fas {{ $setting->is_active ? 'fa-toggle-on' : 'fa-toggle-off' }}"></i>
                                                            </button>
                                                        </form>

                                                        @if(!$setting->is_default)
                                                            <form action="{{ route('admin.smtp-settings.destroy', $setting->id) }}" 
                                                                  method="POST" style="display: inline;">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" 
                                                                        class="btn btn-sm btn-danger" 
                                                                        title="حذف"
                                                                        onclick="return confirm('هل أنت متأكد من حذف هذه الإعدادات؟')">
                                                                    <i class="fas fa-trash"></i>
                                                                </button>
                                                            </form>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="8" class="text-center py-5">
                                                    <div class="text-muted">
                                                        <i class="fas fa-envelope-open-text fa-3x mb-3"></i>
                                                        <p class="mb-0">لا توجد إعدادات SMTP حالياً</p>
                                                        <a href="{{ route('admin.smtp-settings.create') }}" class="btn btn-primary">
                                                            <i class="fas fa-plus"></i> إضافة إعدادات جديدة
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End::app-content -->
@endsection
