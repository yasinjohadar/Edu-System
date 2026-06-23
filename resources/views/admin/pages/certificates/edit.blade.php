@extends('admin.layouts.master')
@section('page-title') تعديل شهادة @stop
@section('content')
<div class="main-content app-content"><div class="container-fluid">
@include('admin.partials.flash-alerts')
<div class="admin-page-header">
    <div class="page-title-wrap"><h1>تعديل الشهادة</h1><p>{{ $certificate->certificate_number }}</p></div>
    <a href="{{ route('admin.certificates.index') }}" class="admin-btn admin-btn-secondary"><i class="ri-arrow-right-line"></i> العودة</a>
</div>
<div class="admin-page-card">
<form action="{{ route('admin.certificates.update', $certificate) }}" method="POST" class="admin-form" id="certificate-form">@csrf @method('PUT')
<div class="admin-form-body">@include('admin.pages.certificates._form', ['certificate' => $certificate])</div>
<div class="admin-form-footer"><button type="submit" class="admin-btn admin-btn-primary"><i class="ri-save-line"></i> حفظ</button></div>
</form></div></div></div>
@stop
@push('scripts')<script>document.addEventListener('DOMContentLoaded',()=>AdminTables.initAdminForm('#certificate-form'));</script>@endpush
