@extends('admin.layouts.master')
@section('page-title') تعديل مشرف @stop
@section('content')
<div class="main-content app-content"><div class="container-fluid">
@include('admin.partials.flash-alerts')
<div class="admin-page-header">
    <div class="page-title-wrap"><h1>تعديل المشرف</h1><p>{{ $supervisor->supervisor_code }}</p></div>
    <a href="{{ route('admin.supervisors.index') }}" class="admin-btn admin-btn-secondary"><i class="ri-arrow-right-line"></i> العودة</a>
</div>
<div class="admin-page-card">
<form action="{{ route('admin.supervisors.update', $supervisor) }}" method="POST" class="admin-form" id="supervisor-form">@csrf @method('PUT')
<div class="admin-form-body">@include('admin.pages.transport.supervisors._form', ['supervisor' => $supervisor])</div>
<div class="admin-form-footer"><button type="submit" class="admin-btn admin-btn-primary"><i class="ri-save-line"></i> حفظ</button></div>
</form></div></div></div>
@stop
@push('scripts')<script>document.addEventListener('DOMContentLoaded',()=>AdminTables.initAdminForm('#supervisor-form'));</script>@endpush
