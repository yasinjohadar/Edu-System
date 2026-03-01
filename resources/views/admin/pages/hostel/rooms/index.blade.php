@extends('admin.layouts.master')

@section('page-title')
    الغرف
@stop

@section('content')
    <div class="main-content app-content">
        <div class="container-fluid">
            <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
                <div class="my-auto">
                    <h5 class="page-title fs-21 mb-1">الغرف</h5>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header align-items-center d-flex gap-3">
                            <div class="d-flex gap-2">
                                <a href="{{ route('admin.rooms.create') }}" class="btn btn-primary btn-sm">إضافة غرفة جديدة</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover align-middle table-nowrap mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>رقم الغرفة</th>
                                            <th>النزل</th>
                                            <th>السعة</th>
                                            <th>الأسرة المتاحة</th>
                                            <th>النوع</th>
                                            <th>الرسوم</th>
                                            <th>العمليات</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($rooms as $room)
                                            <tr>
                                                <td>{{ $room->id }}</td>
                                                <td>{{ $room->room_number }}</td>
                                                <td>{{ $room->hostel->name ?? '-' }}</td>
                                                <td>{{ $room->capacity }}</td>
                                                <td>{{ $room->available_beds }}</td>
                                                <td><span class="badge bg-info">{{ $room->type }}</span></td>
                                                <td>{{ number_format($room->fee, 2) }}</td>
                                                <td>
                                                    <div class="d-flex gap-2">
                                                        <a href="{{ route('admin.rooms.show', $room) }}" class="btn btn-sm btn-info">عرض</a>
                                                        <a href="{{ route('admin.rooms.edit', $room) }}" class="btn btn-sm btn-primary">تعديل</a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="8" class="text-center">لا توجد غرف</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-3">
                                {{ $rooms->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

