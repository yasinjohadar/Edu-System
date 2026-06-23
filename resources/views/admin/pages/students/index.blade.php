@extends('admin.layouts.master')

@section('page-title')
    قائمة الطلاب
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
                    <h1>كافة الطلاب</h1>
                    <p>إدارة بيانات الطلاب والتسجيل والفصول</p>
                </div>
                <div class="admin-page-header-actions">
                    <a href="{{ route('admin.export.students', request()->all()) }}" class="admin-btn admin-btn-secondary">
                        <i class="ri-file-excel-2-line"></i>
                        تصدير Excel
                    </a>
                    <a href="{{ route('admin.students.create') }}" class="admin-btn admin-btn-primary">
                        <i class="ri-add-line"></i>
                        إضافة طالب جديد
                    </a>
                </div>
            </div>

            <div class="admin-page-card">
                <div class="card-toolbar">
                    <form id="students-filter-form" action="{{ route('admin.students.index') }}" method="GET" class="admin-filters w-100">
                        <div class="search-input-wrap">
                            <i class="ri-search-line"></i>
                            <input type="text" name="query" class="form-control"
                                   placeholder="بحث بالاسم أو الإيميل أو رقم القيد..."
                                   value="{{ request('query') }}" autocomplete="off">
                        </div>

                        <select name="status" class="form-select" style="width: auto; min-width: 140px;">
                            <option value="">كل الحالات</option>
                            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>نشط</option>
                            <option value="graduated" {{ request('status') === 'graduated' ? 'selected' : '' }}>متخرج</option>
                            <option value="transferred" {{ request('status') === 'transferred' ? 'selected' : '' }}>منقول</option>
                            <option value="suspended" {{ request('status') === 'suspended' ? 'selected' : '' }}>معلق</option>
                        </select>

                        <select name="class_id" class="form-select" data-admin-choices style="width: auto; min-width: 180px;">
                            <option value="">كل الصفوف</option>
                            @foreach ($classes as $class)
                                <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>
                                    {{ $class->grade->name }} - {{ $class->name }}
                                </option>
                            @endforeach
                        </select>

                        <select name="section_id" class="form-select" data-admin-choices style="width: auto; min-width: 150px;">
                            <option value="">كل الفصول</option>
                            @foreach ($sections as $section)
                                <option value="{{ $section->id }}" {{ request('section_id') == $section->id ? 'selected' : '' }}>
                                    {{ $section->name }}
                                </option>
                            @endforeach
                        </select>

                        <button type="button" class="admin-btn admin-btn-danger" data-admin-filter-reset>
                            <i class="ri-refresh-line"></i>
                            مسح
                        </button>
                    </form>
                </div>

                <div class="admin-table-wrap" id="students-table-wrap">
                    <div class="table-loader">
                        <div class="spinner-border text-primary" role="status"></div>
                    </div>
                    <div class="table-responsive">
                        <table class="admin-data-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>اسم الطالب</th>
                                    <th>رقم القيد</th>
                                    <th>البريد الإلكتروني</th>
                                    <th>الصف / الفصل</th>
                                    <th>أولياء الأمور</th>
                                    <th>الحالة</th>
                                    <th>العمليات</th>
                                </tr>
                            </thead>
                            <tbody id="students-table-body">
                                @include('admin.partials.students-table-body')
                            </tbody>
                        </table>
                    </div>
                </div>

                <div id="students-ajax-extra">
                    @include('admin.partials.students-table-footer')
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
            formSelector: '#students-filter-form',
            bodySelector: '#students-table-body',
            extraSelector: '#students-ajax-extra',
            tableWrapSelector: '#students-table-wrap',
            metaSelector: '#students-table-meta',
            url: '{{ route('admin.students.index') }}',
        });
    });
</script>
@endpush
