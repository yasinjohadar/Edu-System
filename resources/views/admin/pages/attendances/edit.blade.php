@extends('admin.layouts.master')

@section('page-title')
    تعديل سجل الحضور
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
                    <h5 class="page-title fs-21 mb-1">تعديل سجل الحضور</h5>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">معلومات الطالب</h5>
                        </div>
                        <div class="card-body">
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <p><strong>اسم الطالب:</strong> {{ $attendance->student->user->name }}</p>
                                    <p><strong>رقم القيد:</strong> {{ $attendance->student->student_code }}</p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>الفصل:</strong> {{ $attendance->section->class->grade->name }} - {{ $attendance->section->class->name }} - {{ $attendance->section->name }}</p>
                                    <p><strong>التاريخ:</strong> {{ $attendance->date->format('Y-m-d') }}</p>
                                </div>
                            </div>

                            <form action="{{ route('admin.attendances.update', $attendance->id) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">حالة الحضور</label>
                                        <select name="status" class="form-select" required>
                                            <option value="present" {{ $attendance->status == 'present' ? 'selected' : '' }}>حاضر</option>
                                            <option value="absent" {{ $attendance->status == 'absent' ? 'selected' : '' }}>غائب</option>
                                            <option value="late" {{ $attendance->status == 'late' ? 'selected' : '' }}>متأخر</option>
                                            <option value="excused" {{ $attendance->status == 'excused' ? 'selected' : '' }}>معذور</option>
                                        </select>
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">وقت الحضور</label>
                                        <input type="time" name="check_in_time" class="form-control" 
                                               value="{{ $attendance->check_in_time ? \Carbon\Carbon::parse($attendance->check_in_time)->format('H:i') : '' }}">
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">وقت الانصراف</label>
                                        <input type="time" name="check_out_time" class="form-control" 
                                               value="{{ $attendance->check_out_time ? \Carbon\Carbon::parse($attendance->check_out_time)->format('H:i') : '' }}">
                                    </div>

                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">ملاحظات</label>
                                        <textarea name="notes" class="form-control" rows="3" placeholder="أدخل أي ملاحظات...">{{ $attendance->notes }}</textarea>
                                    </div>
                                </div>

                                <div class="mt-3">
                                    <button type="submit" class="btn btn-primary">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-left: 5px; vertical-align: middle;">
                                            <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                                            <polyline points="17 21 17 13 7 13 7 21"></polyline>
                                            <polyline points="7 3 7 8 15 8"></polyline>
                                        </svg>
                                        حفظ التعديلات
                                    </button>
                                    <a href="{{ route('admin.attendances.index', ['section_id' => $attendance->section_id, 'date' => $attendance->date->format('Y-m-d')]) }}" class="btn btn-secondary">إلغاء</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

