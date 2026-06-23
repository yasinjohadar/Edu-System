@extends('admin.layouts.master')

@section('page-title')
    قائمة الجدول الدراسي
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
                    <h1>الجدول الدراسي</h1>
                    <p>إدارة الحصص والمواد والمعلمين حسب الفصول</p>
                </div>
                @can('schedule-create')
                <a href="{{ route('admin.schedules.create') }}" class="admin-btn admin-btn-primary">
                    <i class="ri-add-line"></i>
                    إضافة جدول جديد
                </a>
                @endcan
            </div>

            <div class="admin-page-card">
                <div class="card-toolbar">
                    <form id="schedules-filter-form" action="{{ route('admin.schedules.index') }}" method="GET" class="admin-filters w-100">
                        <select name="section_id" class="form-select" data-admin-choices style="width: auto; min-width: 200px;">
                            <option value="">كل الفصول</option>
                            @foreach ($sections as $section)
                                <option value="{{ $section->id }}" {{ request('section_id') == $section->id ? 'selected' : '' }}>
                                    {{ $section->class->name }} - {{ $section->name }}
                                </option>
                            @endforeach
                        </select>

                        <select name="teacher_id" class="form-select" data-admin-choices style="width: auto; min-width: 200px;">
                            <option value="">كل المعلمين</option>
                            @foreach ($teachers as $teacher)
                                <option value="{{ $teacher->id }}" {{ request('teacher_id') == $teacher->id ? 'selected' : '' }}>
                                    {{ $teacher->user->name }}
                                </option>
                            @endforeach
                        </select>

                        <select name="day_of_week" class="form-select" style="width: auto; min-width: 150px;">
                            <option value="">كل الأيام</option>
                            @foreach ($days as $key => $day)
                                <option value="{{ $key }}" {{ request('day_of_week') === $key ? 'selected' : '' }}>
                                    {{ $day }}
                                </option>
                            @endforeach
                        </select>

                        <button type="button" class="admin-btn admin-btn-danger" data-admin-filter-reset>
                            <i class="ri-refresh-line"></i>
                            مسح
                        </button>
                    </form>
                </div>

                <div class="admin-table-wrap" id="schedules-table-wrap">
                    <div class="table-loader">
                        <div class="spinner-border text-primary" role="status"></div>
                    </div>
                    <div class="table-responsive">
                        <table class="admin-data-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>الفصل</th>
                                    <th>المادة</th>
                                    <th>المعلم</th>
                                    <th>اليوم</th>
                                    <th>وقت البداية</th>
                                    <th>وقت النهاية</th>
                                    <th>القاعة</th>
                                    <th>الحالة</th>
                                    <th>العمليات</th>
                                </tr>
                            </thead>
                            <tbody id="schedules-table-body">
                                @include('admin.partials.schedules-table-body')
                            </tbody>
                        </table>
                    </div>
                </div>

                <div id="schedules-ajax-extra">
                    @include('admin.partials.schedules-table-footer')
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
            formSelector: '#schedules-filter-form',
            bodySelector: '#schedules-table-body',
            extraSelector: '#schedules-ajax-extra',
            tableWrapSelector: '#schedules-table-wrap',
            metaSelector: '#schedules-table-meta',
            url: '{{ route('admin.schedules.index') }}',
        });
    });
</script>
@endpush
