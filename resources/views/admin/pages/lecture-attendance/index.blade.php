@extends('admin.layouts.master')

@section('page-title')
    حضور المحاضرات
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
                    <h5 class="page-title fs-21 mb-1">حضور المحاضرات</h5>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header align-items-center d-flex gap-3">
                            @can('lecture-attendance-create')
                            <a href="{{ route('admin.lecture-attendance.create') }}" class="btn btn-primary btn-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-left: 4px; vertical-align: middle;">
                                    <line x1="12" y1="5" x2="12" y2="19"></line>
                                    <line x1="5" y1="12" x2="19" y2="12"></line>
                                </svg>
                                تسجيل حضور جديد
                            </a>
                            @endcan
                            <div class="flex-shrink-0">
                                <form action="{{ route('admin.lecture-attendance.index') }}" method="GET" class="d-flex align-items-center gap-2">
                                    <select name="lecture_id" class="form-select" style="width: 250px;">
                                        <option value="">كل المحاضرات</option>
                                        @foreach($lectures as $lecture)
                                            <option value="{{ $lecture->id }}" {{ request('lecture_id') == $lecture->id ? 'selected' : '' }}>{{ $lecture->title }}</option>
                                        @endforeach
                                    </select>
                                    <select name="status" class="form-select" style="width: 150px;">
                                        <option value="">كل الحالات</option>
                                        <option value="present" {{ request('status') == 'present' ? 'selected' : '' }}>حاضر</option>
                                        <option value="absent" {{ request('status') == 'absent' ? 'selected' : '' }}>غائب</option>
                                        <option value="late" {{ request('status') == 'late' ? 'selected' : '' }}>متأخر</option>
                                        <option value="excused" {{ request('status') == 'excused' ? 'selected' : '' }}>معذور</option>
                                    </select>
                                    <button type="submit" class="btn btn-secondary">بحث</button>
                                    <a href="{{ route('admin.lecture-attendance.index') }}" class="btn btn-danger">مسح</a>
                                </form>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover align-middle table-nowrap mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>المحاضرة</th>
                                            <th>الطالب</th>
                                            <th>الحالة</th>
                                            <th>وقت الانضمام</th>
                                            <th>مدة الحضور</th>
                                            <th>العمليات</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($attendance as $record)
                                            <tr>
                                                <td>{{ $record->id }}</td>
                                                <td>{{ $record->lecture->title }}</td>
                                                <td>{{ $record->student->user->name ?? '-' }}</td>
                                                <td>
                                                    <span class="badge bg-{{ $record->status_color }}">{{ $record->status_label }}</span>
                                                </td>
                                                <td>{{ $record->joined_at ? $record->joined_at->format('Y-m-d H:i') : '-' }}</td>
                                                <td>{{ $record->duration_minutes ? $record->duration_minutes . ' دقيقة' : '-' }}</td>
                                                <td>
                                                    <div class="d-flex gap-2">
                                                        <a href="{{ route('admin.lecture-attendance.show', $record->id) }}" class="btn btn-sm btn-info" title="عرض">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                                <circle cx="12" cy="12" r="3"></circle>
                                                            </svg>
                                                        </a>
                                                        @can('lecture-attendance-edit')
                                                        <a href="{{ route('admin.lecture-attendance.edit', $record->id) }}" class="btn btn-sm btn-warning" title="تعديل">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                                            </svg>
                                                        </a>
                                                        @endcan
                                                        @can('lecture-attendance-delete')
                                                        <button type="button" class="btn btn-sm btn-danger" 
                                                                data-delete-url="{{ route('admin.lecture-attendance.destroy', $record->id) }}"
                                                                data-delete-message="هل أنت متأكد من رغبتك في حذف سجل الحضور؟"
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
                                                <td colspan="7" class="text-center">لا توجد سجلات حضور</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-3">
                                {{ $attendance->links() }}
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

