@extends('admin.layouts.master')

@section('page-title')
    قائمة المواد الدراسية
@stop

@section('content')
    @if (\Session::has('success'))
        <div class="alert alert-success">
            <ul>
                <li>{!! \Session::get('success') !!}</li>
            </ul>
        </div>
    @endif

    <!-- Start::app-content -->
    <div class="main-content app-content">
        <div class="container-fluid">
            <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
                <div class="my-auto">
                    <h5 class="page-title fs-21 mb-1">كافة المواد الدراسية</h5>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header align-items-center d-flex gap-3">
                            <a href="{{ route('admin.subjects.create') }}" class="btn btn-primary btn-sm">إضافة مادة جديدة</a>
                            <div class="flex-shrink-0">
                                <form action="{{ route('admin.subjects.index') }}" method="GET" class="d-flex align-items-center gap-2">
                                    <input style="width: 300px" type="text" name="query" class="form-control" placeholder="بحث بالاسم أو الرمز" value="{{ request('query') }}">
                                    <select name="type" class="form-select">
                                        <option value="">كل الأنواع</option>
                                        <option value="required" {{ request('type') == 'required' ? 'selected' : '' }}>إجباري</option>
                                        <option value="optional" {{ request('type') == 'optional' ? 'selected' : '' }}>اختياري</option>
                                    </select>
                                    <button type="submit" class="btn btn-secondary">بحث</button>
                                    <a href="{{ route('admin.subjects.index') }}" class="btn btn-danger">مسح</a>
                                </form>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover align-middle table-nowrap mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>اسم المادة</th>
                                            <th>الرمز</th>
                                            <th>النوع</th>
                                            <th>الحصص الأسبوعية</th>
                                            <th>الدرجة الكاملة</th>
                                            <th>الحالة</th>
                                            <th>العمليات</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($subjects as $subject)
                                            <tr>
                                                <td>{{ $subject->id }}</td>
                                                <td>{{ $subject->name }}</td>
                                                <td>{{ $subject->code ?? '-' }}</td>
                                                <td>
                                                    @if($subject->type == 'required')
                                                        <span class="badge bg-primary">إجباري</span>
                                                    @else
                                                        <span class="badge bg-info">اختياري</span>
                                                    @endif
                                                </td>
                                                <td>{{ $subject->weekly_hours }}</td>
                                                <td>{{ $subject->full_marks }}</td>
                                                <td>
                                                    @if($subject->is_active)
                                                        <span class="badge bg-success">نشط</span>
                                                    @else
                                                        <span class="badge bg-danger">غير نشط</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="d-flex gap-2">
                                                        <a href="{{ route('admin.subjects.edit', $subject->id) }}" class="btn btn-sm btn-info">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-left: 4px; vertical-align: middle;">
                                                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                                            </svg>
                                                            تعديل
                                                        </a>
                                                        <button type="button" class="btn btn-sm btn-danger" 
                                                                data-delete-url="{{ route('admin.subjects.destroy', $subject->id) }}"
                                                                data-delete-message="هل أنت متأكد من رغبتك في حذف المادة <strong>{{ $subject->name }}</strong>؟">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-left: 4px; vertical-align: middle;">
                                                                <polyline points="3 6 5 6 21 6"></polyline>
                                                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                                            </svg>
                                                            حذف
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="8" class="text-center">لا توجد مواد</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-3">
                                {{ $subjects->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    @include('admin.components.delete-modal')
@stop

