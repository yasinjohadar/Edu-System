@extends('admin.layouts.master')

@section('page-title')
    الحسابات المالية
@stop

@section('content')
    <div class="main-content app-content">
        <div class="container-fluid">

            <div class="admin-page-header">
                <div class="page-title-wrap">
                    <h1>الحسابات المالية</h1>
                    <p>متابعة أرصدة الطلاب والمستحقات والمدفوعات</p>
                </div>
            </div>

            <div class="admin-page-card">
                <div class="card-toolbar">
                    <form id="financial-accounts-filter-form" action="{{ route('admin.financial-accounts.index') }}" method="GET" class="admin-filters w-100">
                        <div class="search-input-wrap">
                            <i class="ri-search-line"></i>
                            <input type="text" name="query" class="form-control"
                                   placeholder="بحث برقم الحساب أو الطالب..."
                                   value="{{ request('query') }}" autocomplete="off">
                        </div>

                        <select name="student_id" class="form-select" style="width: auto; min-width: 180px;">
                            <option value="">كل الطلاب</option>
                            @foreach ($students as $student)
                                <option value="{{ $student->id }}" {{ request('student_id') == $student->id ? 'selected' : '' }}>
                                    {{ $student->user->name }}
                                </option>
                            @endforeach
                        </select>

                        <button type="button" class="admin-btn admin-btn-danger" data-admin-filter-reset>
                            <i class="ri-refresh-line"></i>
                            مسح
                        </button>
                    </form>
                </div>

                <div class="admin-table-wrap" id="financial-accounts-table-wrap">
                    <div class="table-loader">
                        <div class="spinner-border text-primary" role="status"></div>
                    </div>
                    <div class="table-responsive">
                        <table class="admin-data-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>رقم الحساب</th>
                                    <th>الطالب</th>
                                    <th>إجمالي الفواتير</th>
                                    <th>إجمالي المدفوعات</th>
                                    <th>المستحقات</th>
                                    <th>الرصيد</th>
                                    <th>آخر معاملة</th>
                                    <th>العمليات</th>
                                </tr>
                            </thead>
                            <tbody id="financial-accounts-table-body">
                                @include('admin.partials.financial-accounts-table-body')
                            </tbody>
                        </table>
                    </div>
                </div>

                <div id="financial-accounts-ajax-extra">
                    @include('admin.partials.financial-accounts-table-footer')
                </div>
            </div>

        </div>
    </div>
@stop

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        AdminTables.initAjaxTable({
            formSelector: '#financial-accounts-filter-form',
            bodySelector: '#financial-accounts-table-body',
            extraSelector: '#financial-accounts-ajax-extra',
            tableWrapSelector: '#financial-accounts-table-wrap',
            metaSelector: '#financial-accounts-table-meta',
            url: '{{ route('admin.financial-accounts.index') }}',
        });
    });
</script>
@endpush
