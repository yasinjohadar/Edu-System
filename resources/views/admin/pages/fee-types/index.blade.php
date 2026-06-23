@extends('admin.layouts.master')

@section('page-title')
    أنواع الرسوم
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
                    <h1>أنواع الرسوم</h1>
                    <p>إدارة أنواع الرسوم الدراسية والفئات والمبالغ الافتراضية</p>
                </div>
                @can('fee-type-create')
                    <a href="{{ route('admin.fee-types.create') }}" class="admin-btn admin-btn-primary">
                        <i class="ri-add-line"></i>
                        إضافة نوع رسوم
                    </a>
                @endcan
            </div>

            <div class="admin-page-card">
                <div class="card-toolbar">
                    <form id="fee-types-filter-form" action="{{ route('admin.fee-types.index') }}" method="GET" class="admin-filters w-100">
                        <div class="search-input-wrap">
                            <i class="ri-search-line"></i>
                            <input type="text" name="query" class="form-control"
                                   placeholder="بحث بالاسم أو الرمز..."
                                   value="{{ request('query') }}" autocomplete="off">
                        </div>

                        <select name="category" class="form-select" style="width: auto; min-width: 150px;">
                            <option value="">كل الفئات</option>
                            @foreach ($categories as $key => $label)
                                <option value="{{ $key }}" {{ request('category') === $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>

                        <select name="is_recurring" class="form-select" style="width: auto; min-width: 130px;">
                            <option value="">كل الأنواع</option>
                            <option value="1" {{ request('is_recurring') === '1' ? 'selected' : '' }}>متكرر</option>
                            <option value="0" {{ request('is_recurring') === '0' ? 'selected' : '' }}>غير متكرر</option>
                        </select>

                        <select name="is_active" class="form-select" style="width: auto; min-width: 130px;">
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

                <div class="admin-table-wrap" id="fee-types-table-wrap">
                    <div class="table-loader">
                        <div class="spinner-border text-primary" role="status"></div>
                    </div>
                    <div class="table-responsive">
                        <table class="admin-data-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>الرمز</th>
                                    <th>الاسم</th>
                                    <th>الفئة</th>
                                    <th>المبلغ الافتراضي</th>
                                    <th>متكرر</th>
                                    <th>الحالة</th>
                                    <th>العمليات</th>
                                </tr>
                            </thead>
                            <tbody id="fee-types-table-body">
                                @include('admin.partials.fee-types-table-body')
                            </tbody>
                        </table>
                    </div>
                </div>

                <div id="fee-types-ajax-extra">
                    @include('admin.partials.fee-types-table-footer')
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
            formSelector: '#fee-types-filter-form',
            bodySelector: '#fee-types-table-body',
            extraSelector: '#fee-types-ajax-extra',
            tableWrapSelector: '#fee-types-table-wrap',
            metaSelector: '#fee-types-table-meta',
            url: '{{ route('admin.fee-types.index') }}',
        });
    });
</script>
@endpush
