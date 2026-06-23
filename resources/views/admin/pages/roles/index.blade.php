@extends('admin.layouts.master')

@section('page-title')
    الأدوار
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
                    <h1>جدول الأدوار</h1>
                    <p>إدارة الأدوار والصلاحيات في النظام</p>
                </div>
                <a href="{{ route('roles.create') }}" class="admin-btn admin-btn-primary">
                    <i class="ri-add-line"></i>
                    إضافة دور جديد
                </a>
            </div>

            <div class="admin-page-card">
                <div class="card-toolbar">
                    <form id="roles-filter-form" action="{{ route('roles.index') }}" method="GET" class="admin-filters w-100">
                        <div class="search-input-wrap">
                            <i class="ri-search-line"></i>
                            <input type="text" name="query" class="form-control"
                                   placeholder="بحث باسم الدور..."
                                   value="{{ request('query') }}" autocomplete="off">
                        </div>

                        <button type="button" class="admin-btn admin-btn-danger" data-admin-filter-reset>
                            <i class="ri-refresh-line"></i>
                            مسح
                        </button>
                    </form>
                </div>

                <div class="admin-table-wrap" id="roles-table-wrap">
                    <div class="table-loader">
                        <div class="spinner-border text-primary" role="status"></div>
                    </div>
                    <div class="table-responsive">
                        <table class="admin-data-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>اسم الدور</th>
                                    <th>الصلاحيات</th>
                                    <th>العمليات</th>
                                </tr>
                            </thead>
                            <tbody id="roles-table-body">
                                @include('admin.partials.roles-table-body')
                            </tbody>
                        </table>
                    </div>
                </div>

                <div id="roles-ajax-extra">
                    @include('admin.partials.roles-table-footer')
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
            formSelector: '#roles-filter-form',
            bodySelector: '#roles-table-body',
            extraSelector: '#roles-ajax-extra',
            tableWrapSelector: '#roles-table-wrap',
            metaSelector: '#roles-table-meta',
            url: '{{ route('roles.index') }}',
        });
    });
</script>
@endpush
