@extends('admin.layouts.master')
@section('page-title') تعديل تبرع @stop
@section('content')
<div class="main-content app-content"><div class="container-fluid">
@include('admin.partials.flash-alerts')
<div class="admin-page-header">
    <div class="page-title-wrap"><h1>تعديل التبرع</h1></div>
    <a href="{{ route('admin.alumni-donations.index') }}" class="admin-btn admin-btn-secondary"><i class="ri-arrow-right-line"></i> العودة</a>
</div>
<div class="admin-page-card">
<form action="{{ route('admin.alumni-donations.update', $donation) }}" method="POST" class="admin-form" id="donation-form">@csrf @method('PUT')
<div class="admin-form-body">@include('admin.pages.alumni.donations._form', ['donation' => $donation, 'alumniList' => $alumniList, 'statuses' => $statuses, 'paymentMethods' => $paymentMethods])</div>
<div class="admin-form-footer"><button type="submit" class="admin-btn admin-btn-primary"><i class="ri-save-line"></i> حفظ</button></div>
</form></div></div></div>
@stop
@push('scripts')<script>document.addEventListener('DOMContentLoaded',()=>AdminTables.initAdminForm('#donation-form'));</script>@endpush
