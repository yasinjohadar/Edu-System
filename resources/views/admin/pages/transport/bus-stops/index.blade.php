@extends('admin.layouts.master')
@section('page-title') محطات الحافلات @stop
@section('content')
<div class="main-content app-content"><div class="container-fluid">
@include('admin.partials.flash-alerts')
<div class="admin-page-header">
    <div class="page-title-wrap"><h1>محطات الحافلات</h1></div>
    @can('bus-stop-create')
        <a href="{{ route('admin.bus-stops.create') }}" class="admin-btn admin-btn-primary"><i class="ri-add-line"></i> إضافة محطة</a>
    @endcan
</div>
<div class="admin-page-card">
<div class="table-responsive"><table class="admin-data-table">
<thead><tr><th>#</th><th>المحطة</th><th>المسار</th><th>الترتيب</th><th>العمليات</th></tr></thead>
<tbody>
@forelse($stops as $stop)
<tr>
<th class="row-number">{{ $stops->firstItem() + $loop->index }}</th>
<td>{{ $stop->stop_name }}</td>
<td>{{ $stop->route->route_name ?? '—' }}</td>
<td>{{ $stop->order }}</td>
<td><div class="admin-action-group">
@can('bus-stop-show')
<a href="{{ route('admin.bus-stops.show', $stop) }}" class="admin-action-btn admin-action-view"><i class="ri-eye-line"></i></a>
@endcan
@can('bus-stop-edit')
<a href="{{ route('admin.bus-stops.edit', $stop) }}" class="admin-action-btn admin-action-edit"><i class="ri-edit-line"></i></a>
@endcan
@can('bus-stop-delete')
<button type="button" class="admin-action-btn admin-action-delete" data-delete-url="{{ route('admin.bus-stops.destroy', $stop) }}" data-delete-message="حذف المحطة <strong>{{ $stop->stop_name }}</strong>؟"><i class="ri-delete-bin-line"></i></button>
@endcan
</div></td></tr>
@empty<tr><td colspan="5"><div class="admin-empty-state">لا توجد محطات</div></td></tr>
@endforelse
</tbody></table></div>
<div class="mt-3">{{ $stops->links() }}</div>
</div></div></div>
@include('admin.components.delete-modal')
@stop
