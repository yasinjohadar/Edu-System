@extends('admin.layouts.master')

@section('page-title')
    المدفوعات
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
                    <h1>المدفوعات</h1>
                    <p>تسجيل ومتابعة مدفوعات الطلاب</p>
                </div>
                @can('payment-create')
                    <a href="{{ route('admin.payments.create') }}" class="admin-btn admin-btn-primary">
                        <i class="ri-add-line"></i>
                        تسجيل دفعة
                    </a>
                @endcan
            </div>

            <div class="admin-page-card">
                <div class="card-toolbar">
                    <form id="payments-filter-form" action="{{ route('admin.payments.index') }}" method="GET" class="admin-filters w-100">
                        <div class="search-input-wrap">
                            <i class="ri-search-line"></i>
                            <input type="text" name="query" class="form-control"
                                   placeholder="بحث برقم الدفعة أو الطالب..."
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

                        <select name="status" class="form-select" style="width: auto; min-width: 130px;">
                            <option value="">كل الحالات</option>
                            @foreach ($paymentStatuses as $key => $label)
                                <option value="{{ $key }}" {{ request('status') == $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>

                        <select name="payment_method" class="form-select" style="width: auto; min-width: 140px;">
                            <option value="">كل الطرق</option>
                            @foreach ($paymentMethods as $key => $label)
                                <option value="{{ $key }}" {{ request('payment_method') == $key ? 'selected' : '' }}>{{ $label }}</option>
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

                <div class="admin-table-wrap" id="payments-table-wrap">
                    <div class="table-loader">
                        <div class="spinner-border text-primary" role="status"></div>
                    </div>
                    <div class="table-responsive">
                        <table class="admin-data-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>رقم الدفعة</th>
                                    <th>الطالب</th>
                                    <th>الفاتورة</th>
                                    <th>التاريخ</th>
                                    <th>المبلغ</th>
                                    <th>طريقة الدفع</th>
                                    <th>الحالة</th>
                                    <th>العمليات</th>
                                </tr>
                            </thead>
                            <tbody id="payments-table-body">
                                @include('admin.partials.payments-table-body')
                            </tbody>
                        </table>
                    </div>
                </div>

                <div id="payments-ajax-extra">
                    @include('admin.partials.payments-table-footer')
                </div>
            </div>

        </div>
    </div>
@stop

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        AdminTables.initAjaxTable({
            formSelector: '#payments-filter-form',
            bodySelector: '#payments-table-body',
            extraSelector: '#payments-ajax-extra',
            tableWrapSelector: '#payments-table-wrap',
            metaSelector: '#payments-table-meta',
            url: '{{ route('admin.payments.index') }}',
        });
    });
</script>
@endpush
