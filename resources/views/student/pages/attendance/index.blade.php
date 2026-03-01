@extends('student.layouts.master')

@section('page-title')
    الحضور والغياب
@stop

@section('content')
    <!-- Start::app-content -->
    <div class="main-content app-content">
        <div class="container-fluid">
            <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
                <div class="my-auto">
                    <h5 class="page-title fs-21 mb-1">سجل الحضور والغياب</h5>
                </div>
            </div>

            <!-- إحصائيات -->
            <div class="row mb-4">
                <div class="col-xl-3 col-lg-6 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="avatar avatar-md bg-primary text-white rounded">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-0">إجمالي الأيام</h6>
                                    <h4 class="mb-0">{{ $stats['total_days'] }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-6 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="avatar avatar-md bg-success text-white rounded">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <polyline points="20 6 9 17 4 12"></polyline>
                                        </svg>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-0">أيام الحضور</h6>
                                    <h4 class="mb-0">{{ $stats['present_days'] }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-6 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="avatar avatar-md bg-danger text-white rounded">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <line x1="18" y1="6" x2="6" y2="18"></line>
                                            <line x1="6" y1="6" x2="18" y2="18"></line>
                                        </svg>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-0">أيام الغياب</h6>
                                    <h4 class="mb-0">{{ $stats['absent_days'] }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-6 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="avatar avatar-md bg-info text-white rounded">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <circle cx="12" cy="12" r="10"></circle>
                                            <polyline points="12 6 12 12 16 14"></polyline>
                                        </svg>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-0">معدل الحضور</h6>
                                    <h4 class="mb-0">{{ $stats['attendance_rate'] }}%</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header align-items-center d-flex gap-3">
                            <div class="flex-shrink-0">
                                <form action="{{ route('student.attendance.index') }}" method="GET" class="d-flex align-items-center gap-2">
                                    <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}" style="width: 180px;" placeholder="من تاريخ">
                                    <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}" style="width: 180px;" placeholder="إلى تاريخ">
                                    <select name="status" class="form-select" style="width: 150px;">
                                        <option value="">كل الحالات</option>
                                        <option value="present" {{ request('status') == 'present' ? 'selected' : '' }}>حاضر</option>
                                        <option value="absent" {{ request('status') == 'absent' ? 'selected' : '' }}>غائب</option>
                                        <option value="late" {{ request('status') == 'late' ? 'selected' : '' }}>متأخر</option>
                                        <option value="excused" {{ request('status') == 'excused' ? 'selected' : '' }}>معذور</option>
                                    </select>
                                    <button type="submit" class="btn btn-secondary">بحث</button>
                                    <a href="{{ route('student.attendance.index') }}" class="btn btn-danger">مسح</a>
                                </form>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover align-middle table-nowrap mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>التاريخ</th>
                                            <th>الفصل</th>
                                            <th>الحالة</th>
                                            <th>وقت الحضور</th>
                                            <th>وقت المغادرة</th>
                                            <th>الملاحظات</th>
                                            <th>العمليات</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($attendances as $attendance)
                                            <tr>
                                                <td>{{ $attendance->id }}</td>
                                                <td>{{ $attendance->date->format('Y-m-d') }}</td>
                                                <td>{{ $attendance->section->name ?? '-' }}</td>
                                                <td>
                                                    @if($attendance->status == 'present')
                                                        <span class="badge bg-success">حاضر</span>
                                                    @elseif($attendance->status == 'absent')
                                                        <span class="badge bg-danger">غائب</span>
                                                    @elseif($attendance->status == 'late')
                                                        <span class="badge bg-warning">متأخر</span>
                                                    @else
                                                        <span class="badge bg-info">معذور</span>
                                                    @endif
                                                </td>
                                                <td>{{ $attendance->check_in_time ? $attendance->check_in_time->format('H:i') : '-' }}</td>
                                                <td>{{ $attendance->check_out_time ? $attendance->check_out_time->format('H:i') : '-' }}</td>
                                                <td>{{ $attendance->notes ?? '-' }}</td>
                                                <td>
                                                    <a href="{{ route('student.attendance.show', $attendance->id) }}" class="btn btn-sm btn-info" title="عرض">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                            <circle cx="12" cy="12" r="3"></circle>
                                                        </svg>
                                                    </a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="8" class="text-center">لا توجد سجلات حضور</td>
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
    <!-- End::app-content -->
@stop

