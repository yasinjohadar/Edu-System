@extends('admin.layouts.master')

@section('page-title')
    إضافة غرامة جديدة
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
                    <h5 class="page-title fs-21 mb-1">إضافة غرامة جديدة</h5>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">معلومات الغرامة</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.fines.store') }}" method="POST">
                                @csrf
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">الاستعارة (اختياري)</label>
                                        <select name="borrowing_id" class="form-select" id="borrowing_id">
                                            <option value="">اختر الاستعارة</option>
                                            @foreach($borrowings as $borrowing)
                                                <option value="{{ $borrowing->id }}" {{ old('borrowing_id') == $borrowing->id ? 'selected' : '' }}>
                                                    {{ $borrowing->book->title }} - {{ $borrowing->student->user->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('borrowing_id')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">الطالب <span class="text-danger">*</span></label>
                                        <select name="student_id" class="form-select" required id="student_id">
                                            <option value="">اختر الطالب</option>
                                            @foreach($students as $student)
                                                <option value="{{ $student->id }}" {{ old('student_id') == $student->id ? 'selected' : '' }}>
                                                    {{ $student->user->name }} - {{ $student->student_code }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('student_id')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">المبلغ <span class="text-danger">*</span></label>
                                        <input type="number" name="amount" class="form-control" step="0.01" min="0" value="{{ old('amount') }}" required>
                                        @error('amount')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">النوع <span class="text-danger">*</span></label>
                                        <select name="type" class="form-select" required>
                                            <option value="late_return" {{ old('type', 'late_return') == 'late_return' ? 'selected' : '' }}>تأخير في الإرجاع</option>
                                            <option value="damaged" {{ old('type') == 'damaged' ? 'selected' : '' }}>تلف الكتاب</option>
                                            <option value="lost" {{ old('type') == 'lost' ? 'selected' : '' }}>فقدان الكتاب</option>
                                        </select>
                                        @error('type')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">تاريخ الاستحقاق <span class="text-danger">*</span></label>
                                        <input type="date" name="due_date" class="form-control" value="{{ old('due_date', date('Y-m-d', strtotime('+30 days'))) }}" required>
                                        @error('due_date')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">السبب</label>
                                        <textarea name="reason" class="form-control" rows="3">{{ old('reason') }}</textarea>
                                        @error('reason')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">ملاحظات</label>
                                        <textarea name="notes" class="form-control" rows="3">{{ old('notes') }}</textarea>
                                        @error('notes')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-left: 5px; vertical-align: middle;">
                                            <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                                            <polyline points="17 21 17 13 7 13 7 21"></polyline>
                                            <polyline points="7 3 7 8 15 8"></polyline>
                                        </svg>
                                        حفظ
                                    </button>
                                    <a href="{{ route('admin.fines.index') }}" class="btn btn-secondary">إلغاء</a>
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

@push('scripts')
<script>
document.getElementById('borrowing_id').addEventListener('change', function() {
    const borrowingId = this.value;
    if (borrowingId) {
        // يمكن إضافة AJAX لجلب بيانات الطالب من الاستعارة
    }
});
</script>
@endpush

