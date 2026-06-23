@extends('admin.layouts.master')
@section('page-title') تسجيل نقل طالب @stop
@section('content')
<div class="main-content app-content"><div class="container-fluid">
@include('admin.partials.flash-alerts')
<div class="admin-page-header">
    <div class="page-title-wrap"><h1>تسجيل نقل طالب</h1></div>
    <a href="{{ route('admin.student-transports.index') }}" class="admin-btn admin-btn-secondary"><i class="ri-arrow-right-line"></i> العودة</a>
</div>
<div class="admin-page-card">
<form action="{{ route('admin.student-transports.store') }}" method="POST" class="admin-form" id="student-transport-form">@csrf
<div class="admin-form-body">@include('admin.pages.transport.student-transports._form')</div>
<div class="admin-form-footer"><button type="submit" class="admin-btn admin-btn-primary"><i class="ri-save-line"></i> حفظ</button></div>
</form></div></div></div>
@stop
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    AdminTables.initAdminForm('#student-transport-form');
    const routeSelect = document.getElementById('route_id');
    const stopSelect = document.getElementById('stop_id');
    if (!routeSelect || !stopSelect) return;

    const filterStops = () => {
        const routeId = routeSelect.value;
        let hasVisible = false;
        Array.from(stopSelect.options).forEach((opt, i) => {
            if (i === 0) return;
            const match = !routeId || opt.dataset.routeId === routeId;
            opt.hidden = !match;
            opt.disabled = !match;
            if (match) hasVisible = true;
            if (!match && opt.selected) opt.selected = false;
        });
        if (!hasVisible) stopSelect.value = '';
    };

    routeSelect.addEventListener('change', filterStops);
    filterStops();
});
</script>
@endpush
