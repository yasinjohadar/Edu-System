@extends('admin.layouts.master')
@section('page-title') قوالب الشهادات @stop
@section('content')
<div class="main-content app-content"><div class="container-fluid">
@include('admin.partials.flash-alerts')
<div class="admin-page-header">
    <div class="page-title-wrap"><h1>قوالب الشهادات</h1><p>تصميم وإدارة قوالب الشهادات</p></div>
    @can('certificate-template-create')
        <a href="{{ route('admin.certificate-templates.create') }}" class="admin-btn admin-btn-primary"><i class="ri-add-line"></i> إضافة قالب</a>
    @endcan
</div>
<div class="admin-page-card"><div class="table-responsive"><table class="admin-data-table">
<thead><tr><th>#</th><th>اسم القالب</th><th>النوع</th><th>الشهادات</th><th>الحالة</th><th>العمليات</th></tr></thead>
<tbody>
@forelse($templates as $template)
<tr>
<th class="row-number">{{ $templates->firstItem() + $loop->index }}</th>
<td><strong>{{ $template->name }}</strong></td>
<td>{{ $types[$template->type] ?? $template->type }}</td>
<td>{{ $template->certificates_count }}</td>
<td><span class="admin-badge {{ $template->is_active ? 'admin-badge-success' : 'admin-badge-danger' }}">{{ $template->is_active ? 'نشط' : 'غير نشط' }}</span></td>
<td><div class="admin-action-group">
@can('certificate-template-show')
<a href="{{ route('admin.certificate-templates.show', $template) }}" class="admin-action-btn admin-action-view"><i class="ri-eye-line"></i></a>
@endcan
@can('certificate-template-edit')
<a href="{{ route('admin.certificate-templates.edit', $template) }}" class="admin-action-btn admin-action-edit"><i class="ri-edit-line"></i></a>
@endcan
@can('certificate-template-delete')
<button type="button" class="admin-action-btn admin-action-delete"
    data-delete-url="{{ route('admin.certificate-templates.destroy', $template) }}"
    data-delete-message="حذف القالب <strong>{{ $template->name }}</strong>؟"><i class="ri-delete-bin-line"></i></button>
@endcan
</div></td></tr>
@empty<tr><td colspan="6"><div class="admin-empty-state"><i class="ri-layout-masonry-line"></i> لا توجد قوالب</div></td></tr>
@endforelse
</tbody></table></div>
<div class="admin-table-footer mt-3"><div class="admin-pagination">{{ $templates->links() }}</div></div>
</div></div></div>
@include('admin.components.delete-modal')
@stop
