@extends('admin.layouts.master')
@section('page-title') نقل الطلاب @stop
@section('content')
@php $statuses = ['active' => 'نشط', 'inactive' => 'غير نشط', 'suspended' => 'موقوف']; @endphp
<div class="main-content app-content"><div class="container-fluid">
@include('admin.partials.flash-alerts')
<div class="admin-page-header">
    <div class="page-title-wrap"><h1>نقل الطلاب</h1><p>ربط الطلاب بمسارات الحافلات</p></div>
    @can('student-transport-create')
        <a href="{{ route('admin.student-transports.create') }}" class="admin-btn admin-btn-primary"><i class="ri-add-line"></i> تسجيل نقل</a>
    @endcan
</div>
<div class="admin-page-card"><div class="table-responsive"><table class="admin-data-table">
<thead><tr><th>#</th><th>الطالب</th><th>المسار</th><th>المحطة</th><th>الحالة</th><th>العمليات</th></tr></thead>
<tbody>
@forelse($transports as $transport)
<tr>
<th class="row-number">{{ $transports->firstItem() + $loop->index }}</th>
<td>{{ $transport->student->user->name ?? $transport->student->student_code }}</td>
<td>{{ $transport->route->route_name ?? '—' }}</td>
<td>{{ $transport->stop->stop_name ?? '—' }}</td>
<td>{{ $statuses[$transport->status] ?? $transport->status }}</td>
<td><div class="admin-action-group">
@can('student-transport-show')
<a href="{{ route('admin.student-transports.show', $transport) }}" class="admin-action-btn admin-action-view"><i class="ri-eye-line"></i></a>
@endcan
@can('student-transport-edit')
<a href="{{ route('admin.student-transports.edit', $transport) }}" class="admin-action-btn admin-action-edit"><i class="ri-edit-line"></i></a>
@endcan
@can('student-transport-delete')
<button type="button" class="admin-action-btn admin-action-delete" data-delete-url="{{ route('admin.student-transports.destroy', $transport) }}" data-delete-message="حذف سجل النقل لهذا الطالب؟"><i class="ri-delete-bin-line"></i></button>
@endcan
</div></td></tr>
@empty<tr><td colspan="6"><div class="admin-empty-state">لا توجد سجلات نقل</div></td></tr>
@endforelse
</tbody></table></div>
<div class="mt-3">{{ $transports->links() }}</div>
</div></div></div>
@include('admin.components.delete-modal')
@stop
