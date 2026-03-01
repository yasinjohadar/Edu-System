@extends('admin.layouts.master')

@section('page-title')
    تقرير المكتبة
@stop

@section('content')
    <!-- Start::app-content -->
    <div class="main-content app-content">
        <div class="container-fluid">
            <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
                <div class="my-auto">
                    <h5 class="page-title fs-21 mb-1">تقرير المكتبة</h5>
                </div>
                <div>
                    <a href="{{ route('admin.reports.index') }}" class="btn btn-secondary">رجوع</a>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header align-items-center d-flex gap-3">
                            <h5 class="card-title mb-0">فلاتر التقرير</h5>
                            <div class="flex-shrink-0">
                                <form action="{{ route('admin.reports.library') }}" method="GET" class="d-flex align-items-center gap-2">
                                    <select name="status" class="form-select" style="width: 150px;">
                                        <option value="">كل الحالات</option>
                                        <option value="borrowed" {{ request('status') == 'borrowed' ? 'selected' : '' }}>مستعار</option>
                                        <option value="returned" {{ request('status') == 'returned' ? 'selected' : '' }}>مُعاد</option>
                                        <option value="overdue" {{ request('status') == 'overdue' ? 'selected' : '' }}>متأخر</option>
                                    </select>
                                    <input type="date" name="date_from" class="form-control" style="width: 150px;" value="{{ request('date_from') }}">
                                    <input type="date" name="date_to" class="form-control" style="width: 150px;" value="{{ request('date_to') }}">
                                    <button type="submit" class="btn btn-primary">بحث</button>
                                    <a href="{{ route('admin.reports.library') }}" class="btn btn-danger">مسح</a>
                                </form>
                            </div>
                        </div>
                        <div class="card-body">
                            <!-- إحصائيات الاستعارات -->
                            <div class="row mb-4">
                                <div class="col-md-3">
                                    <div class="card border-primary">
                                        <div class="card-body text-center">
                                            <h6 class="text-muted">إجمالي الاستعارات</h6>
                                            <h3 class="mb-0">{{ $stats['total_borrowings'] }}</h3>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card border-info">
                                        <div class="card-body text-center">
                                            <h6 class="text-muted">نشطة</h6>
                                            <h3 class="mb-0 text-info">{{ $stats['active_borrowings'] }}</h3>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card border-success">
                                        <div class="card-body text-center">
                                            <h6 class="text-muted">مُعادة</h6>
                                            <h3 class="mb-0 text-success">{{ $stats['returned_borrowings'] }}</h3>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card border-danger">
                                        <div class="card-body text-center">
                                            <h6 class="text-muted">متأخرة</h6>
                                            <h3 class="mb-0 text-danger">{{ $stats['overdue_borrowings'] }}</h3>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- إحصائيات الغرامات -->
                            <div class="row mb-4">
                                <div class="col-md-4">
                                    <div class="card border-warning">
                                        <div class="card-body text-center">
                                            <h6 class="text-muted">إجمالي الغرامات</h6>
                                            <h3 class="mb-0">{{ $fineStats['total_fines'] }}</h3>
                                            <small class="text-muted">{{ number_format($fineStats['total_amount'], 2) }} ر.س</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card border-success">
                                        <div class="card-body text-center">
                                            <h6 class="text-muted">مدفوعة</h6>
                                            <h3 class="mb-0 text-success">{{ number_format($fineStats['paid_amount'], 2) }} ر.س</h3>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card border-danger">
                                        <div class="card-body text-center">
                                            <h6 class="text-muted">غير مدفوعة</h6>
                                            <h3 class="mb-0 text-danger">{{ number_format($fineStats['unpaid_amount'], 2) }} ر.س</h3>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <h5 class="mb-3">الاستعارات</h5>
                            @if($borrowings->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover align-middle table-nowrap mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th>#</th>
                                                <th>اسم الطالب</th>
                                                <th>اسم الكتاب</th>
                                                <th>تاريخ الاستعارة</th>
                                                <th>تاريخ الإرجاع المتوقع</th>
                                                <th>تاريخ الإرجاع الفعلي</th>
                                                <th>الحالة</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($borrowings as $borrowing)
                                                <tr>
                                                    <td>{{ $borrowing->id }}</td>
                                                    <td>{{ $borrowing->student->user->name }}</td>
                                                    <td>{{ $borrowing->book->title }}</td>
                                                    <td>{{ $borrowing->borrowed_at->format('Y-m-d') }}</td>
                                                    <td>{{ $borrowing->due_date->format('Y-m-d') }}</td>
                                                    <td>{{ $borrowing->returned_at ? $borrowing->returned_at->format('Y-m-d') : '-' }}</td>
                                                    <td>
                                                        @if($borrowing->status == 'borrowed')
                                                            <span class="badge bg-info">مستعار</span>
                                                        @elseif($borrowing->status == 'returned')
                                                            <span class="badge bg-success">مُعاد</span>
                                                        @else
                                                            <span class="badge bg-danger">متأخر</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="alert alert-info text-center">
                                    <p class="mb-0">لا توجد استعارات متاحة</p>
                                </div>
                            @endif

                            <h5 class="mb-3 mt-4">الغرامات</h5>
                            @if($fines->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover align-middle table-nowrap mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th>#</th>
                                                <th>اسم الطالب</th>
                                                <th>اسم الكتاب</th>
                                                <th>المبلغ</th>
                                                <th>تاريخ الإنشاء</th>
                                                <th>الحالة</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($fines as $fine)
                                                <tr>
                                                    <td>{{ $fine->id }}</td>
                                                    <td>{{ $fine->bookBorrowing->student->user->name }}</td>
                                                    <td>{{ $fine->bookBorrowing->book->title }}</td>
                                                    <td>{{ number_format($fine->amount, 2) }} ر.س</td>
                                                    <td>{{ $fine->created_at->format('Y-m-d') }}</td>
                                                    <td>
                                                        @if($fine->status == 'paid')
                                                            <span class="badge bg-success">مدفوعة</span>
                                                        @else
                                                            <span class="badge bg-danger">غير مدفوعة</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="alert alert-info text-center">
                                    <p class="mb-0">لا توجد غرامات متاحة</p>
                                </div>
                            @endif
                        </div>
                        @if($borrowings->count() > 0 || $fines->count() > 0)
                            <div class="card-footer">
                                <form action="{{ route('admin.reports.export') }}" method="POST" class="d-inline">
                                    @csrf
                                    <input type="hidden" name="type" value="library">
                                    @foreach(request()->except(['type', 'format', '_token']) as $key => $value)
                                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                                    @endforeach
                                    <button type="submit" name="format" value="pdf" class="btn btn-danger">تصدير PDF</button>
                                    <button type="submit" name="format" value="excel" class="btn btn-success">تصدير Excel</button>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End::app-content -->
@stop

