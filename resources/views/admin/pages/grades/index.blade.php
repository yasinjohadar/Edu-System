@extends('admin.layouts.master')

@section('page-title')
    قائمة المراحل التعليمية
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
                    <h1>كافة المراحل التعليمية</h1>
                    <p>إدارة المراحل الدراسية والأعمار والرسوم</p>
                </div>
                <a href="{{ route('admin.grades.create') }}" class="admin-btn admin-btn-primary">
                    <i class="ri-add-line"></i>
                    إضافة مرحلة جديدة
                </a>
            </div>

            <div class="admin-page-card">
                <div class="card-toolbar">
                    <form id="grades-filter-form" action="{{ route('admin.grades.index') }}" method="GET" class="admin-filters w-100">
                        <div class="search-input-wrap">
                            <i class="ri-search-line"></i>
                            <input type="text" name="query" class="form-control"
                                   placeholder="بحث بالاسم..."
                                   value="{{ request('query') }}" autocomplete="off">
                        </div>

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

                <div class="admin-table-wrap" id="grades-table-wrap">
                    <div class="table-loader">
                        <div class="spinner-border text-primary" role="status"></div>
                    </div>
                    <div class="table-responsive">
                        <table class="admin-data-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>اسم المرحلة</th>
                                    <th>الحد الأدنى للعمر</th>
                                    <th>الحد الأقصى للعمر</th>
                                    <th>الرسوم</th>
                                    <th>الترتيب</th>
                                    <th>الحالة</th>
                                    <th>العمليات</th>
                                </tr>
                            </thead>
                            <tbody id="grades-table-body">
                                @include('admin.partials.grades-table-body')
                            </tbody>
                        </table>
                    </div>
                </div>

                <div id="grades-ajax-extra">
                    @include('admin.partials.grades-table-footer')
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
            formSelector: '#grades-filter-form',
            bodySelector: '#grades-table-body',
            extraSelector: '#grades-ajax-extra',
            tableWrapSelector: '#grades-table-wrap',
            metaSelector: '#grades-table-meta',
            url: '{{ route('admin.grades.index') }}',
        });
    });
</script>
@endpush
