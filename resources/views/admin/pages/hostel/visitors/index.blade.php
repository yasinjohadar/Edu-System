@extends('admin.layouts.master')

@section('page-title')
    الزوار
@stop

@section('content')
    <div class="main-content app-content">
        <div class="container-fluid">
            <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
                <div class="my-auto">
                    <h5 class="page-title fs-21 mb-1">الزوار</h5>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header align-items-center d-flex gap-3">
                            <div class="d-flex gap-2">
                                <a href="{{ route('admin.visitors.create') }}" class="btn btn-primary btn-sm">إضافة زائر جديد</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover align-middle table-nowrap mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>اسم الزائر</th>
                                            <th>اسم الطالب</th>
                                            <th>العلاقة</th>
                                            <th>تاريخ الزيارة</th>
                                            <th>العمليات</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($visitors as $visitor)
                                            <tr>
                                                <td>{{ $visitor->id }}</td>
                                                <td>{{ $visitor->visitor_name }}</td>
                                                <td>{{ $visitor->student->user->name ?? '-' }}</td>
                                                <td>{{ $visitor->relationship ?? '-' }}</td>
                                                <td>{{ $visitor->visit_date->format('Y-m-d H:i') }}</td>
                                                <td>
                                                    <div class="d-flex gap-2">
                                                        <a href="{{ route('admin.visitors.show', $visitor) }}" class="btn btn-sm btn-info">عرض</a>
                                                        <a href="{{ route('admin.visitors.edit', $visitor) }}" class="btn btn-sm btn-primary">تعديل</a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center">لا يوجد زوار</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-3">
                                {{ $visitors->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

