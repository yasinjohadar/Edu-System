@extends('admin.layouts.master')
@section('page-title') تعديل محطة @stop
@section('content')
<div class="main-content app-content"><div class="container-fluid">
@include('admin.partials.flash-alerts')
<div class="admin-page-header">
    <div class="page-title-wrap"><h1>تعديل المحطة</h1><p>{{ $busStop->stop_name }}</p></div>
    <a href="{{ route('admin.bus-stops.index') }}" class="admin-btn admin-btn-secondary"><i class="ri-arrow-right-line"></i> العودة</a>
</div>
<div class="admin-page-card">
<form action="{{ route('admin.bus-stops.update', $busStop) }}" method="POST" class="admin-form" id="bus-stop-form">
@csrf @method('PUT')
<div class="admin-form-body">@include('admin.pages.transport.bus-stops._form', ['busStop' => $busStop])</div>
<div class="admin-form-footer"><button type="submit" class="admin-btn admin-btn-primary"><i class="ri-save-line"></i> حفظ</button></div>
</form></div></div></div>
@stop
@push('scripts')<script>document.addEventListener('DOMContentLoaded',()=>AdminTables.initAdminForm('#bus-stop-form'));</script>@endpush
