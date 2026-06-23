@extends('admin.layouts.master')
@section('page-title') تعديل وظيفة @stop
@section('content')
<div class="main-content app-content"><div class="container-fluid">
@include('admin.partials.flash-alerts')
<div class="admin-page-header">
    <div class="page-title-wrap"><h1>تعديل الوظيفة</h1><p>{{ $job->title }}</p></div>
    <a href="{{ route('admin.job-postings.index') }}" class="admin-btn admin-btn-secondary"><i class="ri-arrow-right-line"></i> العودة</a>
</div>
<div class="admin-page-card">
<form action="{{ route('admin.job-postings.update', $job) }}" method="POST" class="admin-form" id="job-form">@csrf @method('PUT')
<div class="admin-form-body">@include('admin.pages.alumni.jobs._form', ['job' => $job, 'employmentTypes' => $employmentTypes])</div>
<div class="admin-form-footer"><button type="submit" class="admin-btn admin-btn-primary"><i class="ri-save-line"></i> حفظ</button></div>
</form></div></div></div>
@stop
@push('scripts')<script>document.addEventListener('DOMContentLoaded',()=>AdminTables.initAdminForm('#job-form'));</script>@endpush
