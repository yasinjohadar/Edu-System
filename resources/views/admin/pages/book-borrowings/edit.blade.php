@extends('admin.layouts.master')

@section('page-title')
    إرجاع الكتاب
@stop

@section('content')
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Start::app-content -->
    <div class="main-content app-content">
        <div class="container-fluid">
            <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
                <div class="my-auto">
                    <h5 class="page-title fs-21 mb-1">إرجاع الكتاب</h5>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">معلومات الاستعارة</h5>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-info mb-3">
                                <strong>الكتاب:</strong> {{ $borrowing->book->title }}<br>
                                <strong>الطالب:</strong> {{ $borrowing->student->user->name ?? '-' }}<br>
                                <strong>تاريخ الاستعارة:</strong> {{ $borrowing->borrow_date->format('Y-m-d') }}<br>
                                <strong>تاريخ الاستحقاق:</strong> {{ $borrowing->due_date->format('Y-m-d') }}
                                @if($borrowing->isOverdue())
                                    <br><strong class="text-danger">متأخر {{ $borrowing->days_overdue }} يوم</strong>
                                @endif
                            </div>

                            <form action="{{ route('admin.book-borrowings.return', $borrowing->id) }}" method="POST">
                                @csrf
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">تاريخ الإرجاع <span class="text-danger">*</span></label>
                                        <input type="date" name="return_date" class="form-control" value="{{ old('return_date', date('Y-m-d')) }}" required>
                                        @error('return_date')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">حالة الإرجاع <span class="text-danger">*</span></label>
                                        <select name="status" class="form-select" required>
                                            <option value="returned" {{ old('status', 'returned') == 'returned' ? 'selected' : '' }}>مُرجع</option>
                                            <option value="damaged" {{ old('status') == 'damaged' ? 'selected' : '' }}>تالف</option>
                                            <option value="lost" {{ old('status') == 'lost' ? 'selected' : '' }}>مفقود</option>
                                        </select>
                                        @error('status')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">ملاحظات</label>
                                        <textarea name="notes" class="form-control" rows="3">{{ old('notes', $borrowing->notes) }}</textarea>
                                        @error('notes')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-left: 5px; vertical-align: middle;">
                                            <polyline points="20 6 9 17 4 12"></polyline>
                                        </svg>
                                        تسجيل الإرجاع
                                    </button>
                                    <a href="{{ route('admin.book-borrowings.index') }}" class="btn btn-secondary">إلغاء</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End::app-content -->
@stop

