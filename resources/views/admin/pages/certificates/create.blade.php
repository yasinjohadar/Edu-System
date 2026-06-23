@extends('admin.layouts.master')
@section('page-title') إضافة شهادة @stop
@section('content')
<div class="main-content app-content"><div class="container-fluid">
@include('admin.partials.flash-alerts')
<div class="admin-page-header">
    <div class="page-title-wrap"><h1>إضافة شهادة</h1><p>إصدار شهادة جديدة لطالب</p></div>
    <a href="{{ route('admin.certificates.index') }}" class="admin-btn admin-btn-secondary"><i class="ri-arrow-right-line"></i> العودة</a>
</div>
<div class="admin-page-card">
<form action="{{ route('admin.certificates.store') }}" method="POST" class="admin-form" id="certificate-form">@csrf
<div class="admin-form-body">@include('admin.pages.certificates._form')</div>
<div class="admin-form-footer">
<button type="submit" class="admin-btn admin-btn-primary"><i class="ri-save-line"></i> حفظ</button>
<a href="{{ route('admin.certificates.index') }}" class="admin-btn admin-btn-secondary">إلغاء</a>
</div>
</form></div></div></div>
@stop
@push('scripts')<script>document.addEventListener('DOMContentLoaded',()=>AdminTables.initAdminForm('#certificate-form'));</script>@endpush
