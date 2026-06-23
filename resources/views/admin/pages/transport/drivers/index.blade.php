@extends('admin.layouts.master')
@section('page-title') السائقون @stop
@section('content')
@php $statuses = ['active' => 'نشط', 'inactive' => 'غير نشط', 'on_leave' => 'في إجازة']; @endphp
<div class="main-content app-content"><div class="container-fluid">
@include('admin.partials.flash-alerts')
<div class="admin-page-header">
    <div class="page-title-wrap"><h1>السائقون</h1></div>
    @can('driver-create')
        <a href="{{ route('admin.drivers.create') }}" class="admin-btn admin-btn-primary"><i class="ri-add-line"></i> إضافة سائق</a>
    @endcan
</div>
<div class="admin-page-card"><div class="table-responsive"><table class="admin-data-table">
<thead><tr><th>#</th><th>الاسم</th><th>رقم السائق</th><th>الرخصة</th><th>الحالة</th><th>العمليات</th></tr></thead>
<tbody>
@forelse($drivers as $driver)
<tr>
<th class="row-number">{{ $drivers->firstItem() + $loop->index }}</th>
<td>{{ $driver->user->name ?? '—' }}</td>
<td>{{ $driver->driver_code }}</td>
<td>{{ $driver->license_number }}</td>
<td>{{ $statuses[$driver->status] ?? $driver->status }}</td>
<td><div class="admin-action-group">
@can('driver-show')
<a href="{{ route('admin.drivers.show', $driver) }}" class="admin-action-btn admin-action-view"><i class="ri-eye-line"></i></a>
@endcan
@can('driver-edit')
<a href="{{ route('admin.drivers.edit', $driver) }}" class="admin-action-btn admin-action-edit"><i class="ri-edit-line"></i></a>
@endcan
@can('driver-delete')
<button type="button" class="admin-action-btn admin-action-delete" data-delete-url="{{ route('admin.drivers.destroy', $driver) }}" data-delete-message="حذف السائق <strong>{{ $driver->driver_code }}</strong>؟"><i class="ri-delete-bin-line"></i></button>
@endcan
</div></td></tr>
@empty<tr><td colspan="6"><div class="admin-empty-state">لا يوجد سائقون</div></td></tr>
@endforelse
</tbody></table></div>
<div class="mt-3">{{ $drivers->links() }}</div>
</div></div></div>
@include('admin.components.delete-modal')
@stop
