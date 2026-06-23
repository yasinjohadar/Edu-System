@extends('admin.layouts.master')
@section('page-title') تفاصيل الحدث @stop
@section('content')
<div class="main-content app-content"><div class="container-fluid">
<div class="admin-page-header">
    <div class="page-title-wrap"><h1>{{ $event->title }}</h1><p>{{ $types[$event->type] ?? $event->type }}</p></div>
    @can('alumni-event-edit')
        <a href="{{ route('admin.alumni-events.edit', $event) }}" class="admin-btn admin-btn-primary"><i class="ri-edit-line"></i> تعديل</a>
    @endcan
</div>
<div class="admin-page-card"><div class="admin-detail-grid">
    <div class="admin-detail-item"><span class="label">التاريخ</span><span class="value">{{ $event->event_date?->format('Y-m-d') }} {{ $event->event_time ?? '' }}</span></div>
    <div class="admin-detail-item"><span class="label">المكان</span><span class="value">{{ $event->location ?? '—' }}</span></div>
    <div class="admin-detail-item"><span class="label">الرسوم</span><span class="value">{{ number_format($event->fee, 2) }} ر.س</span></div>
    <div class="admin-detail-item"><span class="label">الحد الأقصى</span><span class="value">{{ $event->max_attendees ?? '—' }}</span></div>
    <div class="admin-detail-item"><span class="label">أنشأه</span><span class="value">{{ $event->creator->name ?? '—' }}</span></div>
    @if($event->description)<div class="admin-detail-item admin-detail-full"><span class="label">الوصف</span><span class="value">{{ $event->description }}</span></div>@endif
</div></div></div></div>
@stop
