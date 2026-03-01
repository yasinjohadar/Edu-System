@extends('admin.layouts.master')

@section('page-title')
    التقارير
@stop

@section('content')
    @if (\Session::has('success'))
        <div class="alert alert-success">
            <ul>
                <li>{!! \Session::get('success') !!}</li>
            </ul>
        </div>
    @endif

    @if (\Session::has('error'))
        <div class="alert alert-danger">
            <ul>
                <li>{!! \Session::get('error') !!}</li>
            </ul>
        </div>
    @endif

    <!-- Start::app-content -->
    <div class="main-content app-content">
        <div class="container-fluid">
            <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
                <div class="my-auto">
                    <h5 class="page-title fs-21 mb-1">التقارير</h5>
                </div>
                <div>
                    <a href="{{ route('admin.reports.create') }}" class="btn btn-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-left: 4px; vertical-align: middle;">
                            <line x1="12" y1="5" x2="12" y2="19"></line>
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                        إنشاء تقرير جديد
                    </a>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">التقارير المحفوظة</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover align-middle table-nowrap mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>اسم التقرير</th>
                                            <th>النوع</th>
                                            <th>الصيغة</th>
                                            <th>الحالة</th>
                                            <th>أنشئ بواسطة</th>
                                            <th>تاريخ الإنشاء</th>
                                            <th>العمليات</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($reports as $report)
                                            <tr>
                                                <td>{{ $report->id }}</td>
                                                <td>{{ $report->name }}</td>
                                                <td>
                                                    <span class="badge bg-info">{{ $report->type_name }}</span>
                                                </td>
                                                <td>
                                                    <span class="badge bg-secondary">{{ strtoupper($report->format) }}</span>
                                                </td>
                                                <td>
                                                    @if($report->status == 'completed')
                                                        <span class="badge bg-success">{{ $report->status_name }}</span>
                                                    @elseif($report->status == 'generating')
                                                        <span class="badge bg-warning">{{ $report->status_name }}</span>
                                                    @elseif($report->status == 'failed')
                                                        <span class="badge bg-danger">{{ $report->status_name }}</span>
                                                    @else
                                                        <span class="badge bg-secondary">{{ $report->status_name }}</span>
                                                    @endif
                                                </td>
                                                <td>{{ $report->creator->name ?? '-' }}</td>
                                                <td>{{ $report->generated_at ? $report->generated_at->format('Y-m-d H:i') : '-' }}</td>
                                                <td>
                                                    @if($report->status == 'completed' && $report->file_path)
                                                        <a href="{{ Storage::url($report->file_path) }}" class="btn btn-sm btn-primary" download>
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                                                <polyline points="7 10 12 15 17 10"></polyline>
                                                                <line x1="12" y1="15" x2="12" y2="3"></line>
                                                            </svg>
                                                            تحميل
                                                        </a>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="8" class="text-center">لا توجد تقارير محفوظة</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-3">
                                {{ $reports->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End::app-content -->
@stop

