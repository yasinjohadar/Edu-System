@extends('admin.layouts.master')

@section('page-title')
    قائمة المواد الدراسية
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
                    <h1>كافة المواد الدراسية</h1>
                    <p>إدارة المواد والدرجات والحصص الأسبوعية</p>
                </div>
                <a href="{{ route('admin.subjects.create') }}" class="admin-btn admin-btn-primary">
                    <i class="ri-add-line"></i>
                    إضافة مادة جديدة
                </a>
            </div>

            <div class="admin-page-card">
                <div class="card-toolbar">
                    <form id="subjects-filter-form" action="{{ route('admin.subjects.index') }}" method="GET" class="admin-filters w-100">
                        <div class="search-input-wrap">
                            <i class="ri-search-line"></i>
                            <input type="text" name="query" class="form-control"
                                   placeholder="بحث بالاسم أو الرمز..."
                                   value="{{ request('query') }}" autocomplete="off">
                        </div>

                        <select name="type" class="form-select" style="width: auto; min-width: 140px;">
                            <option value="">كل الأنواع</option>
                            <option value="required" {{ request('type') === 'required' ? 'selected' : '' }}>إجباري</option>
                            <option value="optional" {{ request('type') === 'optional' ? 'selected' : '' }}>اختياري</option>
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

                <div class="admin-table-wrap" id="subjects-table-wrap">
                    <div class="table-loader">
                        <div class="spinner-border text-primary" role="status"></div>
                    </div>
                    <div class="table-responsive">
                        <table class="admin-data-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>اسم المادة</th>
                                    <th>الرمز</th>
                                    <th>النوع</th>
                                    <th>الحصص الأسبوعية</th>
                                    <th>الدرجة الكاملة</th>
                                    <th>الحالة</th>
                                    <th>العمليات</th>
                                </tr>
                            </thead>
                            <tbody id="subjects-table-body">
                                @include('admin.partials.subjects-table-body')
                            </tbody>
                        </table>
                    </div>
                </div>

                <div id="subjects-ajax-extra">
                    @include('admin.partials.subjects-table-footer')
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
            formSelector: '#subjects-filter-form',
            bodySelector: '#subjects-table-body',
            extraSelector: '#subjects-ajax-extra',
            tableWrapSelector: '#subjects-table-wrap',
            metaSelector: '#subjects-table-meta',
            url: '{{ route('admin.subjects.index') }}',
        });
    });
</script>
@endpush
