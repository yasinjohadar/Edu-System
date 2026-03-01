@extends('admin.layouts.master')

@section('page-title')
    محطات الحافلات
@stop

@section('content')
    <div class="main-content app-content">
        <div class="container-fluid">
            <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
                <div class="my-auto">
                    <h5 class="page-title fs-21 mb-1">محطات الحافلات</h5>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header align-items-center d-flex gap-3">
                            <div class="d-flex gap-2">
                                <a href="{{ route('admin.bus-stops.create') }}" class="btn btn-primary btn-sm">إضافة محطة جديدة</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover align-middle table-nowrap mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>اسم المحطة</th>
                                            <th>المسار</th>
                                            <th>العنوان</th>
                                            <th>الترتيب</th>
                                            <th>العمليات</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($stops as $stop)
                                            <tr>
                                                <td>{{ $stop->id }}</td>
                                                <td>{{ $stop->stop_name }}</td>
                                                <td>{{ $stop->route->route_name ?? '-' }}</td>
                                                <td>{{ $stop->address ?? '-' }}</td>
                                                <td>{{ $stop->order }}</td>
                                                <td>
                                                    <div class="d-flex gap-2">
                                                        <a href="{{ route('admin.bus-stops.show', $stop) }}" class="btn btn-sm btn-info">عرض</a>
                                                        <a href="{{ route('admin.bus-stops.edit', $stop) }}" class="btn btn-sm btn-primary">تعديل</a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center">لا توجد محطات</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-3">
                                {{ $stops->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

