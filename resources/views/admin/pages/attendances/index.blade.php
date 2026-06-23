@extends('admin.layouts.master')

@section('page-title')
    قائمة الحضور والغياب
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
                    <h1>سجل الحضور والغياب</h1>
                    <p>متابعة حضور الطلاب حسب الفصل والتاريخ</p>
                </div>
                <a href="{{ route('admin.attendances.create') }}" class="admin-btn admin-btn-primary">
                    <i class="ri-add-line"></i>
                    تسجيل حضور جديد
                </a>
            </div>

            <div class="admin-page-card">
                <div class="card-toolbar">
                    <form id="attendances-filter-form" action="{{ route('admin.attendances.index') }}" method="GET" class="admin-filters w-100">
                        <div class="search-input-wrap">
                            <i class="ri-search-line"></i>
                            <input type="text" name="query" class="form-control"
                                   placeholder="بحث باسم الطالب أو رقم القيد..."
                                   value="{{ request('query') }}" autocomplete="off">
                        </div>

                        <select name="section_id" class="form-select" data-admin-choices style="width: auto; min-width: 200px;">
                            <option value="">كل الفصول</option>
                            @foreach ($sections as $section)
                                <option value="{{ $section->id }}" {{ request('section_id') == $section->id ? 'selected' : '' }}>
                                    {{ $section->class->grade->name }} - {{ $section->class->name }} - {{ $section->name }}
                                </option>
                            @endforeach
                        </select>

                        <input type="date" name="date" class="form-control" style="width: auto; min-width: 160px;"
                               value="{{ request('date', date('Y-m-d')) }}">

                        <select name="status" class="form-select" style="width: auto; min-width: 140px;">
                            <option value="">كل الحالات</option>
                            <option value="present" {{ request('status') === 'present' ? 'selected' : '' }}>حاضر</option>
                            <option value="absent" {{ request('status') === 'absent' ? 'selected' : '' }}>غائب</option>
                            <option value="late" {{ request('status') === 'late' ? 'selected' : '' }}>متأخر</option>
                            <option value="excused" {{ request('status') === 'excused' ? 'selected' : '' }}>معذور</option>
                        </select>

                        <button type="button" class="admin-btn admin-btn-danger" data-admin-filter-reset>
                            <i class="ri-refresh-line"></i>
                            مسح
                        </button>
                    </form>
                </div>

                <div class="admin-table-wrap" id="attendances-table-wrap">
                    <div class="table-loader">
                        <div class="spinner-border text-primary" role="status"></div>
                    </div>
                    <div class="table-responsive">
                        <table class="admin-data-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>اسم الطالب</th>
                                    <th>الفصل</th>
                                    <th>التاريخ</th>
                                    <th>الحالة</th>
                                    <th>وقت الحضور</th>
                                    <th>الملاحظات</th>
                                    <th>سجل بواسطة</th>
                                    <th>العمليات</th>
                                </tr>
                            </thead>
                            <tbody id="attendances-table-body">
                                @include('admin.partials.attendances-table-body')
                            </tbody>
                        </table>
                    </div>
                </div>

                <div id="attendances-ajax-extra">
                    @include('admin.partials.attendances-table-footer')
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
            formSelector: '#attendances-filter-form',
            bodySelector: '#attendances-table-body',
            extraSelector: '#attendances-ajax-extra',
            tableWrapSelector: '#attendances-table-wrap',
            metaSelector: '#attendances-table-meta',
            url: '{{ route('admin.attendances.index') }}',
        });
    });
</script>
@endpush
