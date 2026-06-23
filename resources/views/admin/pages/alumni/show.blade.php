@extends('admin.layouts.master')
@section('page-title') تفاصيل الخريج @stop
@section('content')
<div class="main-content app-content"><div class="container-fluid">
<div class="admin-page-header">
    <div class="page-title-wrap"><h1>{{ $alumni->name }}</h1><p>{{ $alumni->email }}</p></div>
    <div class="d-flex gap-2">
        @can('alumni-edit')
            <a href="{{ route('admin.alumni.edit', $alumni) }}" class="admin-btn admin-btn-primary"><i class="ri-edit-line"></i> تعديل</a>
        @endcan
        <a href="{{ route('admin.alumni.index') }}" class="admin-btn admin-btn-secondary"><i class="ri-arrow-right-line"></i> العودة</a>
    </div>
</div>
<div class="admin-page-card"><div class="admin-detail-grid">
    <div class="admin-detail-item"><span class="label">الهاتف</span><span class="value">{{ $alumni->phone ?? '—' }}</span></div>
    <div class="admin-detail-item"><span class="label">تاريخ التخرج</span><span class="value">{{ $alumni->graduation_date?->format('Y-m-d') }}</span></div>
    <div class="admin-detail-item"><span class="label">الدرجة / التخصص</span><span class="value">{{ $alumni->degree ?? '—' }} / {{ $alumni->major ?? '—' }}</span></div>
    <div class="admin-detail-item"><span class="label">الوظيفة</span><span class="value">{{ $alumni->current_job ?? '—' }} @if($alumni->company) — {{ $alumni->company }}@endif</span></div>
    <div class="admin-detail-item"><span class="label">الطالب المرتبط</span><span class="value">{{ $alumni->student->user->name ?? '—' }}</span></div>
    <div class="admin-detail-item"><span class="label">الحالة</span><span class="value">{{ $alumni->is_active ? 'نشط' : 'غير نشط' }}</span></div>
    <div class="admin-detail-item"><span class="label">عدد التبرعات</span><span class="value">{{ $alumni->donations->count() }}</span></div>
    @if($alumni->address)<div class="admin-detail-item admin-detail-full"><span class="label">العنوان</span><span class="value">{{ $alumni->address }}</span></div>@endif
</div></div></div></div>
@stop
