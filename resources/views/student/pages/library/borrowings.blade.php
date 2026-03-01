@extends('student.layouts.master')

@section('page-title')
    استعارات الكتب
@stop

@section('content')
    <!-- Start::app-content -->
    <div class="main-content app-content">
        <div class="container-fluid">
            <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
                <div class="my-auto">
                    <h5 class="page-title fs-21 mb-1">استعارات الكتب</h5>
                </div>
            </div>

            <!-- إحصائيات -->
            <div class="row mb-4">
                <div class="col-xl-3 col-lg-6 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="avatar avatar-md bg-primary text-white rounded">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                                            <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-0">إجمالي الاستعارات</h6>
                                    <h4 class="mb-0">{{ $stats['total_borrowings'] }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-6 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="avatar avatar-md bg-warning text-white rounded">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <circle cx="12" cy="12" r="10"></circle>
                                            <polyline points="12 6 12 12 16 14"></polyline>
                                        </svg>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-0">الاستعارات النشطة</h6>
                                    <h4 class="mb-0">{{ $stats['active_borrowings'] }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-6 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="avatar avatar-md bg-success text-white rounded">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <polyline points="20 6 9 17 4 12"></polyline>
                                        </svg>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-0">الاستعارات المُرجعة</h6>
                                    <h4 class="mb-0">{{ $stats['returned_borrowings'] }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-6 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="avatar avatar-md bg-danger text-white rounded">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <circle cx="12" cy="12" r="10"></circle>
                                            <line x1="12" y1="8" x2="12" y2="12"></line>
                                            <line x1="12" y1="16" x2="12.01" y2="16"></line>
                                        </svg>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-0">الاستعارات المتأخرة</h6>
                                    <h4 class="mb-0">{{ $stats['overdue_borrowings'] }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header align-items-center d-flex gap-3">
                            <div class="flex-shrink-0">
                                <form action="{{ route('student.library.borrowings') }}" method="GET" class="d-flex align-items-center gap-2">
                                    <select name="status" class="form-select" style="width: 150px;">
                                        <option value="">كل الحالات</option>
                                        <option value="borrowed" {{ request('status') == 'borrowed' ? 'selected' : '' }}>مستعار</option>
                                        <option value="returned" {{ request('status') == 'returned' ? 'selected' : '' }}>مُرجع</option>
                                        <option value="overdue" {{ request('status') == 'overdue' ? 'selected' : '' }}>متأخر</option>
                                        <option value="lost" {{ request('status') == 'lost' ? 'selected' : '' }}>مفقود</option>
                                    </select>
                                    <button type="submit" class="btn btn-secondary">بحث</button>
                                    <a href="{{ route('student.library.borrowings') }}" class="btn btn-danger">مسح</a>
                                </form>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover align-middle table-nowrap mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>رقم الاستعارة</th>
                                            <th>الكتاب</th>
                                            <th>تاريخ الاستعارة</th>
                                            <th>تاريخ الاستحقاق</th>
                                            <th>تاريخ الإرجاع</th>
                                            <th>الحالة</th>
                                            <th>العمليات</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($borrowings as $borrowing)
                                            <tr>
                                                <td>{{ $borrowing->id }}</td>
                                                <td><strong>{{ $borrowing->borrowing_number }}</strong></td>
                                                <td>
                                                    <strong>{{ $borrowing->book->title }}</strong><br>
                                                    <small class="text-muted">{{ $borrowing->book->author }}</small>
                                                </td>
                                                <td>{{ $borrowing->borrow_date->format('Y-m-d') }}</td>
                                                <td>
                                                    {{ $borrowing->due_date->format('Y-m-d') }}
                                                    @if($borrowing->isOverdue())
                                                        <br><small class="text-danger">متأخر {{ $borrowing->days_overdue }} يوم</small>
                                                    @endif
                                                </td>
                                                <td>{{ $borrowing->return_date ? $borrowing->return_date->format('Y-m-d') : '-' }}</td>
                                                <td>
                                                    @if($borrowing->status == 'borrowed')
                                                        <span class="badge bg-primary">مستعار</span>
                                                    @elseif($borrowing->status == 'returned')
                                                        <span class="badge bg-success">مُرجع</span>
                                                    @elseif($borrowing->status == 'overdue')
                                                        <span class="badge bg-danger">متأخر</span>
                                                    @else
                                                        <span class="badge bg-warning">مفقود</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="{{ route('student.library.borrowings.show', $borrowing->id) }}" class="btn btn-sm btn-info" title="عرض">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                            <circle cx="12" cy="12" r="3"></circle>
                                                        </svg>
                                                    </a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="8" class="text-center">لا توجد استعارات</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-3">
                                {{ $borrowings->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End::app-content -->
@stop

