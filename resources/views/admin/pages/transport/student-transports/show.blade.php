@extends('admin.layouts.master')
@section('page-title') تفاصيل نقل الطالب @stop
@section('content')
@php $statuses = ['active' => 'نشط', 'inactive' => 'غير نشط', 'suspended' => 'موقوف']; @endphp
<div class="main-content app-content"><div class="container-fluid">
<div class="admin-page-header">
    <div class="page-title-wrap">
        <h1>{{ $studentTransport->student->user->name ?? $studentTransport->student->student_code }}</h1>
        <p>{{ $studentTransport->route->route_name ?? '' }}</p>
    </div>
    @can('student-transport-edit')
        <a href="{{ route('admin.student-transports.edit', $studentTransport) }}" class="admin-btn admin-btn-primary"><i class="ri-edit-line"></i> تعديل</a>
    @endcan
</div>
<div class="admin-page-card"><div class="admin-detail-grid">
    <div class="admin-detail-item"><span class="label">المحطة</span><span class="value">{{ $studentTransport->stop->stop_name ?? '—' }}</span></div>
    <div class="admin-detail-item"><span class="label">السائق</span><span class="value">{{ $studentTransport->driver?->user?->name ?? $studentTransport->driver?->driver_code ?? '—' }}</span></div>
    <div class="admin-detail-item"><span class="label">المشرف</span><span class="value">{{ $studentTransport->supervisor?->user?->name ?? $studentTransport->supervisor?->supervisor_code ?? '—' }}</span></div>
    <div class="admin-detail-item"><span class="label">تاريخ البدء</span><span class="value">{{ $studentTransport->start_date?->format('Y-m-d') ?? '—' }}</span></div>
    <div class="admin-detail-item"><span class="label">تاريخ الانتهاء</span><span class="value">{{ $studentTransport->end_date?->format('Y-m-d') ?? '—' }}</span></div>
    <div class="admin-detail-item"><span class="label">الحالة</span><span class="value">{{ $statuses[$studentTransport->status] ?? $studentTransport->status }}</span></div>
</div></div></div></div>
@stop
