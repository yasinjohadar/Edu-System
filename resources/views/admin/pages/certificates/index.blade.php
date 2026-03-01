@extends('admin.layouts.master')

@section('page-title')
    قائمة الشهادات
@stop

@section('content')
    @if (\Session::has('success'))
        <div class="alert alert-success">
            <ul>
                <li>{!! \Session::get('success') !!}</li>
            </ul>
        </div>
    @endif

    <div class="main-content app-content">
        <div class="container-fluid">
            <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
                <div class="my-auto">
                    <h5 class="page-title fs-21 mb-1">كافة الشهادات</h5>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header align-items-center d-flex gap-3">
                            <div class="d-flex gap-2">
                                <a href="{{ route('admin.certificates.create') }}" class="btn btn-primary btn-sm">إضافة شهادة جديدة</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover align-middle table-nowrap mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>رقم الشهادة</th>
                                            <th>اسم الطالب</th>
                                            <th>نوع الشهادة</th>
                                            <th>تاريخ الإصدار</th>
                                            <th>العمليات</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($certificates as $certificate)
                                            <tr>
                                                <td>{{ $certificate->id }}</td>
                                                <td>{{ $certificate->certificate_number }}</td>
                                                <td>{{ $certificate->student->user->name ?? '-' }}</td>
                                                <td><span class="badge bg-info">{{ $certificate->type }}</span></td>
                                                <td>{{ $certificate->issue_date->format('Y-m-d') }}</td>
                                                <td>
                                                    <div class="d-flex gap-2">
                                                        <a href="{{ route('admin.certificates.show', $certificate) }}" class="btn btn-sm btn-info">عرض</a>
                                                        <a href="{{ route('admin.certificates.edit', $certificate) }}" class="btn btn-sm btn-primary">تعديل</a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center">لا توجد شهادات</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-3">
                                {{ $certificates->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

