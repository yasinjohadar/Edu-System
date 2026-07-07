@extends('admin.layouts.master')

@section('page-title')
    الفواتير
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
                    <h1>الفواتير</h1>
                    <p>إدارة فواتير الطلاب والمستحقات المالية</p>
                </div>
                @can('invoice-create')
                    <a href="{{ route('admin.invoices.create') }}" class="admin-btn admin-btn-primary">
                        <i class="ri-add-line"></i>
                        إنشاء فاتورة
                    </a>
                @endcan
            </div>

            <div class="admin-page-card">
                <div class="card-toolbar">
                    <form id="invoices-filter-form" action="{{ route('admin.invoices.index') }}" method="GET" class="admin-filters w-100">
                        <div class="search-input-wrap">
                            <i class="ri-search-line"></i>
                            <input type="text" name="query" class="form-control"
                                   placeholder="بحث برقم الفاتورة أو الطالب..."
                                   value="{{ request('query') }}" autocomplete="off">
                        </div>

                        <select name="student_id" class="form-select" style="width: auto; min-width: 180px;">
                            <option value="">كل الطلاب</option>
                            @foreach ($students as $student)
                                <option value="{{ $student->id }}" {{ request('student_id') == $student->id ? 'selected' : '' }}>
                                    {{ $student->user->name }} ({{ $student->student_code }})
                                </option>
                            @endforeach
                        </select>

                        <select name="status" class="form-select" style="width: auto; min-width: 140px;">
                            <option value="">كل الحالات</option>
                            @foreach ($statuses as $key => $name)
                                <option value="{{ $key }}" {{ request('status') == $key ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>

                        <div class="admin-filter-dates">
                            <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}" title="من تاريخ">
                            <span class="admin-filter-dates-sep">—</span>
                            <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}" title="إلى تاريخ">
                        </div>

                        <button type="button" class="admin-btn admin-btn-danger" data-admin-filter-reset>
                            <i class="ri-refresh-line"></i>
                            مسح
                        </button>
                    </form>
                </div>

                <div class="admin-table-wrap" id="invoices-table-wrap">
                    <div class="table-loader">
                        <div class="spinner-border text-primary" role="status"></div>
                    </div>
                    <div class="table-responsive">
                        <table class="admin-data-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>رقم الفاتورة</th>
                                    <th>الطالب</th>
                                    <th>تاريخ الفاتورة</th>
                                    <th>تاريخ الاستحقاق</th>
                                    <th>الإجمالي</th>
                                    <th>المدفوع</th>
                                    <th>المتبقي</th>
                                    <th>الحالة</th>
                                    <th>العمليات</th>
                                </tr>
                            </thead>
                            <tbody id="invoices-table-body">
                                @include('admin.partials.invoices-table-body')
                            </tbody>
                        </table>
                    </div>
                </div>

                <div id="invoices-ajax-extra">
                    @include('admin.partials.invoices-table-footer')
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
            formSelector: '#invoices-filter-form',
            bodySelector: '#invoices-table-body',
            extraSelector: '#invoices-ajax-extra',
            tableWrapSelector: '#invoices-table-wrap',
            metaSelector: '#invoices-table-meta',
            url: '{{ route('admin.invoices.index') }}',
        });
    });
</script>
@endpush
