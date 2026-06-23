@extends('admin.layouts.master')
@section('page-title') تفاصيل الشهادة @stop
@section('content')
<div class="main-content app-content"><div class="container-fluid">
<div class="admin-page-header">
    <div class="page-title-wrap"><h1>{{ $certificate->certificate_number }}</h1><p>{{ $types[$certificate->type] ?? $certificate->type }}</p></div>
    <div class="d-flex gap-2">
        @can('certificate-edit')
            <a href="{{ route('admin.certificates.edit', $certificate) }}" class="admin-btn admin-btn-primary"><i class="ri-edit-line"></i> تعديل</a>
        @endcan
        <a href="{{ route('admin.certificates.index') }}" class="admin-btn admin-btn-secondary"><i class="ri-arrow-right-line"></i> العودة</a>
    </div>
</div>
<div class="admin-page-card"><div class="admin-detail-grid">
    <div class="admin-detail-item"><span class="label">الطالب</span><span class="value">{{ $certificate->student->user->name ?? '—' }}</span></div>
    <div class="admin-detail-item"><span class="label">القالب</span><span class="value">{{ $certificate->template->name ?? '—' }}</span></div>
    <div class="admin-detail-item"><span class="label">تاريخ الإصدار</span><span class="value">{{ $certificate->issue_date?->format('Y-m-d') }}</span></div>
    <div class="admin-detail-item"><span class="label">رمز التحقق</span><span class="value"><code>{{ $certificate->verification_code }}</code></span></div>
    <div class="admin-detail-item"><span class="label">الحالة</span><span class="value">{{ $certificate->is_verified ? 'موثّقة' : 'غير موثّقة' }}</span></div>
    <div class="admin-detail-item"><span class="label">أصدرها</span><span class="value">{{ $certificate->issuer->name ?? '—' }}</span></div>
    @if(is_array($certificate->data) && !empty($certificate->data['notes']))
    <div class="admin-detail-item admin-detail-full"><span class="label">ملاحظات</span><span class="value">{{ $certificate->data['notes'] }}</span></div>
    @endif
</div></div></div></div>
@stop
