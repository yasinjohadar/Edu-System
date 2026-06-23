@extends('admin.layouts.master')
@section('page-title') المشرفون @stop
@section('content')
@php $statuses = ['active' => 'نشط', 'inactive' => 'غير نشط']; @endphp
<div class="main-content app-content"><div class="container-fluid">
@include('admin.partials.flash-alerts')
<div class="admin-page-header">
    <div class="page-title-wrap"><h1>مشرفو الحافلات</h1></div>
    @can('supervisor-create')
        <a href="{{ route('admin.supervisors.create') }}" class="admin-btn admin-btn-primary"><i class="ri-add-line"></i> إضافة مشرف</a>
    @endcan
</div>
<div class="admin-page-card"><div class="table-responsive"><table class="admin-data-table">
<thead><tr><th>#</th><th>الاسم</th><th>رقم المشرف</th><th>الهاتف</th><th>الحالة</th><th>العمليات</th></tr></thead>
<tbody>
@forelse($supervisors as $supervisor)
<tr>
<th class="row-number">{{ $supervisors->firstItem() + $loop->index }}</th>
<td>{{ $supervisor->user->name ?? '—' }}</td>
<td>{{ $supervisor->supervisor_code }}</td>
<td>{{ $supervisor->phone ?? '—' }}</td>
<td>{{ $statuses[$supervisor->status] ?? $supervisor->status }}</td>
<td><div class="admin-action-group">
@can('supervisor-show')
<a href="{{ route('admin.supervisors.show', $supervisor) }}" class="admin-action-btn admin-action-view"><i class="ri-eye-line"></i></a>
@endcan
@can('supervisor-edit')
<a href="{{ route('admin.supervisors.edit', $supervisor) }}" class="admin-action-btn admin-action-edit"><i class="ri-edit-line"></i></a>
@endcan
@can('supervisor-delete')
<button type="button" class="admin-action-btn admin-action-delete" data-delete-url="{{ route('admin.supervisors.destroy', $supervisor) }}" data-delete-message="حذف المشرف <strong>{{ $supervisor->supervisor_code }}</strong>؟"><i class="ri-delete-bin-line"></i></button>
@endcan
</div></td></tr>
@empty<tr><td colspan="6"><div class="admin-empty-state">لا يوجد مشرفون</div></td></tr>
@endforelse
</tbody></table></div>
<div class="mt-3">{{ $supervisors->links() }}</div>
</div></div></div>
@include('admin.components.delete-modal')
@stop
