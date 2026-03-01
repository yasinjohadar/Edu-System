@extends('admin.layouts.master')

@section('page-title')
    المشرفون
@stop

@section('content')
    <div class="main-content app-content">
        <div class="container-fluid">
            <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
                <div class="my-auto">
                    <h5 class="page-title fs-21 mb-1">المشرفون</h5>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header align-items-center d-flex gap-3">
                            <div class="d-flex gap-2">
                                <a href="{{ route('admin.supervisors.create') }}" class="btn btn-primary btn-sm">إضافة مشرف جديد</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover align-middle table-nowrap mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>اسم المشرف</th>
                                            <th>رقم المشرف</th>
                                            <th>الهاتف</th>
                                            <th>الحالة</th>
                                            <th>العمليات</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($supervisors as $supervisor)
                                            <tr>
                                                <td>{{ $supervisor->id }}</td>
                                                <td>{{ $supervisor->user->name ?? '-' }}</td>
                                                <td>{{ $supervisor->supervisor_code }}</td>
                                                <td>{{ $supervisor->phone ?? '-' }}</td>
                                                <td><span class="badge bg-info">{{ $supervisor->status }}</span></td>
                                                <td>
                                                    <div class="d-flex gap-2">
                                                        <a href="{{ route('admin.supervisors.show', $supervisor) }}" class="btn btn-sm btn-info">عرض</a>
                                                        <a href="{{ route('admin.supervisors.edit', $supervisor) }}" class="btn btn-sm btn-primary">تعديل</a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center">لا يوجد مشرفون</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-3">
                                {{ $supervisors->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

