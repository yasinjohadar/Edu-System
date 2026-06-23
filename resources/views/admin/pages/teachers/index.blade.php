@extends('admin.layouts.master')

@section('page-title')
    قائمة المعلمين
@stop

@section('content')
    @if (\Session::has('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {!! \Session::get('success') !!}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (\Session::has('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {!! \Session::get('error') !!}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="main-content app-content">
        <div class="container-fluid">

            <div class="admin-page-header">
                <div class="page-title-wrap">
                    <h1>كافة المعلمين</h1>
                    <p>إدارة بيانات المعلمين والتخصصات والمواد</p>
                </div>
                <div class="admin-page-header-actions">
                    <a href="{{ route('admin.export.teachers', request()->all()) }}" class="admin-btn admin-btn-secondary">
                        <i class="ri-file-excel-2-line"></i>
                        تصدير Excel
                    </a>
                    <a href="{{ route('admin.teachers.create') }}" class="admin-btn admin-btn-primary">
                        <i class="ri-add-line"></i>
                        إضافة معلم جديد
                    </a>
                </div>
            </div>

            <div class="admin-page-card">
                <div class="card-toolbar">
                    <form id="teachers-filter-form" action="{{ route('admin.teachers.index') }}" method="GET" class="admin-filters w-100">
                        <div class="search-input-wrap">
                            <i class="ri-search-line"></i>
                            <input type="text" name="query" class="form-control"
                                   placeholder="بحث بالاسم أو الإيميل أو الرقم أو التخصص..."
                                   value="{{ request('query') }}" autocomplete="off">
                        </div>

                        <select name="status" class="form-select" style="width: auto; min-width: 150px;">
                            <option value="">كل الحالات</option>
                            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>نشط</option>
                            <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>غير نشط</option>
                            <option value="on_leave" {{ request('status') === 'on_leave' ? 'selected' : '' }}>في إجازة</option>
                            <option value="resigned" {{ request('status') === 'resigned' ? 'selected' : '' }}>استقال</option>
                        </select>

                        <button type="button" class="admin-btn admin-btn-danger" data-admin-filter-reset>
                            <i class="ri-refresh-line"></i>
                            مسح
                        </button>
                    </form>
                </div>

                <div class="admin-table-wrap" id="teachers-table-wrap">
                    <div class="table-loader">
                        <div class="spinner-border text-primary" role="status"></div>
                    </div>
                    <div class="table-responsive">
                        <table class="admin-data-table">
                            <thead>
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
                            <tbody id="teachers-table-body">
                                @include('admin.partials.teachers-table-body')
                            </tbody>
                        </table>
                    </div>
                </div>

                <div id="teachers-ajax-extra">
                    @include('admin.partials.teachers-table-footer')
                </div>
            </div>

        </div>
    </div>

    @include('admin.components.delete-modal')
@stop

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        AdminTables.initAjaxTable({
            formSelector: '#teachers-filter-form',
            bodySelector: '#teachers-table-body',
            extraSelector: '#teachers-ajax-extra',
            tableWrapSelector: '#teachers-table-wrap',
            metaSelector: '#teachers-table-meta',
            url: '{{ route('admin.teachers.index') }}',
        });
    });
</script>
@endpush
