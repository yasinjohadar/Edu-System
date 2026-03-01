@extends('admin.layouts.master')

@section('page-title')
    قائمة المعلمين
@stop

@section('content')
    @if (\Session::has('success'))
        <div class="alert alert-success">
            <ul>
                <li>{!! \Session::get('success') !!}</li>
            </ul>
        </div>
    @endif

    <!-- Start::app-content -->
    <div class="main-content app-content">
        <div class="container-fluid">
            <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
                <div class="my-auto">
                    <h5 class="page-title fs-21 mb-1">كافة المعلمين</h5>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header align-items-center d-flex gap-3">
                            <div class="d-flex gap-2">
                                <a href="{{ route('admin.teachers.create') }}" class="btn btn-primary btn-sm">إضافة معلم جديد</a>
                                <a href="{{ route('admin.export.teachers', request()->all()) }}" class="btn btn-success btn-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                        <polyline points="7 10 12 15 17 10"></polyline>
                                        <line x1="12" y1="15" x2="12" y2="3"></line>
                                    </svg>
                                    تصدير Excel
                                </a>
                            </div>
                            <div class="flex-shrink-0">
                                <form action="{{ route('admin.teachers.index') }}" method="GET" class="d-flex align-items-center gap-2">
                                    <input style="width: 300px" type="text" name="query" class="form-control" placeholder="بحث بالاسم أو الإيميل أو الرقم" value="{{ request('query') }}">
                                    <select name="status" class="form-select">
                                        <option value="">كل الحالات</option>
                                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>نشط</option>
                                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>غير نشط</option>
                                        <option value="on_leave" {{ request('status') == 'on_leave' ? 'selected' : '' }}>في إجازة</option>
                                        <option value="resigned" {{ request('status') == 'resigned' ? 'selected' : '' }}>استقال</option>
                                    </select>
                                    <button type="submit" class="btn btn-secondary">بحث</button>
                                    <a href="{{ route('admin.teachers.index') }}" class="btn btn-danger">مسح</a>
                                </form>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover align-middle table-nowrap mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>اسم المعلم</th>
                                            <th>البريد الإلكتروني</th>
                                            <th>رقم المعلم</th>
                                            <th>التخصص</th>
                                            <th>المواد</th>
                                            <th>الحالة</th>
                                            <th>العمليات</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($teachers as $teacher)
                                            <tr>
                                                <td>{{ $teacher->id }}</td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        @if($teacher->photo)
                                                            <img src="{{ asset('storage/' . $teacher->photo) }}" alt="{{ $teacher->user->name }}" class="avatar avatar-sm rounded-circle me-2">
                                                        @endif
                                                        <div>
                                                            <h6 class="mb-0">{{ $teacher->user->name }}</h6>
                                                            <small class="text-muted">{{ $teacher->user->phone ?? '-' }}</small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>{{ $teacher->user->email }}</td>
                                                <td>{{ $teacher->teacher_code }}</td>
                                                <td>{{ $teacher->specialization ?? '-' }}</td>
                                                <td>
                                                    @if($teacher->subjects->count() > 0)
                                                        <span class="badge bg-info">{{ $teacher->subjects->count() }} مادة</span>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($teacher->status == 'active')
                                                        <span class="badge bg-success">نشط</span>
                                                    @elseif($teacher->status == 'inactive')
                                                        <span class="badge bg-danger">غير نشط</span>
                                                    @elseif($teacher->status == 'on_leave')
                                                        <span class="badge bg-warning">في إجازة</span>
                                                    @else
                                                        <span class="badge bg-secondary">استقال</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="d-flex gap-2">
                                                        <a href="{{ route('admin.teachers.show', $teacher->id) }}" class="btn btn-sm btn-primary">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-left: 4px; vertical-align: middle;">
                                                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                                <circle cx="12" cy="12" r="3"></circle>
                                                            </svg>
                                                            عرض
                                                        </a>
                                                        <a href="{{ route('admin.teachers.edit', $teacher->id) }}" class="btn btn-sm btn-info">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-left: 4px; vertical-align: middle;">
                                                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                                            </svg>
                                                            تعديل
                                                        </a>
                                                        <button type="button" class="btn btn-sm btn-danger" 
                                                                data-delete-url="{{ route('admin.teachers.destroy', $teacher->id) }}"
                                                                data-delete-message="هل أنت متأكد من رغبتك في حذف المعلم <strong>{{ $teacher->user->name }}</strong>؟">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-left: 4px; vertical-align: middle;">
                                                                <polyline points="3 6 5 6 21 6"></polyline>
                                                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                                            </svg>
                                                            حذف
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="8" class="text-center">لا يوجد معلمون</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-3">
                                {{ $teachers->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    @include('admin.components.delete-modal')
@stop

