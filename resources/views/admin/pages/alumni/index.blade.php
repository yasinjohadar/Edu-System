@extends('admin.layouts.master')

@section('page-title')
    الخريجون
@stop

@section('content')
    <div class="main-content app-content">
        <div class="container-fluid">
            <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
                <div class="my-auto">
                    <h5 class="page-title fs-21 mb-1">الخريجون</h5>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header align-items-center d-flex gap-3">
                            <div class="d-flex gap-2">
                                <a href="{{ route('admin.alumni.create') }}" class="btn btn-primary btn-sm">إضافة خريج جديد</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover align-middle table-nowrap mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>الاسم</th>
                                            <th>البريد الإلكتروني</th>
                                            <th>تاريخ التخرج</th>
                                            <th>الدرجة</th>
                                            <th>الوظيفة الحالية</th>
                                            <th>الحالة</th>
                                            <th>العمليات</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($alumni as $alumnus)
                                            <tr>
                                                <td>{{ $alumnus->id }}</td>
                                                <td>{{ $alumnus->name }}</td>
                                                <td>{{ $alumnus->email }}</td>
                                                <td>{{ $alumnus->graduation_date->format('Y-m-d') }}</td>
                                                <td>{{ $alumnus->degree ?? '-' }}</td>
                                                <td>{{ $alumnus->current_job ?? '-' }}</td>
                                                <td>
                                                    @if($alumnus->is_active)
                                                        <span class="badge bg-success">نشط</span>
                                                    @else
                                                        <span class="badge bg-danger">غير نشط</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="d-flex gap-2">
                                                        <a href="{{ route('admin.alumni.show', $alumnus) }}" class="btn btn-sm btn-info">عرض</a>
                                                        <a href="{{ route('admin.alumni.edit', $alumnus) }}" class="btn btn-sm btn-primary">تعديل</a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="8" class="text-center">لا يوجد خريجون</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-3">
                                {{ $alumni->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

