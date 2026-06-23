@extends('admin.layouts.master')
@section('page-title') تفاصيل السائق @stop
@section('content')
@php $statuses = ['active' => 'نشط', 'inactive' => 'غير نشط', 'on_leave' => 'في إجازة']; @endphp
<div class="main-content app-content"><div class="container-fluid">
<div class="admin-page-header">
    <div class="page-title-wrap"><h1>{{ $driver->user->name ?? $driver->driver_code }}</h1></div>
    @can('driver-edit')
        <a href="{{ route('admin.drivers.edit', $driver) }}" class="admin-btn admin-btn-primary"><i class="ri-edit-line"></i> تعديل</a>
    @endcan
</div>
<div class="admin-page-card"><div class="admin-detail-grid">
    <div class="admin-detail-item"><span class="label">رقم السائق</span><span class="value">{{ $driver->driver_code }}</span></div>
    <div class="admin-detail-item"><span class="label">الرخصة</span><span class="value">{{ $driver->license_number }}</span></div>
    <div class="admin-detail-item"><span class="label">الهاتف</span><span class="value">{{ $driver->phone ?? '—' }}</span></div>
    <div class="admin-detail-item"><span class="label">الحالة</span><span class="value">{{ $statuses[$driver->status] ?? $driver->status }}</span></div>
</div>
<p class="mt-3 text-muted">رحلات مرتبطة: {{ $driver->studentTransports->count() }}</p>
</div></div></div>
@stop
