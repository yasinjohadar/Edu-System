@extends('admin.layouts.master')

@section('page-title')
    تقرير الدرجات
@stop

@section('content')
    <div class="main-content app-content">
        <div class="container-fluid">

            <div class="admin-page-header">
                <div class="page-title-wrap">
                    <h1>تقرير الدرجات</h1>
                    <p>تحليل ومتابعة درجات الطلاب حسب الصف والمادة</p>
                </div>
                <a href="{{ route('admin.reports.index') }}" class="admin-btn admin-btn-secondary">
                    <i class="ri-arrow-right-line"></i>
                    العودة للتقارير
                </a>
            </div>

            <div class="admin-page-card">
                <div class="card-toolbar">
                    <form id="grades-filter-form" action="{{ route('admin.reports.grades') }}" method="GET" class="admin-filters w-100">
                        <input type="hidden" name="tier" value="{{ request('tier') }}">

                        <select name="class_id" class="form-select" style="width: auto; min-width: 170px;">
                            <option value="">كل الصفوف</option>
                            @foreach ($classes as $class)
                                <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>
                                    {{ $class->grade->name }} - {{ $class->name }}
                                </option>
                            @endforeach
                        </select>

                        <select name="section_id" class="form-select" style="width: auto; min-width: 140px;">
                            <option value="">كل الفصول</option>
                            @foreach ($sections as $section)
                                <option value="{{ $section->id }}" {{ request('section_id') == $section->id ? 'selected' : '' }}>
                                    {{ $section->name }}
                                </option>
                            @endforeach
                        </select>

                        <select name="subject_id" class="form-select" style="width: auto; min-width: 140px;">
                            <option value="">كل المواد</option>
                            @foreach ($subjects as $subject)
                                <option value="{{ $subject->id }}" {{ request('subject_id') == $subject->id ? 'selected' : '' }}>
                                    {{ $subject->name }}
                                </option>
                            @endforeach
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

                <div id="grades-report-wrap" class="admin-table-wrap">
                    <div class="table-loader">
                        <div class="spinner-border text-primary" role="status"></div>
                    </div>
                    <div id="grades-report-content">
                        @include('admin.partials.grades-report-content')
                    </div>
                </div>
            </div>

        </div>
    </div>
@stop

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const gradesTable = AdminTables.initAjaxTable({
            formSelector: '#grades-filter-form',
            containerSelector: '#grades-report-content',
            tableWrapSelector: '#grades-report-wrap',
            url: '{{ route('admin.reports.grades') }}',
        });

        const reportWrap = document.getElementById('grades-report-wrap');
        const filterForm = document.getElementById('grades-filter-form');

        if (reportWrap && filterForm) {
            reportWrap.addEventListener('click', function (e) {
                const stat = e.target.closest('[data-grades-stat-filter]');
                if (!stat) return;

                const tierInput = filterForm.querySelector('[name="tier"]');
                if (!tierInput) return;

                tierInput.value = stat.dataset.gradesStatFilter;
                if (gradesTable && typeof gradesTable.reload === 'function') {
                    gradesTable.reload(1);
                }
            });
        }

        const resetBtn = filterForm?.querySelector('[data-admin-filter-reset]');
        if (resetBtn) {
            resetBtn.addEventListener('click', function () {
                const tierInput = filterForm.querySelector('[name="tier"]');
                if (tierInput) tierInput.value = '';
            });
        }
    });
</script>
@endpush
