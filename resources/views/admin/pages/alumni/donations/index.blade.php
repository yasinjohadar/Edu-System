@extends('admin.layouts.master')
@section('page-title') التبرعات @stop
@section('content')
<div class="main-content app-content"><div class="container-fluid">
@include('admin.partials.flash-alerts')
<div class="admin-page-header">
    <div class="page-title-wrap"><h1>التبرعات</h1><p>تسجيل تبرعات الخريجين</p></div>
    @can('alumni-donation-create')
        <a href="{{ route('admin.alumni-donations.create') }}" class="admin-btn admin-btn-primary"><i class="ri-add-line"></i> إضافة تبرع</a>
    @endcan
</div>
<div class="admin-page-card">
<form method="GET" class="admin-filter-bar mb-3"><div class="row g-2 align-items-end">
    <div class="col-md-4"><select name="alumni_id" class="form-select"><option value="">كل الخريجين</option>@foreach($alumniList as $a)<option value="{{ $a->id }}" {{ request('alumni_id')==$a->id?'selected':'' }}>{{ $a->name }}</option>@endforeach</select></div>
    <div class="col-md-3"><select name="status" class="form-select"><option value="">كل الحالات</option>@foreach($statuses as $k=>$l)<option value="{{ $k }}" {{ request('status')===$k?'selected':'' }}>{{ $l }}</option>@endforeach</select></div>
    <div class="col-md-auto"><button type="submit" class="admin-btn admin-btn-secondary"><i class="ri-search-line"></i> بحث</button></div>
</div></form>
<div class="table-responsive"><table class="admin-data-table">
<thead><tr><th>#</th><th>الخريج</th><th>المبلغ</th><th>الدفع</th><th>التاريخ</th><th>الحالة</th><th>العمليات</th></tr></thead>
<tbody>
@forelse($donations as $donation)
<tr>
<th class="row-number">{{ $donations->firstItem() + $loop->index }}</th>
<td>{{ $donation->alumni->name ?? '—' }}</td>
<td><strong>{{ number_format($donation->amount, 2) }} ر.س</strong></td>
<td>{{ $paymentMethods[$donation->payment_method] ?? $donation->payment_method }}</td>
<td>{{ $donation->donation_date?->format('Y-m-d') }}</td>
<td><span class="admin-badge {{ $donation->status === 'completed' ? 'admin-badge-success' : ($donation->status === 'failed' ? 'admin-badge-danger' : 'admin-badge-warning') }}">{{ $statuses[$donation->status] ?? $donation->status }}</span></td>
<td><div class="admin-action-group">
@can('alumni-donation-show')
<a href="{{ route('admin.alumni-donations.show', $donation) }}" class="admin-action-btn admin-action-view"><i class="ri-eye-line"></i></a>
@endcan
@can('alumni-donation-edit')
<a href="{{ route('admin.alumni-donations.edit', $donation) }}" class="admin-action-btn admin-action-edit"><i class="ri-edit-line"></i></a>
@endcan
@can('alumni-donation-delete')
<button type="button" class="admin-action-btn admin-action-delete" data-delete-url="{{ route('admin.alumni-donations.destroy', $donation) }}" data-delete-message="حذف سجل التبرع؟"><i class="ri-delete-bin-line"></i></button>
@endcan
</div></td></tr>
@empty<tr><td colspan="7"><div class="admin-empty-state"><i class="ri-hand-heart-line"></i> لا توجد تبرعات</div></td></tr>
@endforelse
</tbody></table></div>
<div class="admin-table-footer mt-3"><div class="admin-pagination">{{ $donations->links() }}</div></div>
</div></div></div>
@include('admin.components.delete-modal')
@stop
