@extends('admin.layouts.master')

@section('page-title')
    السائقون
@stop

@section('content')
    <div class="main-content app-content">
        <div class="container-fluid">
            <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
                <div class="my-auto">
                    <h5 class="page-title fs-21 mb-1">السائقون</h5>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header align-items-center d-flex gap-3">
                            <div class="d-flex gap-2">
                                <a href="{{ route('admin.drivers.create') }}" class="btn btn-primary btn-sm">إضافة سائق جديد</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover align-middle table-nowrap mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>اسم السائق</th>
                                            <th>رقم السائق</th>
                                            <th>رقم الرخصة</th>
                                            <th>الهاتف</th>
                                            <th>الحالة</th>
                                            <th>العمليات</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($drivers as $driver)
                                            <tr>
                                                <td>{{ $driver->id }}</td>
                                                <td>{{ $driver->user->name ?? '-' }}</td>
                                                <td>{{ $driver->driver_code }}</td>
                                                <td>{{ $driver->license_number }}</td>
                                                <td>{{ $driver->phone ?? '-' }}</td>
                                                <td><span class="badge bg-info">{{ $driver->status }}</span></td>
                                                <td>
                                                    <div class="d-flex gap-2">
                                                        <a href="{{ route('admin.drivers.show', $driver) }}" class="btn btn-sm btn-info">عرض</a>
                                                        <a href="{{ route('admin.drivers.edit', $driver) }}" class="btn btn-sm btn-primary">تعديل</a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center">لا يوجد سائقون</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-3">
                                {{ $drivers->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

