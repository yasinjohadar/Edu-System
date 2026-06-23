@extends('admin.layouts.master')
@section('page-title') تفاصيل المحطة @stop
@section('content')
<div class="main-content app-content"><div class="container-fluid">
<div class="admin-page-header">
    <div class="page-title-wrap"><h1>{{ $busStop->stop_name }}</h1><p>{{ $busStop->route->route_name ?? '' }}</p></div>
    @can('bus-stop-edit')
        <a href="{{ route('admin.bus-stops.edit', $busStop) }}" class="admin-btn admin-btn-primary"><i class="ri-edit-line"></i> تعديل</a>
    @endcan
</div>
<div class="admin-page-card"><div class="admin-detail-grid">
    <div class="admin-detail-item"><span class="label">العنوان</span><span class="value">{{ $busStop->address ?? '—' }}</span></div>
    <div class="admin-detail-item"><span class="label">الترتيب</span><span class="value">{{ $busStop->order }}</span></div>
    <div class="admin-detail-item"><span class="label">وقت الوصول</span><span class="value">{{ $busStop->arrival_time ?? '—' }}</span></div>
</div></div></div></div>
@stop
