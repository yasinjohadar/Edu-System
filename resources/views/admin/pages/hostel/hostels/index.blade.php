@extends('admin.layouts.master')

@section('page-title')
    النزل
@stop

@section('content')
    <div class="main-content app-content">
        <div class="container-fluid">
            <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
                <div class="my-auto">
                    <h5 class="page-title fs-21 mb-1">النزل</h5>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header align-items-center d-flex gap-3">
                            <div class="d-flex gap-2">
                                <a href="{{ route('admin.hostels.create') }}" class="btn btn-primary btn-sm">إضافة نزل جديد</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover align-middle table-nowrap mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>اسم النزل</th>
                                            <th>العنوان</th>
                                            <th>عدد الغرف</th>
                                            <th>عدد الأسرة</th>
                                            <th>الجنس</th>
                                            <th>الحالة</th>
                                            <th>العمليات</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($hostels as $hostel)
                                            <tr>
                                                <td>{{ $hostel->id }}</td>
                                                <td>{{ $hostel->name }}</td>
                                                <td>{{ $hostel->address ?? '-' }}</td>
                                                <td>{{ $hostel->total_rooms }}</td>
                                                <td>{{ $hostel->total_beds }}</td>
                                                <td><span class="badge bg-info">{{ $hostel->gender }}</span></td>
                                                <td>
                                                    @if($hostel->is_active)
                                                        <span class="badge bg-success">نشط</span>
                                                    @else
                                                        <span class="badge bg-danger">غير نشط</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="d-flex gap-2">
                                                        <a href="{{ route('admin.hostels.show', $hostel) }}" class="btn btn-sm btn-info">عرض</a>
                                                        <a href="{{ route('admin.hostels.edit', $hostel) }}" class="btn btn-sm btn-primary">تعديل</a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="8" class="text-center">لا توجد نزل</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-3">
                                {{ $hostels->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

