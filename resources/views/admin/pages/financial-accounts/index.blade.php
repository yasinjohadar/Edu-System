@extends('admin.layouts.master')

@section('page-title')
    الحسابات المالية
@stop

@section('content')
    <!-- Start::app-content -->
    <div class="main-content app-content">
        <div class="container-fluid">
            <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
                <div class="my-auto">
                    <h5 class="page-title fs-21 mb-1">الحسابات المالية</h5>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header align-items-center d-flex gap-3">
                            <div class="flex-shrink-0">
                                <form action="{{ route('admin.financial-accounts.index') }}" method="GET" class="d-flex align-items-center gap-2">
                                    <select name="student_id" class="form-select" style="width: 200px;">
                                        <option value="">كل الطلاب</option>
                                        @foreach($students as $student)
                                            <option value="{{ $student->id }}" {{ request('student_id') == $student->id ? 'selected' : '' }}>
                                                {{ $student->user->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <input type="text" name="account_number" class="form-control" placeholder="رقم الحساب" value="{{ request('account_number') }}" style="width: 200px;">
                                    <button type="submit" class="btn btn-secondary">بحث</button>
                                    <a href="{{ route('admin.financial-accounts.index') }}" class="btn btn-danger">مسح</a>
                                </form>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover align-middle table-nowrap mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>رقم الحساب</th>
                                            <th>الطالب</th>
                                            <th>إجمالي الفواتير</th>
                                            <th>إجمالي المدفوعات</th>
                                            <th>المستحقات</th>
                                            <th>الرصيد</th>
                                            <th>آخر معاملة</th>
                                            <th>العمليات</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($accounts as $account)
                                            <tr>
                                                <td>{{ $account->id }}</td>
                                                <td><strong>{{ $account->account_number }}</strong></td>
                                                <td>
                                                    {{ $account->student->user->name ?? 'غير محدد' }}
                                                    <br>
                                                    <small class="text-muted">{{ $account->student->student_code }}</small>
                                                </td>
                                                <td><strong>{{ number_format($account->total_invoiced, 2) }} ر.س</strong></td>
                                                <td><span class="text-success">{{ number_format($account->total_paid, 2) }} ر.س</span></td>
                                                <td><span class="text-danger">{{ number_format($account->total_due, 2) }} ر.س</span></td>
                                                <td>
                                                    @if($account->balance >= 0)
                                                        <span class="text-success">{{ number_format($account->balance, 2) }} ر.س</span>
                                                    @else
                                                        <span class="text-danger">{{ number_format($account->balance, 2) }} ر.س</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($account->last_transaction_date)
                                                        {{ $account->last_transaction_date->format('Y-m-d') }}
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="{{ route('admin.financial-accounts.show', $account->id) }}" class="btn btn-sm btn-info" title="عرض التفاصيل">
                                                        <i class="fa-solid fa-eye"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="9" class="text-center">لا توجد حسابات مالية</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-3">
                                {{ $accounts->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End::app-content -->
@stop

