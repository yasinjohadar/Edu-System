@extends('admin.layouts.master')

@section('page-title')
    تسجيل حضور جديد
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
                    <h5 class="page-title fs-21 mb-1">تسجيل حضور جديد</h5>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">معلومات الحضور</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.lecture-attendance.store') }}" method="POST">
                                @csrf
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">المحاضرة <span class="text-danger">*</span></label>
                                        <select name="lecture_id" class="form-select" required>
                                            <option value="">اختر المحاضرة</option>
                                            @foreach($lectures as $lecture)
                                                <option value="{{ $lecture->id }}" {{ old('lecture_id') == $lecture->id ? 'selected' : '' }}>{{ $lecture->title }}</option>
                                            @endforeach
                                        </select>
                                        @error('lecture_id')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">الطالب <span class="text-danger">*</span></label>
                                        <select name="student_id" class="form-select" required>
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
                                        <label class="form-label">الحالة <span class="text-danger">*</span></label>
                                        <select name="status" class="form-select" required>
                                            <option value="present" {{ old('status', 'present') == 'present' ? 'selected' : '' }}>حاضر</option>
                                            <option value="absent" {{ old('status') == 'absent' ? 'selected' : '' }}>غائب</option>
                                            <option value="late" {{ old('status') == 'late' ? 'selected' : '' }}>متأخر</option>
                                            <option value="excused" {{ old('status') == 'excused' ? 'selected' : '' }}>معذور</option>
                                        </select>
                                        @error('status')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">وقت الانضمام</label>
                                        <input type="datetime-local" name="joined_at" class="form-control" value="{{ old('joined_at') }}">
                                        @error('joined_at')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">وقت المغادرة</label>
                                        <input type="datetime-local" name="left_at" class="form-control" value="{{ old('left_at') }}">
                                        @error('left_at')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">مدة الحضور (بالدقائق)</label>
                                        <input type="number" name="duration_minutes" class="form-control" min="0" value="{{ old('duration_minutes') }}">
                                        @error('duration_minutes')
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
                                    <a href="{{ route('admin.lecture-attendance.index') }}" class="btn btn-secondary">إلغاء</a>
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

