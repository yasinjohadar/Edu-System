@extends('admin.layouts.master')

@section('page-title')
    قائمة الدرجات والتقييم
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
                    <h1>الدرجات والتقييم</h1>
                    <p>عرض وإدارة درجات الطلاب وتقييماتهم</p>
                </div>
                @can('grade-create')
                    <a href="{{ route('admin.grade-records.create') }}" class="admin-btn admin-btn-primary">
                        <i class="ri-add-line"></i>
                        إدخال درجات جديدة
                    </a>
                @endcan
            </div>

            <div class="admin-page-card">
                <div class="card-toolbar">
                    <form id="grade-records-filter-form" action="{{ route('admin.grade-records.index') }}" method="GET" class="admin-filters w-100">
                        <div class="search-input-wrap">
                            <i class="ri-search-line"></i>
                            <input type="text" name="query" class="form-control"
                                   placeholder="بحث باسم الطالب أو رقم القيد..."
                                   value="{{ request('query') }}" autocomplete="off">
                        </div>

                        <select name="section_id" class="form-select" style="width: auto; min-width: 160px;">
                            <option value="">كل الفصول</option>
                            @foreach ($sections as $section)
                                <option value="{{ $section->id }}" {{ request('section_id') == $section->id ? 'selected' : '' }}>
                                    {{ $section->class->name }} - {{ $section->name }}
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

                        <select name="exam_type" class="form-select" style="width: auto; min-width: 140px;">
                            <option value="">كل الأنواع</option>
                            @foreach ($examTypes as $key => $name)
                                <option value="{{ $key }}" {{ request('exam_type') == $key ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>

                        <select name="academic_year" class="form-select" style="width: auto; min-width: 130px;">
                            <option value="">كل السنوات</option>
                            @foreach ($academicYears as $year)
                                <option value="{{ $year }}" {{ request('academic_year') == $year ? 'selected' : '' }}>{{ $year }}</option>
                            @endforeach
                        </select>

                        <button type="button" class="admin-btn admin-btn-danger" data-admin-filter-reset>
                            <i class="ri-refresh-line"></i>
                            مسح
                        </button>
                    </form>
                </div>

                <div class="admin-table-wrap" id="grade-records-table-wrap">
                    <div class="table-loader">
                        <div class="spinner-border text-primary" role="status"></div>
                    </div>
                    <div class="table-responsive">
                        <table class="admin-data-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>اسم الطالب</th>
                                    <th>المادة</th>
                                    <th>نوع التقييم</th>
                                    <th>اسم التقييم</th>
                                    <th>الدرجة</th>
                                    <th>النسبة</th>
                                    <th>الدرجة الحرفية</th>
                                    <th>التاريخ</th>
                                    <th>السنة الدراسية</th>
                                    <th>الحالة</th>
                                    <th>العمليات</th>
                                </tr>
                            </thead>
                            <tbody id="grade-records-table-body">
                                @include('admin.partials.grade-records-table-body')
                            </tbody>
                        </table>
                    </div>
                </div>

                <div id="grade-records-ajax-extra">
                    @include('admin.partials.grade-records-table-footer')
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
            formSelector: '#grade-records-filter-form',
            bodySelector: '#grade-records-table-body',
            extraSelector: '#grade-records-ajax-extra',
            tableWrapSelector: '#grade-records-table-wrap',
            metaSelector: '#grade-records-table-meta',
            url: '{{ route('admin.grade-records.index') }}',
        });
    });
</script>
@endpush
