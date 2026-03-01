@extends('admin.layouts.master')

@section('page-title')
    قائمة الحضور والغياب
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
                    <h5 class="page-title fs-21 mb-1">سجل الحضور والغياب</h5>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header align-items-center d-flex gap-3">
                            <a href="{{ route('admin.attendances.create') }}" class="btn btn-primary btn-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-left: 4px; vertical-align: middle;">
                                    <line x1="12" y1="5" x2="12" y2="19"></line>
                                    <line x1="5" y1="12" x2="19" y2="12"></line>
                                </svg>
                                تسجيل حضور جديد
                            </a>
                            <div class="flex-shrink-0">
                                <form action="{{ route('admin.attendances.index') }}" method="GET" class="d-flex align-items-center gap-2">
                                    <select name="section_id" class="form-select" style="width: 200px;">
                                        <option value="">كل الفصول</option>
                                        @foreach($sections as $section)
                                            <option value="{{ $section->id }}" {{ request('section_id') == $section->id ? 'selected' : '' }}>
                                                {{ $section->class->grade->name }} - {{ $section->class->name }} - {{ $section->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <input type="date" name="date" class="form-control" value="{{ request('date', date('Y-m-d')) }}" style="width: 180px;">
                                    <select name="status" class="form-select" style="width: 150px;">
                                        <option value="">كل الحالات</option>
                                        <option value="present" {{ request('status') == 'present' ? 'selected' : '' }}>حاضر</option>
                                        <option value="absent" {{ request('status') == 'absent' ? 'selected' : '' }}>غائب</option>
                                        <option value="late" {{ request('status') == 'late' ? 'selected' : '' }}>متأخر</option>
                                        <option value="excused" {{ request('status') == 'excused' ? 'selected' : '' }}>معذور</option>
                                    </select>
                                    <button type="submit" class="btn btn-secondary">بحث</button>
                                    <a href="{{ route('admin.attendances.index') }}" class="btn btn-danger">مسح</a>
                                </form>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover align-middle table-nowrap mb-0">
                                    <thead class="table-light">
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
                                    <tbody>
                                        @forelse($attendances as $attendance)
                                            <tr>
                                                <td>{{ $attendance->id }}</td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div>
                                                            <h6 class="mb-0">{{ $attendance->student->user->name }}</h6>
                                                            <small class="text-muted">{{ $attendance->student->student_code }}</small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    {{ $attendance->section->class->grade->name }} - 
                                                    {{ $attendance->section->class->name }} - 
                                                    {{ $attendance->section->name }}
                                                </td>
                                                <td>{{ $attendance->date->format('Y-m-d') }}</td>
                                                <td>
                                                    <span class="badge bg-{{ $attendance->status_color }}">
                                                        {{ $attendance->status_label }}
                                                    </span>
                                                </td>
                                                <td>
                                                    @if($attendance->check_in_time)
                                                        {{ \Carbon\Carbon::parse($attendance->check_in_time)->format('H:i') }}
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($attendance->notes)
                                                        <span class="text-truncate d-inline-block" style="max-width: 150px;" title="{{ $attendance->notes }}">
                                                            {{ $attendance->notes }}
                                                        </span>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td>{{ $attendance->markedBy->name ?? '-' }}</td>
                                                <td>
                                                    <div class="d-flex gap-2">
                                                        <a href="{{ route('admin.attendances.edit', $attendance->id) }}" class="btn btn-sm btn-info">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-left: 4px; vertical-align: middle;">
                                                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                                            </svg>
                                                            تعديل
                                                        </a>
                                                        <button type="button" class="btn btn-sm btn-danger" 
                                                                data-delete-url="{{ route('admin.attendances.destroy', $attendance->id) }}"
                                                                data-delete-message="هل أنت متأكد من رغبتك في حذف سجل الحضور للطالب <strong>{{ $attendance->student->user->name }}</strong> في تاريخ <strong>{{ $attendance->date->format('Y-m-d') }}</strong>؟">
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
                                                <td colspan="9" class="text-center">لا توجد سجلات حضور</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-3">
                                {{ $attendances->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    @include('admin.components.delete-modal')
@stop

