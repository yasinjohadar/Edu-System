@extends('admin.layouts.master')
@section('page-title') تفاصيل التبرع @stop
@section('content')
<div class="main-content app-content"><div class="container-fluid">
<div class="admin-page-header">
    <div class="page-title-wrap"><h1>{{ number_format($donation->amount, 2) }} ر.س</h1><p>{{ $donation->alumni->name ?? '' }}</p></div>
    @can('alumni-donation-edit')
        <a href="{{ route('admin.alumni-donations.edit', $donation) }}" class="admin-btn admin-btn-primary"><i class="ri-edit-line"></i> تعديل</a>
    @endcan
</div>
<div class="admin-page-card"><div class="admin-detail-grid">
    <div class="admin-detail-item"><span class="label">رقم المرجع</span><span class="value"><code>{{ $donation->reference_number ?? '—' }}</code></span></div>
    <div class="admin-detail-item"><span class="label">طريقة الدفع</span><span class="value">{{ $paymentMethods[$donation->payment_method] ?? $donation->payment_method }}</span></div>
    <div class="admin-detail-item"><span class="label">التاريخ</span><span class="value">{{ $donation->donation_date?->format('Y-m-d') }}</span></div>
    <div class="admin-detail-item"><span class="label">الحالة</span><span class="value">{{ $statuses[$donation->status] ?? $donation->status }}</span></div>
    @if($donation->purpose)<div class="admin-detail-item admin-detail-full"><span class="label">الغرض</span><span class="value">{{ $donation->purpose }}</span></div>@endif
    @if($donation->notes)<div class="admin-detail-item admin-detail-full"><span class="label">ملاحظات</span><span class="value">{{ $donation->notes }}</span></div>@endif
</div></div></div></div>
@stop
