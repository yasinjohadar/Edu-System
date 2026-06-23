@extends('admin.layouts.master')
@section('page-title') إضافة حدث @stop
@section('content')
<div class="main-content app-content"><div class="container-fluid">
@include('admin.partials.flash-alerts')
<div class="admin-page-header">
    <div class="page-title-wrap"><h1>إضافة حدث</h1></div>
    <a href="{{ route('admin.alumni-events.index') }}" class="admin-btn admin-btn-secondary"><i class="ri-arrow-right-line"></i> العودة</a>
</div>
<div class="admin-page-card">
<form action="{{ route('admin.alumni-events.store') }}" method="POST" class="admin-form" id="event-form">@csrf
<div class="admin-form-body">@include('admin.pages.alumni.events._form', ['types' => $types])</div>
<div class="admin-form-footer"><button type="submit" class="admin-btn admin-btn-primary"><i class="ri-save-line"></i> حفظ</button></div>
</form></div></div></div>
@stop
@push('scripts')<script>document.addEventListener('DOMContentLoaded',()=>AdminTables.initAdminForm('#event-form'));</script>@endpush
