@extends('admin.layouts.master')

@section('page-title')
    تقرير المكتبة
@stop

@section('content')
    <div class="main-content app-content">
        <div class="container-fluid">

            <div class="admin-page-header">
                <div class="page-title-wrap">
                    <h1>تقرير المكتبة</h1>
                    <p>متابعة الاستعارات والغرامات في المكتبة</p>
                </div>
                <a href="{{ route('admin.reports.index') }}" class="admin-btn admin-btn-secondary">
                    <i class="ri-arrow-right-line"></i>
                    العودة للتقارير
                </a>
            </div>

            <div class="admin-page-card">
                <div class="card-toolbar">
                    <form id="library-filter-form" action="{{ route('admin.reports.library') }}" method="GET" class="admin-filters w-100">
                        <select name="status" class="form-select" style="width: auto; min-width: 150px;">
                            <option value="">كل الحالات</option>
                            <option value="borrowed" {{ request('status') == 'borrowed' ? 'selected' : '' }}>مستعار</option>
                            <option value="returned" {{ request('status') == 'returned' ? 'selected' : '' }}>مُعاد</option>
                            <option value="overdue" {{ request('status') == 'overdue' ? 'selected' : '' }}>متأخر</option>
                        </select>

                        <div class="admin-filter-dates">
                            <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}" title="من تاريخ">
                            <span class="admin-filter-dates-sep">—</span>
                            <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}" title="إلى تاريخ">
                        </div>

                        <button type="submit" class="admin-btn admin-btn-primary">
                            <i class="ri-search-line"></i>
                            بحث
                        </button>
                        <button type="button" class="admin-btn admin-btn-danger" data-admin-filter-reset>
                            <i class="ri-refresh-line"></i>
                            مسح
                        </button>
                    </form>
                </div>

                <div id="library-report-wrap" class="admin-table-wrap">
                    <div class="table-loader">
                        <div class="spinner-border text-primary" role="status"></div>
                    </div>
                    <div id="library-report-content">
                        @include('admin.partials.library-report-content')
                    </div>
                </div>
            </div>

        </div>
    </div>
@stop

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const libraryTable = AdminTables.initAjaxTable({
            formSelector: '#library-filter-form',
            containerSelector: '#library-report-content',
            tableWrapSelector: '#library-report-wrap',
            url: '{{ route('admin.reports.library') }}',
        });

        const reportWrap = document.getElementById('library-report-wrap');
        const filterForm = document.getElementById('library-filter-form');

        if (reportWrap && filterForm) {
            reportWrap.addEventListener('click', function (e) {
                const stat = e.target.closest('[data-library-stat-filter]');
                if (!stat) return;

                const status = stat.dataset.libraryStatFilter;
                const select = filterForm.querySelector('[name="status"]');
                if (!select) return;

                select.value = status;
                if (select._adminChoices) {
                    select._adminChoices.setChoiceByValue(status);
                }

                if (libraryTable && typeof libraryTable.reload === 'function') {
                    libraryTable.reload(1);
                }
            });
        }
    });
</script>
@endpush
