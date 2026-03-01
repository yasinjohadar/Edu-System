@extends('admin.layouts.master')

@section('page-title')
    قائمة الفواتير
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
                    <h5 class="page-title fs-21 mb-1">الفواتير</h5>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header align-items-center d-flex gap-3">
                            @can('invoice-create')
                            <a href="{{ route('admin.invoices.create') }}" class="btn btn-primary btn-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-left: 4px; vertical-align: middle;">
                                    <line x1="12" y1="5" x2="12" y2="19"></line>
                                    <line x1="5" y1="12" x2="19" y2="12"></line>
                                </svg>
                                إنشاء فاتورة جديدة
                            </a>
                            @endcan
                            <div class="flex-shrink-0">
                                <form action="{{ route('admin.invoices.index') }}" method="GET" class="d-flex align-items-center gap-2">
                                    <select name="student_id" class="form-select" style="width: 200px;">
                                        <option value="">كل الطلاب</option>
                                        @foreach($students as $student)
                                            <option value="{{ $student->id }}" {{ request('student_id') == $student->id ? 'selected' : '' }}>
                                                {{ $student->user->name }} ({{ $student->student_code }})
                                            </option>
                                        @endforeach
                                    </select>
                                    <select name="status" class="form-select" style="width: 150px;">
                                        <option value="">كل الحالات</option>
                                        @foreach($statuses as $key => $name)
                                            <option value="{{ $key }}" {{ request('status') == $key ? 'selected' : '' }}>
                                                {{ $name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <input type="text" name="invoice_number" class="form-control" placeholder="رقم الفاتورة" value="{{ request('invoice_number') }}" style="width: 150px;">
                                    <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}" style="width: 150px;">
                                    <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}" style="width: 150px;">
                                    <button type="submit" class="btn btn-secondary">بحث</button>
                                    <a href="{{ route('admin.invoices.index') }}" class="btn btn-danger">مسح</a>
                                </form>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover align-middle table-nowrap mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>رقم الفاتورة</th>
                                            <th>الطالب</th>
                                            <th>تاريخ الفاتورة</th>
                                            <th>تاريخ الاستحقاق</th>
                                            <th>المبلغ الإجمالي</th>
                                            <th>المدفوع</th>
                                            <th>المتبقي</th>
                                            <th>الحالة</th>
                                            <th>العمليات</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($invoices as $invoice)
                                            <tr>
                                                <td>{{ $invoice->id }}</td>
                                                <td><strong>{{ $invoice->invoice_number }}</strong></td>
                                                <td>
                                                    {{ $invoice->student->user->name ?? 'غير محدد' }}
                                                    <br>
                                                    <small class="text-muted">{{ $invoice->student->student_code }}</small>
                                                </td>
                                                <td>{{ $invoice->invoice_date->format('Y-m-d') }}</td>
                                                <td>
                                                    {{ $invoice->due_date->format('Y-m-d') }}
                                                    @if($invoice->isOverdue())
                                                        <br><small class="text-danger">متأخرة</small>
                                                    @endif
                                                </td>
                                                <td><strong>{{ number_format($invoice->total_amount, 2) }} ر.س</strong></td>
                                                <td><span class="text-success">{{ number_format($invoice->paid_amount, 2) }} ر.س</span></td>
                                                <td><span class="text-danger">{{ number_format($invoice->remaining_amount, 2) }} ر.س</span></td>
                                                <td>
                                                    @if($invoice->status == 'paid')
                                                        <span class="badge bg-success">{{ $invoice->status_name }}</span>
                                                    @elseif($invoice->status == 'overdue')
                                                        <span class="badge bg-danger">{{ $invoice->status_name }}</span>
                                                    @elseif($invoice->status == 'partial')
                                                        <span class="badge bg-warning">{{ $invoice->status_name }}</span>
                                                    @elseif($invoice->status == 'pending')
                                                        <span class="badge bg-info">{{ $invoice->status_name }}</span>
                                                    @elseif($invoice->status == 'draft')
                                                        <span class="badge bg-secondary">{{ $invoice->status_name }}</span>
                                                    @else
                                                        <span class="badge bg-dark">{{ $invoice->status_name }}</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="d-flex gap-2">
                                                        <a href="{{ route('admin.invoices.show', $invoice->id) }}" class="btn btn-sm btn-info" title="عرض">
                                                            <i class="fa-solid fa-eye"></i>
                                                        </a>
                                                        @can('invoice-edit')
                                                        @if($invoice->status != 'paid' && $invoice->status != 'cancelled')
                                                        <a href="{{ route('admin.invoices.edit', $invoice->id) }}" class="btn btn-sm btn-warning" title="تعديل">
                                                            <i class="fa-solid fa-edit"></i>
                                                        </a>
                                                        @endif
                                                        @endcan
                                                        @can('invoice-delete')
                                                        @if($invoice->status != 'paid' && $invoice->payments()->count() == 0)
                                                        <button type="button" class="btn btn-sm btn-danger" 
                                                                data-delete-url="{{ route('admin.invoices.destroy', $invoice->id) }}"
                                                                data-delete-message="هل أنت متأكد من رغبتك في حذف الفاتورة <strong>{{ $invoice->invoice_number }}</strong>؟"
                                                                title="حذف">
                                                            <i class="fa-solid fa-trash-can"></i>
                                                        </button>
                                                        @endif
                                                        @endcan
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="10" class="text-center">لا توجد فواتير</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-3">
                                {{ $invoices->links() }}
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

