@extends('admin.layouts.master')

@section('page-title')
    المحاضرات الإلكترونية
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
                    <h5 class="page-title fs-21 mb-1">المحاضرات الإلكترونية</h5>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header align-items-center d-flex gap-3">
                            @can('lecture-create')
                            <a href="{{ route('admin.online-lectures.create') }}" class="btn btn-primary btn-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-left: 4px; vertical-align: middle;">
                                    <line x1="12" y1="5" x2="12" y2="19"></line>
                                    <line x1="5" y1="12" x2="19" y2="12"></line>
                                </svg>
                                إضافة محاضرة جديدة
                            </a>
                            @endcan
                            <div class="flex-shrink-0">
                                <form action="{{ route('admin.online-lectures.index') }}" method="GET" class="d-flex align-items-center gap-2">
                                    <select name="subject_id" class="form-select" style="width: 180px;">
                                        <option value="">كل المواد</option>
                                        @foreach($subjects as $subject)
                                            <option value="{{ $subject->id }}" {{ request('subject_id') == $subject->id ? 'selected' : '' }}>{{ $subject->name }}</option>
                                        @endforeach
                                    </select>
                                    <select name="section_id" class="form-select" style="width: 180px;">
                                        <option value="">كل الفصول</option>
                                        @foreach($sections as $section)
                                            <option value="{{ $section->id }}" {{ request('section_id') == $section->id ? 'selected' : '' }}>{{ $section->name }}</option>
                                        @endforeach
                                    </select>
                                    <select name="type" class="form-select" style="width: 150px;">
                                        <option value="">كل الأنواع</option>
                                        <option value="live" {{ request('type') == 'live' ? 'selected' : '' }}>مباشرة</option>
                                        <option value="recorded" {{ request('type') == 'recorded' ? 'selected' : '' }}>مسجلة</option>
                                        <option value="material" {{ request('type') == 'material' ? 'selected' : '' }}>مواد</option>
                                    </select>
                                    <button type="submit" class="btn btn-secondary">بحث</button>
                                    <a href="{{ route('admin.online-lectures.index') }}" class="btn btn-danger">مسح</a>
                                </form>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover align-middle table-nowrap mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>العنوان</th>
                                            <th>المادة</th>
                                            <th>الفصل</th>
                                            <th>المعلم</th>
                                            <th>النوع</th>
                                            <th>المشاهدات</th>
                                            <th>الحالة</th>
                                            <th>العمليات</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($lectures as $lecture)
                                            <tr>
                                                <td>{{ $lecture->id }}</td>
                                                <td><strong>{{ $lecture->title }}</strong></td>
                                                <td>{{ $lecture->subject->name }}</td>
                                                <td>{{ $lecture->section->name }}</td>
                                                <td>{{ $lecture->teacher->user->name ?? '-' }}</td>
                                                <td>
                                                    @if($lecture->type == 'live')
                                                        <span class="badge bg-danger">مباشرة</span>
                                                    @elseif($lecture->type == 'recorded')
                                                        <span class="badge bg-primary">مسجلة</span>
                                                    @else
                                                        <span class="badge bg-info">مواد</span>
                                                    @endif
                                                </td>
                                                <td>{{ $lecture->views_count }}</td>
                                                <td>
                                                    @if($lecture->is_published)
                                                        <span class="badge bg-success">منشورة</span>
                                                    @else
                                                        <span class="badge bg-warning">مسودة</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="d-flex gap-2">
                                                        <a href="{{ route('admin.online-lectures.show', $lecture->id) }}" class="btn btn-sm btn-info" title="عرض">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                                <circle cx="12" cy="12" r="3"></circle>
                                                            </svg>
                                                        </a>
                                                        @can('lecture-edit')
                                                        <a href="{{ route('admin.online-lectures.edit', $lecture->id) }}" class="btn btn-sm btn-warning" title="تعديل">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                                            </svg>
                                                        </a>
                                                        @endcan
                                                        @can('lecture-delete')
                                                        <button type="button" class="btn btn-sm btn-danger" 
                                                                data-delete-url="{{ route('admin.online-lectures.destroy', $lecture->id) }}"
                                                                data-delete-message="هل أنت متأكد من رغبتك في حذف المحاضرة <strong>{{ $lecture->title }}</strong>؟"
                                                                title="حذف">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                                <polyline points="3 6 5 6 21 6"></polyline>
                                                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                                            </svg>
                                                        </button>
                                                        @endcan
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="9" class="text-center">لا توجد محاضرات</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-3">
                                {{ $lectures->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End::app-content -->

    @include('admin.components.delete-modal')
@stop

