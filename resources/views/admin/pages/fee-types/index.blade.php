@extends('admin.layouts.master')

@section('page-title')
    أنواع الرسوم
@stop

@section('content')
    @if (\Session::has('success'))
        <div class="alert alert-success">
            <ul>
                <li>{!! \Session::get('success') !!}</li>
            </ul>
        </div>
    @endif

    @if (\Session::has('error'))
        <div class="alert alert-danger">
            <ul>
                <li>{!! \Session::get('error') !!}</li>
            </ul>
        </div>
    @endif

    <!-- Start::app-content -->
    <div class="main-content app-content">
        <div class="container-fluid">
            <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
                <div class="my-auto">
                    <h5 class="page-title fs-21 mb-1">أنواع الرسوم</h5>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header align-items-center d-flex gap-3">
                            @can('fee-type-create')
                            <a href="{{ route('admin.fee-types.create') }}" class="btn btn-primary btn-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-left: 4px; vertical-align: middle;">
                                    <line x1="12" y1="5" x2="12" y2="19"></line>
                                    <line x1="5" y1="12" x2="19" y2="12"></line>
                                </svg>
                                إضافة نوع رسوم جديد
                            </a>
                            @endcan
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover align-middle table-nowrap mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>الرمز</th>
                                            <th>الاسم</th>
                                            <th>الفئة</th>
                                            <th>المبلغ الافتراضي</th>
                                            <th>متكرر</th>
                                            <th>الحالة</th>
                                            <th>العمليات</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($feeTypes as $feeType)
                                            <tr>
                                                <td>{{ $feeType->id }}</td>
                                                <td><strong>{{ $feeType->code }}</strong></td>
                                                <td>{{ $feeType->name }}</td>
                                                <td>
                                                    <span class="badge bg-info">{{ $feeType->category_name }}</span>
                                                </td>
                                                <td><strong>{{ number_format($feeType->default_amount, 2) }} ر.س</strong></td>
                                                <td>
                                                    @if($feeType->is_recurring)
                                                        <span class="badge bg-success">نعم ({{ $feeType->recurring_period }})</span>
                                                    @else
                                                        <span class="badge bg-secondary">لا</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($feeType->is_active)
                                                        <span class="badge bg-success">نشط</span>
                                                    @else
                                                        <span class="badge bg-danger">غير نشط</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="d-flex gap-2">
                                                        @can('fee-type-edit')
                                                        <a href="{{ route('admin.fee-types.edit', $feeType->id) }}" class="btn btn-sm btn-warning" title="تعديل">
                                                            <i class="fa-solid fa-edit"></i>
                                                        </a>
                                                        @endcan
                                                        @can('fee-type-delete')
                                                        <button type="button" class="btn btn-sm btn-danger" 
                                                                data-delete-url="{{ route('admin.fee-types.destroy', $feeType->id) }}"
                                                                data-delete-message="هل أنت متأكد من رغبتك في حذف نوع الرسوم <strong>{{ $feeType->name }}</strong>؟"
                                                                title="حذف">
                                                            <i class="fa-solid fa-trash-can"></i>
                                                        </button>
                                                        @endcan
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="8" class="text-center">لا توجد أنواع رسوم</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-3">
                                {{ $feeTypes->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End::app-content -->

    @include('admin.components.delete-modal')
@stop

