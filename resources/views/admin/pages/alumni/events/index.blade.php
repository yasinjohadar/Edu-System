@extends('admin.layouts.master')
@section('page-title') أحداث الخريجين @stop
@section('content')
<div class="main-content app-content"><div class="container-fluid">
@include('admin.partials.flash-alerts')
<div class="admin-page-header">
    <div class="page-title-wrap"><h1>أحداث الخريجين</h1><p>تنظيم لقاءات وفعاليات الخريجين</p></div>
    @can('alumni-event-create')
        <a href="{{ route('admin.alumni-events.create') }}" class="admin-btn admin-btn-primary"><i class="ri-add-line"></i> إضافة حدث</a>
    @endcan
</div>
<div class="admin-page-card">
<form method="GET" class="admin-filter-bar mb-3"><div class="row g-2 align-items-end">
    <div class="col-md-4"><input type="text" name="search" class="form-control" placeholder="بحث بالعنوان..." value="{{ request('search') }}"></div>
    <div class="col-md-3"><select name="type" class="form-select"><option value="">كل الأنواع</option>@foreach($types as $k=>$l)<option value="{{ $k }}" {{ request('type')===$k?'selected':'' }}>{{ $l }}</option>@endforeach</select></div>
    <div class="col-md-auto"><button type="submit" class="admin-btn admin-btn-secondary"><i class="ri-search-line"></i> بحث</button></div>
</div></form>
<div class="table-responsive"><table class="admin-data-table">
<thead><tr><th>#</th><th>الحدث</th><th>التاريخ</th><th>المكان</th><th>النوع</th><th>الحالة</th><th>العمليات</th></tr></thead>
<tbody>
@forelse($events as $event)
<tr>
<th class="row-number">{{ $events->firstItem() + $loop->index }}</th>
<td><strong>{{ $event->title }}</strong></td>
<td>{{ $event->event_date?->format('Y-m-d') }}</td>
<td>{{ $event->location ?? '—' }}</td>
<td>{{ $types[$event->type] ?? $event->type }}</td>
<td><span class="admin-badge {{ $event->is_active ? 'admin-badge-success' : 'admin-badge-danger' }}">{{ $event->is_active ? 'نشط' : 'غير نشط' }}</span></td>
<td><div class="admin-action-group">
@can('alumni-event-show')
<a href="{{ route('admin.alumni-events.show', $event) }}" class="admin-action-btn admin-action-view"><i class="ri-eye-line"></i></a>
@endcan
@can('alumni-event-edit')
<a href="{{ route('admin.alumni-events.edit', $event) }}" class="admin-action-btn admin-action-edit"><i class="ri-edit-line"></i></a>
@endcan
@can('alumni-event-delete')
<button type="button" class="admin-action-btn admin-action-delete" data-delete-url="{{ route('admin.alumni-events.destroy', $event) }}" data-delete-message="حذف الحدث <strong>{{ $event->title }}</strong>؟"><i class="ri-delete-bin-line"></i></button>
@endcan
</div></td></tr>
@empty<tr><td colspan="7"><div class="admin-empty-state"><i class="ri-calendar-star-line"></i> لا توجد أحداث</div></td></tr>
@endforelse
</tbody></table></div>
<div class="admin-table-footer mt-3"><div class="admin-pagination">{{ $events->links() }}</div></div>
</div></div></div>
@include('admin.components.delete-modal')
@stop
