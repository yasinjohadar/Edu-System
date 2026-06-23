@extends('admin.layouts.master')
@section('page-title') تفاصيل المشرف @stop
@section('content')
@php $statuses = ['active' => 'نشط', 'inactive' => 'غير نشط']; @endphp
<div class="main-content app-content"><div class="container-fluid">
<div class="admin-page-header">
    <div class="page-title-wrap"><h1>{{ $supervisor->user->name ?? $supervisor->supervisor_code }}</h1></div>
    @can('supervisor-edit')
        <a href="{{ route('admin.supervisors.edit', $supervisor) }}" class="admin-btn admin-btn-primary"><i class="ri-edit-line"></i> تعديل</a>
    @endcan
</div>
<div class="admin-page-card"><div class="admin-detail-grid">
    <div class="admin-detail-item"><span class="label">رقم المشرف</span><span class="value">{{ $supervisor->supervisor_code }}</span></div>
    <div class="admin-detail-item"><span class="label">الهاتف</span><span class="value">{{ $supervisor->phone ?? '—' }}</span></div>
    <div class="admin-detail-item"><span class="label">الحالة</span><span class="value">{{ $statuses[$supervisor->status] ?? $supervisor->status }}</span></div>
</div>
<p class="mt-3 text-muted">طلاب مرتبطون: {{ $supervisor->studentTransports->count() }}</p>
</div></div></div>
@stop
