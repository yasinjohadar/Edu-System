@extends('admin.layouts.master')
@section('page-title') إضافة سائق @stop
@section('content')
<div class="main-content app-content"><div class="container-fluid">
@include('admin.partials.flash-alerts')
<div class="admin-page-header">
    <div class="page-title-wrap"><h1>إضافة سائق</h1></div>
    <a href="{{ route('admin.drivers.index') }}" class="admin-btn admin-btn-secondary"><i class="ri-arrow-right-line"></i> العودة</a>
</div>
<div class="admin-page-card">
<form action="{{ route('admin.drivers.store') }}" method="POST" class="admin-form" id="driver-form">@csrf
<div class="admin-form-body">@include('admin.pages.transport.drivers._form')</div>
<div class="admin-form-footer"><button type="submit" class="admin-btn admin-btn-primary"><i class="ri-save-line"></i> حفظ</button></div>
</form></div></div></div>
@stop
@push('scripts')<script>document.addEventListener('DOMContentLoaded',()=>AdminTables.initAdminForm('#driver-form'));</script>@endpush
