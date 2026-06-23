@extends('admin.layouts.master')
@section('page-title') تفاصيل القالب @stop
@section('content')
<div class="main-content app-content"><div class="container-fluid">
<div class="admin-page-header">
    <div class="page-title-wrap"><h1>{{ $template->name }}</h1><p>{{ $types[$template->type] ?? $template->type }}</p></div>
    @can('certificate-template-edit')
        <a href="{{ route('admin.certificate-templates.edit', $template) }}" class="admin-btn admin-btn-primary"><i class="ri-edit-line"></i> تعديل</a>
    @endcan
</div>
<div class="admin-page-card"><div class="admin-detail-grid">
    <div class="admin-detail-item"><span class="label">الحالة</span><span class="value">{{ $template->is_active ? 'نشط' : 'غير نشط' }}</span></div>
    <div class="admin-detail-item"><span class="label">أنشأه</span><span class="value">{{ $template->creator->name ?? '—' }}</span></div>
    <div class="admin-detail-item"><span class="label">عدد الشهادات</span><span class="value">{{ $template->certificates->count() }}</span></div>
    @if($template->background_image)
    <div class="admin-detail-item admin-detail-full"><span class="label">صورة الخلفية</span><span class="value"><img src="{{ asset('storage/'.$template->background_image) }}" alt="" style="max-height:120px;border-radius:8px;"></span></div>
    @endif
    <div class="admin-detail-item admin-detail-full"><span class="label">معاينة HTML</span><div class="value border rounded p-3 mt-1 bg-light">{!! $template->html_template !!}</div></div>
</div></div></div></div>
@stop
