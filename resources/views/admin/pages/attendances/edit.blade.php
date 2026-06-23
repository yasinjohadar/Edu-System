@extends('admin.layouts.master')

@section('page-title')
    تعديل سجل الحضور
@stop

@section('content')
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li class="small">{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="main-content app-content">
        <div class="container-fluid">

            <div class="admin-page-header">
                <div class="page-title-wrap">
                    <h1>تعديل سجل الحضور</h1>
                    <p>{{ $attendance->student->user->name }} — {{ $attendance->date->format('Y-m-d') }}</p>
                </div>
                <a href="{{ route('admin.attendances.index', ['section_id' => $attendance->section_id, 'date' => $attendance->date->format('Y-m-d')]) }}" class="admin-btn admin-btn-secondary">
                    <i class="ri-arrow-right-line"></i>
                    العودة للقائمة
                </a>
            </div>

            <div class="admin-page-card">
                <form action="{{ route('admin.attendances.update', $attendance->id) }}" method="POST" class="admin-form" id="attendance-edit-form">
                    @csrf
                    @method('PUT')

                    <div class="admin-form-body">
                        <div class="admin-form-section">
                            <div class="admin-form-section-head">
                                <div class="admin-section-icon admin-section-icon-blue">
                                    <i class="ri-user-line"></i>
                                </div>
                                <div>
                                    <h3>معلومات الطالب</h3>
                                    <p>بيانات الطالب والفصل</p>
                                </div>
                            </div>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="admin-form-field">
                                        <label class="admin-form-label">اسم الطالب</label>
                                        <input type="text" class="form-control" value="{{ $attendance->student->user->name }}" disabled>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="admin-form-field">
                                        <label class="admin-form-label">رقم القيد</label>
                                        <input type="text" class="form-control" value="{{ $attendance->student->student_code }}" disabled>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="admin-form-field">
                                        <label class="admin-form-label">الفصل</label>
                                        <input type="text" class="form-control"
                                               value="{{ $attendance->section->class->grade->name }} - {{ $attendance->section->class->name }} - {{ $attendance->section->name }}" disabled>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="admin-form-field">
                                        <label class="admin-form-label">التاريخ</label>
                                        <input type="text" class="form-control" value="{{ $attendance->date->format('Y-m-d') }}" disabled>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="admin-form-section">
                            <div class="admin-form-section-head">
                                <div class="admin-section-icon admin-section-icon-green">
                                    <i class="ri-calendar-check-line"></i>
                                </div>
                                <div>
                                    <h3>بيانات الحضور</h3>
                                    <p>الحالة والأوقات والملاحظات</p>
                                </div>
                            </div>

                            <div class="row g-3">
                                <div class="col-md-4">
                                    <div class="admin-form-field">
                                        <label class="admin-form-label">حالة الحضور <span class="required">*</span></label>
                                        <select name="status" class="form-select @error('status') is-invalid @enderror" data-admin-choices required>
                                            <option value="present" {{ $attendance->status == 'present' ? 'selected' : '' }}>حاضر</option>
                                            <option value="absent" {{ $attendance->status == 'absent' ? 'selected' : '' }}>غائب</option>
                                            <option value="late" {{ $attendance->status == 'late' ? 'selected' : '' }}>متأخر</option>
                                            <option value="excused" {{ $attendance->status == 'excused' ? 'selected' : '' }}>معذور</option>
                                        </select>
                                        @error('status')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="admin-form-field">
                                        <label class="admin-form-label">وقت الحضور</label>
                                        <input type="time" name="check_in_time" class="form-control @error('check_in_time') is-invalid @enderror"
                                               value="{{ $attendance->check_in_time ? \Carbon\Carbon::parse($attendance->check_in_time)->format('H:i') : '' }}">
                                        @error('check_in_time')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="admin-form-field">
                                        <label class="admin-form-label">وقت الانصراف</label>
                                        <input type="time" name="check_out_time" class="form-control @error('check_out_time') is-invalid @enderror"
                                               value="{{ $attendance->check_out_time ? \Carbon\Carbon::parse($attendance->check_out_time)->format('H:i') : '' }}">
                                        @error('check_out_time')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="admin-form-field">
                                        <label class="admin-form-label">ملاحظات</label>
                                        <textarea name="notes" class="form-control @error('notes') is-invalid @enderror"
                                                  rows="3" placeholder="أدخل أي ملاحظات...">{{ old('notes', $attendance->notes) }}</textarea>
                                        @error('notes')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="admin-form-footer">
                        <a href="{{ route('admin.attendances.index', ['section_id' => $attendance->section_id, 'date' => $attendance->date->format('Y-m-d')]) }}" class="admin-btn admin-btn-secondary">
                            <i class="ri-close-line"></i>
                            إلغاء
                        </a>
                        <button type="submit" class="admin-btn admin-btn-primary">
                            <i class="ri-save-line"></i>
                            حفظ التعديلات
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
@stop

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (window.AdminTables && AdminTables.initAdminForm) {
            AdminTables.initAdminForm(document.getElementById('attendance-edit-form'));
        }
    });
</script>
@endpush
