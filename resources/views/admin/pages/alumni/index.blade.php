@extends('admin.layouts.master')
@section('page-title') الخريجون @stop
@section('content')
<div class="main-content app-content"><div class="container-fluid">
@include('admin.partials.flash-alerts')
<div class="admin-page-header">
    <div class="page-title-wrap"><h1>الخريجون</h1><p>سجل الخريجين ومتابعة بياناتهم</p></div>
    @can('alumni-create')
        <a href="{{ route('admin.alumni.create') }}" class="admin-btn admin-btn-primary"><i class="ri-add-line"></i> إضافة خريج</a>
    @endcan
</div>
<div class="admin-page-card">
<form method="GET" class="admin-filter-bar mb-3"><div class="row g-2 align-items-end">
    <div class="col-md-4"><input type="text" name="search" class="form-control" placeholder="بحث بالاسم أو البريد..." value="{{ request('search') }}"></div>
    <div class="col-md-3"><select name="is_active" class="form-select"><option value="">كل الحالات</option><option value="1" {{ request('is_active')==='1'?'selected':'' }}>نشط</option><option value="0" {{ request('is_active')==='0'?'selected':'' }}>غير نشط</option></select></div>
    <div class="col-md-auto"><button type="submit" class="admin-btn admin-btn-secondary"><i class="ri-search-line"></i> بحث</button></div>
</div></form>
<div class="table-responsive"><table class="admin-data-table">
<thead><tr><th>#</th><th>الاسم</th><th>البريد</th><th>التخرج</th><th>الوظيفة</th><th>الحالة</th><th>العمليات</th></tr></thead>
<tbody>
@forelse($alumni as $alumnus)
<tr>
<th class="row-number">{{ $alumni->firstItem() + $loop->index }}</th>
<td><strong>{{ $alumnus->name }}</strong></td>
<td>{{ $alumnus->email }}</td>
<td>{{ $alumnus->graduation_date?->format('Y-m-d') }}</td>
<td>{{ $alumnus->current_job ?? '—' }}</td>
<td><span class="admin-badge {{ $alumnus->is_active ? 'admin-badge-success' : 'admin-badge-danger' }}">{{ $alumnus->is_active ? 'نشط' : 'غير نشط' }}</span></td>
<td><div class="admin-action-group">
@can('alumni-show')
<a href="{{ route('admin.alumni.show', $alumnus) }}" class="admin-action-btn admin-action-view"><i class="ri-eye-line"></i></a>
@endcan
@can('alumni-edit')
<a href="{{ route('admin.alumni.edit', $alumnus) }}" class="admin-action-btn admin-action-edit"><i class="ri-edit-line"></i></a>
@endcan
@can('alumni-delete')
<button type="button" class="admin-action-btn admin-action-delete" data-delete-url="{{ route('admin.alumni.destroy', $alumnus) }}" data-delete-message="حذف الخريج <strong>{{ $alumnus->name }}</strong>؟"><i class="ri-delete-bin-line"></i></button>
@endcan
</div></td></tr>
@empty<tr><td colspan="7"><div class="admin-empty-state"><i class="ri-graduation-cap-line"></i> لا يوجد خريجون</div></td></tr>
@endforelse
</tbody></table></div>
<div class="admin-table-footer mt-3"><div class="admin-pagination">{{ $alumni->links() }}</div></div>
</div></div></div>
@include('admin.components.delete-modal')
@stop
