@extends('admin.layouts.master')

@section('page-title')
    قائمة الجدول الدراسي
@stop

@section('content')
    @if (\Session::has('success'))
        <div class="alert alert-success">
            <ul>
                <li>{!! \Session::get('success') !!}</li>
            </ul>
        </div>
    @endif

    @if (\Session::has('error'))
        <div class="alert alert-danger">
            <ul>
                <li>{!! \Session::get('error') !!}</li>
            </ul>
        </div>
    @endif

    <!-- Start::app-content -->
    <div class="main-content app-content">
        <div class="container-fluid">
            <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
                <div class="my-auto">
                    <h5 class="page-title fs-21 mb-1">الجدول الدراسي</h5>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header align-items-center d-flex gap-3">
                            @can('schedule-create')
                            <a href="{{ route('admin.schedules.create') }}" class="btn btn-primary btn-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-left: 4px; vertical-align: middle;">
                                    <line x1="12" y1="5" x2="12" y2="19"></line>
                                    <line x1="5" y1="12" x2="19" y2="12"></line>
                                </svg>
                                إضافة جدول جديد
                            </a>
                            @endcan
                            <div class="flex-shrink-0">
                                <form action="{{ route('admin.schedules.index') }}" method="GET" class="d-flex align-items-center gap-2">
                                    <select name="section_id" class="form-select" style="width: 200px;">
                                        <option value="">كل الفصول</option>
                                        @foreach($sections as $section)
                                            <option value="{{ $section->id }}" {{ request('section_id') == $section->id ? 'selected' : '' }}>
                                                {{ $section->class->name }} - {{ $section->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <select name="teacher_id" class="form-select" style="width: 200px;">
                                        <option value="">كل المعلمين</option>
                                        @foreach($teachers as $teacher)
                                            <option value="{{ $teacher->id }}" {{ request('teacher_id') == $teacher->id ? 'selected' : '' }}>
                                                {{ $teacher->user->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <select name="day_of_week" class="form-select" style="width: 150px;">
                                        <option value="">كل الأيام</option>
                                        @foreach($days as $key => $day)
                                            <option value="{{ $key }}" {{ request('day_of_week') == $key ? 'selected' : '' }}>
                                                {{ $day }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <button type="submit" class="btn btn-secondary">بحث</button>
                                    <a href="{{ route('admin.schedules.index') }}" class="btn btn-danger">مسح</a>
                                </form>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover align-middle table-nowrap mb-0">
                                    <thead class="table-light">
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
                                    <tbody>
                                        @forelse($schedules as $schedule)
                                            <tr>
                                                <td>{{ $schedule->id }}</td>
                                                <td>
                                                    <span class="badge bg-info">{{ $schedule->section->class->name }} - {{ $schedule->section->name }}</span>
                                                </td>
                                                <td>{{ $schedule->subject->name }}</td>
                                                <td>{{ $schedule->teacher->user->name }}</td>
                                                <td>
                                                    <span class="badge bg-primary">{{ $schedule->day_name }}</span>
                                                </td>
                                                <td>{{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }}</td>
                                                <td>{{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}</td>
                                                <td>{{ $schedule->room ?? '-' }}</td>
                                                <td>
                                                    @if($schedule->is_active)
                                                        <span class="badge bg-success">نشط</span>
                                                    @else
                                                        <span class="badge bg-danger">غير نشط</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="d-flex gap-2">
                                                        @can('schedule-edit')
                                                        <a href="{{ route('admin.schedules.edit', $schedule->id) }}" class="btn btn-sm btn-warning" title="تعديل">
                                                            <i class="fa-solid fa-edit"></i>
                                                        </a>
                                                        @endcan
                                                        @can('schedule-delete')
                                                        <button type="button" class="btn btn-sm btn-danger" 
                                                                data-delete-url="{{ route('admin.schedules.destroy', $schedule->id) }}"
                                                                data-delete-message="هل أنت متأكد من رغبتك في حذف الجدول الدراسي <strong>{{ $schedule->subject->name }}</strong> للفصل <strong>{{ $schedule->section->name }}</strong>؟"
                                                                title="حذف">
                                                            <i class="fa-solid fa-trash-can"></i>
                                                        </button>
                                                        @endcan
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="10" class="text-center">لا توجد جداول دراسية</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-3">
                                {{ $schedules->links() }}
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

