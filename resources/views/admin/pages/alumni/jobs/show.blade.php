@extends('admin.layouts.master')
@section('page-title') تفاصيل الوظيفة @stop
@section('content')
<div class="main-content app-content"><div class="container-fluid">
<div class="admin-page-header">
    <div class="page-title-wrap"><h1>{{ $job->title }}</h1><p>{{ $job->company }}</p></div>
    @can('job-posting-edit')
        <a href="{{ route('admin.job-postings.edit', $job) }}" class="admin-btn admin-btn-primary"><i class="ri-edit-line"></i> تعديل</a>
    @endcan
</div>
<div class="admin-page-card"><div class="admin-detail-grid">
    <div class="admin-detail-item"><span class="label">نوع التوظيف</span><span class="value">{{ $employmentTypes[$job->employment_type] ?? $job->employment_type }}</span></div>
    <div class="admin-detail-item"><span class="label">المكان</span><span class="value">{{ $job->location ?? '—' }}</span></div>
    <div class="admin-detail-item"><span class="label">الراتب</span><span class="value">{{ $job->salary_range ?? '—' }}</span></div>
    <div class="admin-detail-item"><span class="label">آخر موعد</span><span class="value">{{ $job->application_deadline?->format('Y-m-d') ?? '—' }}</span></div>
    <div class="admin-detail-item"><span class="label">التواصل</span><span class="value">{{ $job->contact_email ?? '—' }} / {{ $job->contact_phone ?? '—' }}</span></div>
    <div class="admin-detail-item"><span class="label">نشرها</span><span class="value">{{ $job->poster->name ?? '—' }}</span></div>
    <div class="admin-detail-item admin-detail-full"><span class="label">الوصف</span><span class="value">{{ $job->description }}</span></div>
</div></div></div></div>
@stop
