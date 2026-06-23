@extends('admin.layouts.master')
@section('page-title') الوظائف المفتوحة @stop
@section('content')
<div class="main-content app-content"><div class="container-fluid">
@include('admin.partials.flash-alerts')
<div class="admin-page-header">
    <div class="page-title-wrap"><h1>الوظائف المفتوحة</h1><p>فرص عمل للخريجين</p></div>
    @can('job-posting-create')
        <a href="{{ route('admin.job-postings.create') }}" class="admin-btn admin-btn-primary"><i class="ri-add-line"></i> إضافة وظيفة</a>
    @endcan
</div>
<div class="admin-page-card">
<form method="GET" class="admin-filter-bar mb-3"><div class="row g-2 align-items-end">
    <div class="col-md-4"><input type="text" name="search" class="form-control" placeholder="بحث..." value="{{ request('search') }}"></div>
    <div class="col-md-3"><select name="employment_type" class="form-select"><option value="">كل الأنواع</option>@foreach($employmentTypes as $k=>$l)<option value="{{ $k }}" {{ request('employment_type')===$k?'selected':'' }}>{{ $l }}</option>@endforeach</select></div>
    <div class="col-md-auto"><button type="submit" class="admin-btn admin-btn-secondary"><i class="ri-search-line"></i> بحث</button></div>
</div></form>
<div class="table-responsive"><table class="admin-data-table">
<thead><tr><th>#</th><th>الوظيفة</th><th>الشركة</th><th>النوع</th><th>آخر موعد</th><th>الحالة</th><th>العمليات</th></tr></thead>
<tbody>
@forelse($jobs as $job)
<tr>
<th class="row-number">{{ $jobs->firstItem() + $loop->index }}</th>
<td><strong>{{ $job->title }}</strong><br><small class="text-muted">{{ $job->location ?? '' }}</small></td>
<td>{{ $job->company }}</td>
<td>{{ $employmentTypes[$job->employment_type] ?? $job->employment_type }}</td>
<td>{{ $job->application_deadline?->format('Y-m-d') ?? '—' }}</td>
<td><span class="admin-badge {{ $job->is_active ? 'admin-badge-success' : 'admin-badge-danger' }}">{{ $job->is_active ? 'نشط' : 'غير نشط' }}</span></td>
<td><div class="admin-action-group">
@can('job-posting-show')
<a href="{{ route('admin.job-postings.show', $job) }}" class="admin-action-btn admin-action-view"><i class="ri-eye-line"></i></a>
@endcan
@can('job-posting-edit')
<a href="{{ route('admin.job-postings.edit', $job) }}" class="admin-action-btn admin-action-edit"><i class="ri-edit-line"></i></a>
@endcan
@can('job-posting-delete')
<button type="button" class="admin-action-btn admin-action-delete" data-delete-url="{{ route('admin.job-postings.destroy', $job) }}" data-delete-message="حذف الوظيفة <strong>{{ $job->title }}</strong>؟"><i class="ri-delete-bin-line"></i></button>
@endcan
</div></td></tr>
@empty<tr><td colspan="7"><div class="admin-empty-state"><i class="ri-briefcase-line"></i> لا توجد وظائف</div></td></tr>
@endforelse
</tbody></table></div>
<div class="admin-table-footer mt-3"><div class="admin-pagination">{{ $jobs->links() }}</div></div>
</div></div></div>
@include('admin.components.delete-modal')
@stop
