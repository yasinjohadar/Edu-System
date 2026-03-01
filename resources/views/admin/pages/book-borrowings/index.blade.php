@extends('admin.layouts.master')

@section('page-title')
    استعارات الكتب
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
                    <h5 class="page-title fs-21 mb-1">استعارات الكتب</h5>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header align-items-center d-flex gap-3">
                            @can('book-borrowing-create')
                            <a href="{{ route('admin.book-borrowings.create') }}" class="btn btn-primary btn-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-left: 4px; vertical-align: middle;">
                                    <line x1="12" y1="5" x2="12" y2="19"></line>
                                    <line x1="5" y1="12" x2="19" y2="12"></line>
                                </svg>
                                تسجيل استعارة جديدة
                            </a>
                            @endcan
                            <div class="flex-shrink-0">
                                <form action="{{ route('admin.book-borrowings.index') }}" method="GET" class="d-flex align-items-center gap-2">
                                    <select name="status" class="form-select" style="width: 150px;">
                                        <option value="">كل الحالات</option>
                                        <option value="borrowed" {{ request('status') == 'borrowed' ? 'selected' : '' }}>مستعار</option>
                                        <option value="returned" {{ request('status') == 'returned' ? 'selected' : '' }}>مُرجع</option>
                                        <option value="overdue" {{ request('status') == 'overdue' ? 'selected' : '' }}>متأخر</option>
                                        <option value="lost" {{ request('status') == 'lost' ? 'selected' : '' }}>مفقود</option>
                                    </select>
                                    <select name="student_id" class="form-select" style="width: 200px;">
                                        <option value="">كل الطلاب</option>
                                        @foreach($students as $student)
                                            <option value="{{ $student->id }}" {{ request('student_id') == $student->id ? 'selected' : '' }}>{{ $student->user->name }}</option>
                                        @endforeach
                                    </select>
                                    <button type="submit" class="btn btn-secondary">بحث</button>
                                    <a href="{{ route('admin.book-borrowings.index') }}" class="btn btn-danger">مسح</a>
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
                                            <th>الطالب</th>
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
                                                <td>{{ $borrowing->book->title }}</td>
                                                <td>{{ $borrowing->student->user->name ?? '-' }}</td>
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
                                                    <div class="d-flex gap-2">
                                                        <a href="{{ route('admin.book-borrowings.show', $borrowing->id) }}" class="btn btn-sm btn-info" title="عرض">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                                <circle cx="12" cy="12" r="3"></circle>
                                                            </svg>
                                                        </a>
                                                        @if($borrowing->status == 'borrowed' || $borrowing->status == 'overdue')
                                                            <a href="{{ route('admin.book-borrowings.edit', $borrowing->id) }}" class="btn btn-sm btn-warning" title="إرجاع">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                                                                    <polyline points="9 22 9 12 15 12 15 22"></polyline>
                                                                </svg>
                                                            </a>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="9" class="text-center">لا توجد استعارات</td>
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

    @include('admin.components.delete-modal')
@stop

