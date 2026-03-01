@extends('admin.layouts.master')

@section('page-title')
    نقل الطلاب
@stop

@section('content')
    <div class="main-content app-content">
        <div class="container-fluid">
            <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
                <div class="my-auto">
                    <h5 class="page-title fs-21 mb-1">نقل الطلاب</h5>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header align-items-center d-flex gap-3">
                            <div class="d-flex gap-2">
                                <a href="{{ route('admin.student-transports.create') }}" class="btn btn-primary btn-sm">إضافة نقل جديد</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover align-middle table-nowrap mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>اسم الطالب</th>
                                            <th>المسار</th>
                                            <th>المحطة</th>
                                            <th>السائق</th>
                                            <th>المشرف</th>
                                            <th>الحالة</th>
                                            <th>العمليات</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($transports as $transport)
                                            <tr>
                                                <td>{{ $transport->id }}</td>
                                                <td>{{ $transport->student->user->name ?? '-' }}</td>
                                                <td>{{ $transport->route->route_name ?? '-' }}</td>
                                                <td>{{ $transport->stop->stop_name ?? '-' }}</td>
                                                <td>{{ $transport->driver->user->name ?? '-' }}</td>
                                                <td>{{ $transport->supervisor->user->name ?? '-' }}</td>
                                                <td><span class="badge bg-info">{{ $transport->status }}</span></td>
                                                <td>
                                                    <div class="d-flex gap-2">
                                                        <a href="{{ route('admin.student-transports.show', $transport) }}" class="btn btn-sm btn-info">عرض</a>
                                                        <a href="{{ route('admin.student-transports.edit', $transport) }}" class="btn btn-sm btn-primary">تعديل</a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="8" class="text-center">لا يوجد نقل</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-3">
                                {{ $transports->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

