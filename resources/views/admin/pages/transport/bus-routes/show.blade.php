@extends('admin.layouts.master')

@section('page-title')
    تفاصيل مسار الحافلة
@stop

@section('content')
    <div class="main-content app-content">
        <div class="container-fluid">
            <div class="admin-page-header">
                <div class="page-title-wrap">
                    <h1>{{ $busRoute->route_name }}</h1>
                    <p>{{ $busRoute->route_number }}</p>
                </div>
                <div class="admin-page-header-actions">
                    @can('bus-route-edit')
                        <a href="{{ route('admin.bus-routes.edit', $busRoute) }}" class="admin-btn admin-btn-primary">
                            <i class="ri-edit-line"></i> تعديل
                        </a>
                    @endcan
                    <a href="{{ route('admin.bus-routes.index') }}" class="admin-btn admin-btn-secondary">
                        <i class="ri-arrow-right-line"></i> العودة
                    </a>
                </div>
            </div>

            <div class="admin-page-card">
                <div class="admin-detail-card-body">
                    <div class="admin-detail-grid mb-4">
                        <div class="admin-detail-item"><span class="label">الرسوم</span><span class="value">{{ number_format($busRoute->fee, 2) }} ر.س</span></div>
                        <div class="admin-detail-item"><span class="label">المسافة</span><span class="value">{{ $busRoute->distance ?? '—' }} كم</span></div>
                        <div class="admin-detail-item"><span class="label">الوقت</span><span class="value">{{ $busRoute->start_time }} — {{ $busRoute->end_time }}</span></div>
                        <div class="admin-detail-item"><span class="label">الحالة</span>
                            <span class="admin-badge {{ $busRoute->is_active ? 'admin-badge-success' : 'admin-badge-danger' }}">
                                {{ $busRoute->is_active ? 'نشط' : 'غير نشط' }}
                            </span>
                        </div>
                    </div>

                    @if ($busRoute->stops->count())
                        <h5 class="mb-3">المحطات ({{ $busRoute->stops->count() }})</h5>
                        <div class="table-responsive mb-4">
                            <table class="admin-data-table">
                                <thead><tr><th>#</th><th>المحطة</th><th>الترتيب</th><th>وقت الوصول</th></tr></thead>
                                <tbody>
                                    @foreach ($busRoute->stops as $stop)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $stop->stop_name }}</td>
                                            <td>{{ $stop->order }}</td>
                                            <td>{{ $stop->arrival_time ?? '—' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif

                    <p class="text-muted mb-0">طلاب مسجلون على هذا المسار: {{ $busRoute->studentTransports->count() }}</p>
                </div>
            </div>
        </div>
    </div>
@stop
