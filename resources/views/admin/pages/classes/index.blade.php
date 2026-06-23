@extends('admin.layouts.master')

@section('page-title')
    قائمة الصفوف الدراسية
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
                    <h1>كافة الصفوف الدراسية</h1>
                    <p>إدارة الصفوف وربطها بالمراحل التعليمية</p>
                </div>
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('admin.export.classes', request()->all()) }}" class="admin-btn admin-btn-secondary">
                        <i class="ri-file-excel-2-line"></i>
                        تصدير Excel
                    </a>
                    <a href="{{ route('admin.classes.create') }}" class="admin-btn admin-btn-primary">
                        <i class="ri-add-line"></i>
                        إضافة صف جديد
                    </a>
                </div>
            </div>

            <div class="admin-page-card">
                <div class="card-toolbar">
                    <form id="classes-filter-form" action="{{ route('admin.classes.index') }}" method="GET" class="admin-filters w-100">
                        <div class="search-input-wrap">
                            <i class="ri-search-line"></i>
                            <input type="text" name="query" class="form-control"
                                   placeholder="بحث بالاسم..."
                                   value="{{ request('query') }}" autocomplete="off">
                        </div>

                        <select name="grade_id" class="form-select" data-admin-choices style="width: auto; min-width: 160px;">
                            <option value="">كل المراحل</option>
                            @foreach ($grades as $grade)
                                <option value="{{ $grade->id }}" {{ request('grade_id') == $grade->id ? 'selected' : '' }}>{{ $grade->name }}</option>
                            @endforeach
                        </select>

                        <select name="is_active" class="form-select" style="width: auto; min-width: 150px;">
                            <option value="">كل الحالات</option>
                            <option value="1" {{ request('is_active') === '1' ? 'selected' : '' }}>نشط</option>
                            <option value="0" {{ request('is_active') === '0' ? 'selected' : '' }}>غير نشط</option>
                        </select>

                        <button type="button" class="admin-btn admin-btn-danger" data-admin-filter-reset>
                            <i class="ri-refresh-line"></i>
                            مسح
                        </button>
                    </form>
                </div>

                <div class="admin-table-wrap" id="classes-table-wrap">
                    <div class="table-loader">
                        <div class="spinner-border text-primary" role="status"></div>
                    </div>
                    <div class="table-responsive">
                        <table class="admin-data-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>اسم الصف</th>
                                    <th>المرحلة</th>
                                    <th>الترتيب</th>
                                    <th>الحالة</th>
                                    <th>العمليات</th>
                                </tr>
                            </thead>
                            <tbody id="classes-table-body">
                                @include('admin.partials.classes-table-body')
                            </tbody>
                        </table>
                    </div>
                </div>

                <div id="classes-ajax-extra">
                    @include('admin.partials.classes-table-footer')
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
            formSelector: '#classes-filter-form',
            bodySelector: '#classes-table-body',
            extraSelector: '#classes-ajax-extra',
            tableWrapSelector: '#classes-table-wrap',
            metaSelector: '#classes-table-meta',
            url: '{{ route('admin.classes.index') }}',
        });
    });
</script>
@endpush
