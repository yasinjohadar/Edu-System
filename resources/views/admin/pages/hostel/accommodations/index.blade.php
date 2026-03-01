@extends('admin.layouts.master')

@section('page-title')
    إقامة الطلاب
@stop

@section('content')
    <div class="main-content app-content">
        <div class="container-fluid">
            <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
                <div class="my-auto">
                    <h5 class="page-title fs-21 mb-1">إقامة الطلاب</h5>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header align-items-center d-flex gap-3">
                            <div class="d-flex gap-2">
                                <a href="{{ route('admin.student-accommodations.create') }}" class="btn btn-primary btn-sm">إضافة إقامة جديدة</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover align-middle table-nowrap mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>اسم الطالب</th>
                                            <th>النزل</th>
                                            <th>الغرفة</th>
                                            <th>السرير</th>
                                            <th>تاريخ الدخول</th>
                                            <th>الحالة</th>
                                            <th>العمليات</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($accommodations as $accommodation)
                                            <tr>
                                                <td>{{ $accommodation->id }}</td>
                                                <td>{{ $accommodation->student->user->name ?? '-' }}</td>
                                                <td>{{ $accommodation->hostel->name ?? '-' }}</td>
                                                <td>{{ $accommodation->room->room_number ?? '-' }}</td>
                                                <td>{{ $accommodation->bed->bed_number ?? '-' }}</td>
                                                <td>{{ $accommodation->check_in_date->format('Y-m-d') }}</td>
                                                <td><span class="badge bg-info">{{ $accommodation->status }}</span></td>
                                                <td>
                                                    <div class="d-flex gap-2">
                                                        <a href="{{ route('admin.student-accommodations.show', $accommodation) }}" class="btn btn-sm btn-info">عرض</a>
                                                        <a href="{{ route('admin.student-accommodations.edit', $accommodation) }}" class="btn btn-sm btn-primary">تعديل</a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="8" class="text-center">لا توجد إقامات</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-3">
                                {{ $accommodations->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

