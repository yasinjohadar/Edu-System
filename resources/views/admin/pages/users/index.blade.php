@extends('admin.layouts.master')

@section('page-title')
    قائمة المستخدمين
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
                    <h1>كافة المستخدمين</h1>
                    <p>إدارة حسابات النظام والصلاحيات والحالة</p>
                </div>
                @can('user-create')
                    <a href="{{ route('users.create') }}" class="admin-btn admin-btn-primary">
                        <i class="ri-user-add-line"></i>
                        إنشاء مستخدم جديد
                    </a>
                @endcan
            </div>

            <div class="admin-page-card">
                <div class="card-toolbar">
                    <form id="users-filter-form" action="{{ route('users.index') }}" method="GET" class="admin-filters w-100">
                        <div class="search-input-wrap">
                            <i class="ri-search-line"></i>
                            <input type="text" name="query" class="form-control"
                                   placeholder="بحث بالاسم أو الإيميل أو الهاتف..."
                                   value="{{ request('query') }}" autocomplete="off">
                        </div>

                        <select name="is_active" class="form-select" style="width: auto; min-width: 150px;">
                            <option value="">كل الحالات النشطة</option>
                            <option value="1" {{ request('is_active') === '1' ? 'selected' : '' }}>نشط</option>
                            <option value="0" {{ request('is_active') === '0' ? 'selected' : '' }}>غير نشط</option>
                        </select>

                        <select name="status" class="form-select" style="width: auto; min-width: 140px;">
                            <option value="">كل الحالات</option>
                            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>مفعل</option>
                            <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>موقوف</option>
                            <option value="banned" {{ request('status') === 'banned' ? 'selected' : '' }}>محظور</option>
                        </select>

                        <button type="button" class="admin-btn admin-btn-danger" data-admin-filter-reset>
                            <i class="ri-refresh-line"></i>
                            مسح
                        </button>
                    </form>
                </div>

                <div class="admin-table-wrap" id="users-table-wrap">
                    <div class="table-loader">
                        <div class="spinner-border text-primary" role="status"></div>
                    </div>
                    <div class="table-responsive">
                        <table class="admin-data-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>اسم المستخدم</th>
                                    <th>البريد</th>
                                    <th>الهاتف</th>
                                    <th>آخر دخول</th>
                                    <th>الأدوار</th>
                                    <th>الحالة</th>
                                    <th>الحالة النشطة</th>
                                    <th>العمليات</th>
                                </tr>
                            </thead>
                            <tbody id="users-table-body">
                                @include('admin.partials.users-table-body')
                            </tbody>
                        </table>
                    </div>
                </div>

                <div id="users-ajax-extra">
                    @include('admin.partials.users-table-footer')
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
            formSelector: '#users-filter-form',
            bodySelector: '#users-table-body',
            extraSelector: '#users-ajax-extra',
            tableWrapSelector: '#users-table-wrap',
            metaSelector: '#users-table-meta',
            url: '{{ route('users.index') }}',
            toggleOptions: {
                urlTemplate: '/users/:id/toggle-status',
            },
        });
    });
</script>
@endpush
