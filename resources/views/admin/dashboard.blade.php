@extends('admin.layouts.master')

@section('page-title')
    لوحة التحكم
@stop

@section('content')
    <!-- Start::app-content -->
    <div class="main-content app-content">
        <div class="container-fluid">
            <!-- Page Header -->
            <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
                <div>
                    <h4 class="mb-0">مرحباً بك، {{ auth()->user()->name }}!</h4>
                    <p class="mb-0 text-muted">نظرة عامة على النظام التعليمي</p>
                </div>
                <div class="main-dashboard-header-right">
                    <div>
                        <label class="fs-13 text-muted">نسبة الحضور هذا الأسبوع</label>
                        <h5 class="mb-0 fw-semibold">{{ $attendanceStats['week_rate'] }}%</h5>
                    </div>
                    <div>
                        <label class="fs-13 text-muted">المدفوعات هذا الشهر</label>
                        <h5 class="mb-0 fw-semibold">{{ number_format($financialStats['this_month_payments'], 2) }} ر.س</h5>
                    </div>
                    <div>
                        <label class="fs-13 text-muted">إجمالي الطلاب</label>
                        <h5 class="mb-0 fw-semibold">{{ $studentsStats['total'] }}</h5>
                    </div>
                </div>
            </div>
            <!-- End Page Header -->

            <!-- إحصائيات سريعة -->
            <div class="row">
                <!-- بطاقة الطلاب -->
                <div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
                    <div class="card overflow-hidden sales-card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                        <div class="px-3 pt-3 pb-2 pt-0">
                            <div>
                                <h6 class="mb-3 fs-12 text-white">إجمالي الطلاب</h6>
                            </div>
                            <div class="pb-0 mt-0">
                                <div class="d-flex">
                                    <div>
                                        <h4 class="fs-20 fw-bold mb-1 text-white">{{ $studentsStats['total'] }}</h4>
                                        <p class="mb-0 fs-12 text-white op-7">{{ $studentsStats['active'] }} نشط</p>
                                    </div>
                                    <span class="float-end my-auto ms-auto">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-white op-7">
                                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                            <circle cx="9" cy="7" r="4"></circle>
                                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                        </svg>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- بطاقة المعلمين -->
                <div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
                    <div class="card overflow-hidden sales-card" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                        <div class="px-3 pt-3 pb-2 pt-0">
                            <div>
                                <h6 class="mb-3 fs-12 text-white">إجمالي المعلمين</h6>
                            </div>
                            <div class="pb-0 mt-0">
                                <div class="d-flex">
                                    <div>
                                        <h4 class="fs-20 fw-bold mb-1 text-white">{{ $teachersStats['total'] }}</h4>
                                        <p class="mb-0 fs-12 text-white op-7">{{ $teachersStats['active'] }} نشط</p>
                                    </div>
                                    <span class="float-end my-auto ms-auto">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-white op-7">
                                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                            <circle cx="12" cy="7" r="4"></circle>
                                        </svg>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- بطاقة الحضور اليوم -->
                <div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
                    <div class="card overflow-hidden sales-card" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                        <div class="px-3 pt-3 pb-2 pt-0">
                            <div>
                                <h6 class="mb-3 fs-12 text-white">الحضور اليوم</h6>
                            </div>
                            <div class="pb-0 mt-0">
                                <div class="d-flex">
                                    <div>
                                        <h4 class="fs-20 fw-bold mb-1 text-white">{{ $attendanceStats['today_present'] }}</h4>
                                        <p class="mb-0 fs-12 text-white op-7">{{ $attendanceStats['today_absent'] }} غائب</p>
                                    </div>
                                    <span class="float-end my-auto ms-auto">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-white op-7">
                                            <circle cx="12" cy="12" r="10"></circle>
                                            <polyline points="12 6 12 12 16 14"></polyline>
                                        </svg>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- بطاقة المالية -->
                <div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
                    <div class="card overflow-hidden sales-card" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
                        <div class="px-3 pt-3 pb-2 pt-0">
                            <div>
                                <h6 class="mb-3 fs-12 text-white">إجمالي المدفوعات</h6>
                            </div>
                            <div class="pb-0 mt-0">
                                <div class="d-flex">
                                    <div>
                                        <h4 class="fs-20 fw-bold mb-1 text-white">{{ number_format($financialStats['total_payments'], 0) }}</h4>
                                        <p class="mb-0 fs-12 text-white op-7">{{ number_format($financialStats['overdue_amount'], 0) }} متأخر</p>
                                    </div>
                                    <span class="float-end my-auto ms-auto">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-white op-7">
                                            <line x1="12" y1="1" x2="12" y2="23"></line>
                                            <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                                        </svg>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End إحصائيات سريعة -->

            <!-- إحصائيات إضافية -->
            <div class="row">
                <!-- بطاقة الفواتير -->
                <div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <h6 class="mb-1 text-muted">إجمالي الفواتير</h6>
                                    <h4 class="mb-0 fw-bold">{{ $financialStats['total_invoices'] }}</h4>
                                    <p class="mb-0 text-muted fs-12">{{ number_format($financialStats['total_invoices_amount'], 2) }} ر.س</p>
                                </div>
                                <div class="flex-shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#667eea" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                        <polyline points="14 2 14 8 20 8"></polyline>
                                        <line x1="16" y1="13" x2="8" y2="13"></line>
                                        <line x1="16" y1="17" x2="8" y2="17"></line>
                                        <polyline points="10 9 9 9 8 9"></polyline>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- بطاقة الفواتير المعلقة -->
                <div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <h6 class="mb-1 text-muted">فواتير معلقة</h6>
                                    <h4 class="mb-0 fw-bold text-warning">{{ $financialStats['pending_invoices'] }}</h4>
                                    <p class="mb-0 text-muted fs-12">{{ number_format($financialStats['pending_amount'], 2) }} ر.س</p>
                                </div>
                                <div class="flex-shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#f093fb" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <polyline points="12 6 12 12 16 14"></polyline>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- بطاقة الفواتير المتأخرة -->
                <div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <h6 class="mb-1 text-muted">فواتير متأخرة</h6>
                                    <h4 class="mb-0 fw-bold text-danger">{{ $financialStats['overdue_invoices'] }}</h4>
                                    <p class="mb-0 text-muted fs-12">{{ number_format($financialStats['overdue_amount'], 2) }} ر.س</p>
                                </div>
                                <div class="flex-shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#f5576c" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <polyline points="12 6 12 12 16 14"></polyline>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- بطاقة المعدل العام -->
                <div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <h6 class="mb-1 text-muted">المعدل العام</h6>
                                    <h4 class="mb-0 fw-bold text-success">{{ number_format($gradeStats['average_percentage'], 1) }}%</h4>
                                    <p class="mb-0 text-muted fs-12">{{ $gradeStats['total_records'] }} سجل</p>
                                </div>
                                <div class="flex-shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#43e97b" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                        <polyline points="22 4 12 14.01 9 11.01"></polyline>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End إحصائيات إضافية -->

            <!-- الجداول والإشعارات -->
            <div class="row">
                <!-- آخر الطلاب المسجلين -->
                <div class="col-xl-6 col-lg-12">
                    <div class="card">
                        <div class="card-header pb-1">
                            <h3 class="card-title mb-2">آخر الطلاب المسجلين</h3>
                            <p class="fs-12 mb-0 text-muted">أحدث {{ $recentStudents->count() }} طالب مسجل في النظام</p>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead>
                                        <tr>
                                            <th>الاسم</th>
                                            <th>الصف</th>
                                            <th>الفصل</th>
                                            <th>تاريخ التسجيل</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($recentStudents as $student)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="flex-grow-1">
                                                            <h6 class="mb-0 fs-14">{{ $student->user->name ?? 'غير محدد' }}</h6>
                                                            <p class="mb-0 text-muted fs-12">{{ $student->student_code }}</p>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>{{ $student->class->name ?? 'غير محدد' }}</td>
                                                <td>{{ $student->section->name ?? 'غير محدد' }}</td>
                                                <td>{{ $student->enrollment_date ? $student->enrollment_date->format('Y-m-d') : 'غير محدد' }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center text-muted">لا توجد بيانات</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            @if($recentStudents->count() > 0)
                                <div class="card-footer text-center">
                                    <a href="{{ route('admin.students.index') }}" class="btn btn-sm btn-primary">عرض جميع الطلاب</a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- آخر الفواتير -->
                <div class="col-xl-6 col-lg-12">
                    <div class="card">
                        <div class="card-header pb-1">
                            <h3 class="card-title mb-2">آخر الفواتير</h3>
                            <p class="fs-12 mb-0 text-muted">أحدث {{ $recentInvoices->count() }} فاتورة في النظام</p>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead>
                                        <tr>
                                            <th>رقم الفاتورة</th>
                                            <th>الطالب</th>
                                            <th>المبلغ</th>
                                            <th>الحالة</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($recentInvoices as $invoice)
                                            <tr>
                                                <td>
                                                    <a href="{{ route('admin.invoices.show', $invoice->id) }}" class="text-primary">
                                                        {{ $invoice->invoice_number }}
                                                    </a>
                                                </td>
                                                <td>{{ $invoice->student->user->name ?? 'غير محدد' }}</td>
                                                <td>{{ number_format($invoice->total_amount, 2) }} ر.س</td>
                                                <td>
                                                    <span class="badge 
                                                        @if($invoice->status == 'paid') bg-success
                                                        @elseif($invoice->status == 'overdue') bg-danger
                                                        @elseif($invoice->status == 'partial') bg-warning
                                                        @else bg-secondary
                                                        @endif">
                                                        {{ $invoice->status_name }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center text-muted">لا توجد بيانات</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            @if($recentInvoices->count() > 0)
                                <div class="card-footer text-center">
                                    <a href="{{ route('admin.invoices.index') }}" class="btn btn-sm btn-primary">عرض جميع الفواتير</a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- الفواتير المتأخرة والطلاب الأكثر غياباً -->
            <div class="row">
                <!-- الفواتير المتأخرة -->
                <div class="col-xl-6 col-lg-12">
                    <div class="card border-danger">
                        <div class="card-header bg-danger text-white">
                            <h3 class="card-title mb-0 text-white">⚠️ فواتير متأخرة</h3>
                            <p class="fs-12 mb-0 text-white op-7">فواتير تجاوزت تاريخ الاستحقاق</p>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead>
                                        <tr>
                                            <th>رقم الفاتورة</th>
                                            <th>الطالب</th>
                                            <th>المتبقي</th>
                                            <th>تاريخ الاستحقاق</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($overdueInvoices as $invoice)
                                            <tr>
                                                <td>
                                                    <a href="{{ route('admin.invoices.show', $invoice->id) }}" class="text-danger">
                                                        {{ $invoice->invoice_number }}
                                                    </a>
                                                </td>
                                                <td>{{ $invoice->student->user->name ?? 'غير محدد' }}</td>
                                                <td class="text-danger fw-bold">{{ number_format($invoice->remaining_amount, 2) }} ر.س</td>
                                                <td>{{ $invoice->due_date->format('Y-m-d') }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center text-muted">لا توجد فواتير متأخرة</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            @if($overdueInvoices->count() > 0)
                                <div class="card-footer text-center">
                                    <a href="{{ route('admin.invoices.index', ['status' => 'overdue']) }}" class="btn btn-sm btn-danger">عرض جميع الفواتير المتأخرة</a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- الطلاب الأكثر غياباً -->
                <div class="col-xl-6 col-lg-12">
                    <div class="card border-warning">
                        <div class="card-header bg-warning text-white">
                            <h3 class="card-title mb-0 text-white">⚠️ طلاب يحتاجون متابعة</h3>
                            <p class="fs-12 mb-0 text-white op-7">الطلاب الأكثر غياباً (آخر 30 يوم)</p>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead>
                                        <tr>
                                            <th>الاسم</th>
                                            <th>الصف</th>
                                            <th>عدد الغيابات</th>
                                            <th>الإجراء</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($mostAbsentStudents as $student)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="flex-grow-1">
                                                            <h6 class="mb-0 fs-14">{{ $student->user->name ?? 'غير محدد' }}</h6>
                                                            <p class="mb-0 text-muted fs-12">{{ $student->student_code }}</p>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>{{ $student->class->name ?? 'غير محدد' }}</td>
                                                <td>
                                                    <span class="badge bg-danger">{{ $student->absent_count ?? 0 }}</span>
                                                </td>
                                                <td>
                                                    <a href="{{ route('admin.students.show', $student->id) }}" class="btn btn-sm btn-outline-primary">عرض التفاصيل</a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center text-muted">لا توجد بيانات</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- آخر المدفوعات -->
            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header pb-1">
                            <h3 class="card-title mb-2">آخر المدفوعات</h3>
                            <p class="fs-12 mb-0 text-muted">أحدث {{ $recentPayments->count() }} دفعة في النظام</p>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead>
                                        <tr>
                                            <th>رقم الدفعة</th>
                                            <th>الطالب</th>
                                            <th>الفاتورة</th>
                                            <th>المبلغ</th>
                                            <th>طريقة الدفع</th>
                                            <th>التاريخ</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($recentPayments as $payment)
                                            <tr>
                                                <td>
                                                    <a href="{{ route('admin.payments.show', $payment->id) }}" class="text-primary">
                                                        {{ $payment->payment_number }}
                                                    </a>
                                                </td>
                                                <td>{{ $payment->student->user->name ?? 'غير محدد' }}</td>
                                                <td>
                                                    @if($payment->invoice)
                                                        <a href="{{ route('admin.invoices.show', $payment->invoice->id) }}" class="text-info">
                                                            {{ $payment->invoice->invoice_number }}
                                                        </a>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td class="fw-bold text-success">{{ number_format($payment->amount, 2) }} ر.س</td>
                                                <td>{{ $payment->payment_method_name }}</td>
                                                <td>{{ $payment->payment_date->format('Y-m-d') }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center text-muted">لا توجد بيانات</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            @if($recentPayments->count() > 0)
                                <div class="card-footer text-center">
                                    <a href="{{ route('admin.payments.index') }}" class="btn btn-sm btn-primary">عرض جميع المدفوعات</a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <!-- End::app-content -->
@stop
