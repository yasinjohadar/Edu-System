@extends('admin.layouts.master')

@section('page-title')
    قائمة الواجبات
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
                    <h1>قائمة الواجبات</h1>
                    <p>إدارة ومتابعة واجبات الطلاب</p>
                </div>
                @can('assignment-create')
                    <a href="{{ route('admin.assignments.create') }}" class="admin-btn admin-btn-primary">
                        <i class="ri-add-line"></i>
                        إضافة واجب جديد
                    </a>
                @endcan
            </div>

            <div class="admin-page-card">
                <div class="card-toolbar">
                    <form id="assignments-filter-form" action="{{ route('admin.assignments.index') }}" method="GET" class="admin-filters w-100">
                        <div class="search-input-wrap">
                            <i class="ri-search-line"></i>
                            <input type="text" name="query" class="form-control"
                                   placeholder="بحث بالعنوان أو الوصف..."
                                   value="{{ request('query') }}" autocomplete="off">
                        </div>

                        <select name="subject_id" class="form-select" style="width: auto; min-width: 150px;">
                            <option value="">كل المواد</option>
                            @foreach ($subjects as $subject)
                                <option value="{{ $subject->id }}" {{ request('subject_id') == $subject->id ? 'selected' : '' }}>{{ $subject->name }}</option>
                            @endforeach
                        </select>

                        <select name="teacher_id" class="form-select" style="width: auto; min-width: 150px;">
                            <option value="">كل المعلمين</option>
                            @foreach ($teachers as $teacher)
                                <option value="{{ $teacher->id }}" {{ request('teacher_id') == $teacher->id ? 'selected' : '' }}>{{ $teacher->user->name }}</option>
                            @endforeach
                        </select>

                        <select name="section_id" class="form-select" style="width: auto; min-width: 140px;">
                            <option value="">كل الفصول</option>
                            @foreach ($sections as $section)
                                <option value="{{ $section->id }}" {{ request('section_id') == $section->id ? 'selected' : '' }}>{{ $section->name }}</option>
                            @endforeach
                        </select>

                        <select name="status" class="form-select" style="width: auto; min-width: 130px;">
                            <option value="">كل الحالات</option>
                            <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>مسودة</option>
                            <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>منشور</option>
                            <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>مغلق</option>
                        </select>

                        <select name="is_active" class="form-select" style="width: auto; min-width: 130px;">
                            <option value="">النشاط</option>
                            <option value="1" {{ request('is_active') == '1' ? 'selected' : '' }}>نشط</option>
                            <option value="0" {{ request('is_active') == '0' ? 'selected' : '' }}>غير نشط</option>
                        </select>

                        <button type="button" class="admin-btn admin-btn-danger" data-admin-filter-reset>
                            <i class="ri-refresh-line"></i>
                            مسح
                        </button>
                    </form>
                </div>

                <div class="admin-table-wrap" id="assignments-table-wrap">
                    <div class="table-loader">
                        <div class="spinner-border text-primary" role="status"></div>
                    </div>
                    <div class="table-responsive">
                        <table class="admin-data-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>رقم الواجب</th>
                                    <th>العنوان</th>
                                    <th>المادة</th>
                                    <th>المعلم</th>
                                    <th>الفصل</th>
                                    <th>الدرجة الكلية</th>
                                    <th>تاريخ الاستحقاق</th>
                                    <th>الحالة</th>
                                    <th>التسليمات</th>
                                    <th>العمليات</th>
                                </tr>
                            </thead>
                            <tbody id="assignments-table-body">
                                @include('admin.partials.assignments-table-body')
                            </tbody>
                        </table>
                    </div>
                </div>

                <div id="assignments-ajax-extra">
                    @include('admin.partials.assignments-table-footer')
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
            formSelector: '#assignments-filter-form',
            bodySelector: '#assignments-table-body',
            extraSelector: '#assignments-ajax-extra',
            tableWrapSelector: '#assignments-table-wrap',
            metaSelector: '#assignments-table-meta',
            url: '{{ route('admin.assignments.index') }}',
        });
    });
</script>
@endpush
