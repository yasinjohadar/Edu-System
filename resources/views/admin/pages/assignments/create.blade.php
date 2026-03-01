@extends('admin.layouts.master')

@section('page-title')
    إضافة واجب جديد
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
                    <h5 class="page-title fs-21 mb-1">إضافة واجب جديد</h5>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">معلومات الواجب</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.assignments.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">المادة الدراسية <span class="text-danger">*</span></label>
                                        <select name="subject_id" class="form-select" required>
                                            <option value="">اختر المادة</option>
                                            @foreach($subjects as $subject)
                                                <option value="{{ $subject->id }}" {{ old('subject_id') == $subject->id ? 'selected' : '' }}>{{ $subject->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('subject_id')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">المعلم <span class="text-danger">*</span></label>
                                        <select name="teacher_id" class="form-select" required>
                                            <option value="">اختر المعلم</option>
                                            @foreach($teachers as $teacher)
                                                <option value="{{ $teacher->id }}" {{ old('teacher_id') == $teacher->id ? 'selected' : '' }}>{{ $teacher->user->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('teacher_id')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">الفصل الدراسي</label>
                                        <select name="section_id" class="form-select">
                                            <option value="">كل الفصول</option>
                                            @foreach($sections as $section)
                                                <option value="{{ $section->id }}" {{ old('section_id') == $section->id ? 'selected' : '' }}>{{ $section->name }} - {{ $section->class->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('section_id')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">العنوان <span class="text-danger">*</span></label>
                                        <input type="text" name="title" class="form-control" value="{{ old('title') }}" required>
                                        @error('title')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">الوصف</label>
                                        <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
                                        @error('description')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">التعليمات</label>
                                        <textarea name="instructions" class="form-control" rows="4">{{ old('instructions') }}</textarea>
                                        @error('instructions')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">الدرجة الكلية <span class="text-danger">*</span></label>
                                        <input type="number" name="total_marks" class="form-control" step="0.01" min="1" value="{{ old('total_marks') }}" required>
                                        @error('total_marks')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">تاريخ الاستحقاق <span class="text-danger">*</span></label>
                                        <input type="date" name="due_date" class="form-control" value="{{ old('due_date') }}" required>
                                        @error('due_date')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">وقت الاستحقاق <span class="text-danger">*</span></label>
                                        <input type="time" name="due_time" class="form-control" value="{{ old('due_time', '23:59') }}" required>
                                        @error('due_time')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="allow_late_submission" value="1" {{ old('allow_late_submission', true) ? 'checked' : '' }}>
                                            <label class="form-check-label">السماح بالتسليم المتأخر</label>
                                        </div>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">غرامة التأخير لكل يوم</label>
                                        <input type="number" name="late_penalty_per_day" class="form-control" step="0.01" min="0" value="{{ old('late_penalty_per_day', 0) }}">
                                        @error('late_penalty_per_day')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">أقصى أيام تأخير مسموحة</label>
                                        <input type="number" name="max_late_days" class="form-control" min="1" value="{{ old('max_late_days') }}">
                                        @error('max_late_days')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">عدد المحاولات المسموحة</label>
                                        <input type="number" name="max_attempts" class="form-control" min="1" max="10" value="{{ old('max_attempts') }}" placeholder="اتركه فارغاً لغير محدود">
                                        @error('max_attempts')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="allow_resubmission" value="1" {{ old('allow_resubmission') ? 'checked' : '' }}>
                                            <label class="form-check-label">السماح بإعادة التسليم بعد التصحيح</label>
                                        </div>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">آخر موعد لإعادة التسليم</label>
                                        <input type="date" name="resubmission_deadline" class="form-control" value="{{ old('resubmission_deadline') }}">
                                        @error('resubmission_deadline')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">أنواع التسليم المسموحة <span class="text-danger">*</span></label>
                                        <div class="d-flex gap-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="submission_types[]" value="file" id="type_file" {{ in_array('file', old('submission_types', ['file', 'text', 'link'])) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="type_file">ملفات</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="submission_types[]" value="text" id="type_text" {{ in_array('text', old('submission_types', ['file', 'text', 'link'])) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="type_text">نصوص</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="submission_types[]" value="link" id="type_link" {{ in_array('link', old('submission_types', ['file', 'text', 'link'])) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="type_link">روابط</label>
                                            </div>
                                        </div>
                                        @error('submission_types')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">الحالة <span class="text-danger">*</span></label>
                                        <select name="status" class="form-select" required>
                                            <option value="draft" {{ old('status', 'draft') == 'draft' ? 'selected' : '' }}>مسودة</option>
                                            <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>منشور</option>
                                            <option value="closed" {{ old('status') == 'closed' ? 'selected' : '' }}>مغلق</option>
                                        </select>
                                        @error('status')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <div class="form-check mt-4">
                                            <input class="form-check-input" type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                            <label class="form-check-label">نشط</label>
                                        </div>
                                    </div>

                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">المرفقات</label>
                                        <input type="file" name="attachments[]" class="form-control" multiple accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.zip,.rar,image/*">
                                        <small class="text-muted">يمكن رفع ملفات متعددة (حجم كل ملف: 10MB كحد أقصى)</small>
                                        @error('attachments.*')
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
                                    <a href="{{ route('admin.assignments.index') }}" class="btn btn-secondary">إلغاء</a>
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

