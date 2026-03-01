@extends('admin.layouts.master')

@section('page-title')
    مسارات الحافلات
@stop

@section('content')
    <div class="main-content app-content">
        <div class="container-fluid">
            <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
                <div class="my-auto">
                    <h5 class="page-title fs-21 mb-1">مسارات الحافلات</h5>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header align-items-center d-flex gap-3">
                            <div class="d-flex gap-2">
                                <a href="{{ route('admin.bus-routes.create') }}" class="btn btn-primary btn-sm">إضافة مسار جديد</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover align-middle table-nowrap mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>اسم المسار</th>
                                            <th>رقم المسار</th>
                                            <th>المسافة</th>
                                            <th>الرسوم</th>
                                            <th>الحالة</th>
                                            <th>العمليات</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($routes as $route)
                                            <tr>
                                                <td>{{ $route->id }}</td>
                                                <td>{{ $route->route_name }}</td>
                                                <td>{{ $route->route_number }}</td>
                                                <td>{{ $route->distance }} km</td>
                                                <td>{{ number_format($route->fee, 2) }}</td>
                                                <td>
                                                    @if($route->is_active)
                                                        <span class="badge bg-success">نشط</span>
                                                    @else
                                                        <span class="badge bg-danger">غير نشط</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="d-flex gap-2">
                                                        <a href="{{ route('admin.bus-routes.show', $route) }}" class="btn btn-sm btn-info">عرض</a>
                                                        <a href="{{ route('admin.bus-routes.edit', $route) }}" class="btn btn-sm btn-primary">تعديل</a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center">لا توجد مسارات</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-3">
                                {{ $routes->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

