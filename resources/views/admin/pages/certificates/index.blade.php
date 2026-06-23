@extends('admin.layouts.master')
@section('page-title') الشهادات @stop
@section('content')
<div class="main-content app-content"><div class="container-fluid">
@include('admin.partials.flash-alerts')
<div class="admin-page-header">
    <div class="page-title-wrap"><h1>الشهادات</h1><p>إدارة الشهادات الصادرة للطلاب</p></div>
    @can('certificate-create')
        <a href="{{ route('admin.certificates.create') }}" class="admin-btn admin-btn-primary"><i class="ri-add-line"></i> إضافة شهادة</a>
    @endcan
</div>
<div class="admin-page-card">
    <form method="GET" class="admin-filter-bar mb-3">
        <div class="row g-2 align-items-end">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="بحث برقم الشهادة أو الطالب..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="type" class="form-select">
                    <option value="">كل الأنواع</option>
                    @foreach($types as $key => $label)
                        <option value="{{ $key }}" {{ request('type') === $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-auto">
                <button type="submit" class="admin-btn admin-btn-secondary"><i class="ri-search-line"></i> بحث</button>
            </div>
        </div>
    </form>
    <div class="table-responsive"><table class="admin-data-table">
        <thead><tr><th>#</th><th>رقم الشهادة</th><th>الطالب</th><th>النوع</th><th>القالب</th><th>تاريخ الإصدار</th><th>التحقق</th><th>العمليات</th></tr></thead>
        <tbody>
        @forelse($certificates as $certificate)
        <tr>
            <th class="row-number">{{ $certificates->firstItem() + $loop->index }}</th>
            <td><span class="admin-badge admin-badge-muted">{{ $certificate->certificate_number }}</span></td>
            <td>{{ $certificate->student->user->name ?? '—' }}</td>
            <td>{{ $types[$certificate->type] ?? $certificate->type }}</td>
            <td>{{ $certificate->template->name ?? '—' }}</td>
            <td>{{ $certificate->issue_date?->format('Y-m-d') }}</td>
            <td><span class="admin-badge {{ $certificate->is_verified ? 'admin-badge-success' : 'admin-badge-warning' }}">{{ $certificate->is_verified ? 'موثّقة' : 'غير موثّقة' }}</span></td>
            <td><div class="admin-action-group">
                @can('certificate-show')
                <a href="{{ route('admin.certificates.show', $certificate) }}" class="admin-action-btn admin-action-view" title="عرض"><i class="ri-eye-line"></i></a>
                @endcan
                @can('certificate-edit')
                <a href="{{ route('admin.certificates.edit', $certificate) }}" class="admin-action-btn admin-action-edit" title="تعديل"><i class="ri-edit-line"></i></a>
                @endcan
                @can('certificate-delete')
                <button type="button" class="admin-action-btn admin-action-delete" title="حذف"
                    data-delete-url="{{ route('admin.certificates.destroy', $certificate) }}"
                    data-delete-message="حذف الشهادة <strong>{{ $certificate->certificate_number }}</strong>؟"><i class="ri-delete-bin-line"></i></button>
                @endcan
            </div></td>
        </tr>
        @empty
        <tr><td colspan="8"><div class="admin-empty-state"><i class="ri-award-line"></i> لا توجد شهادات</div></td></tr>
        @endforelse
        </tbody>
    </table></div>
    <div class="admin-table-footer mt-3"><div class="admin-pagination">{{ $certificates->links() }}</div></div>
</div></div></div>
@include('admin.components.delete-modal')
@stop
