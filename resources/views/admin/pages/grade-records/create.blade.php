@extends('admin.layouts.master')

@section('page-title')
    إدخال درجات جديدة
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
                    <h1>إدخال درجات جديدة</h1>
                    <p>اختر الفصل والمادة ثم أدخل درجات الطلاب</p>
                </div>
                <a href="{{ route('admin.grade-records.index') }}" class="admin-btn admin-btn-secondary">
                    <i class="ri-arrow-right-line"></i>
                    العودة للقائمة
                </a>
            </div>

            <div class="admin-page-card">
                <div class="admin-form-body">
                    <div class="admin-form-section">
                        <div class="admin-form-section-head">
                            <div class="admin-section-icon admin-section-icon-blue">
                                <i class="ri-filter-3-line"></i>
                            </div>
                            <div>
                                <h3>اختيار الفصل والمادة</h3>
                                <p>حدد الفصل والمادة لعرض قائمة الطلاب</p>
                            </div>
                        </div>

                        <form action="{{ route('admin.grade-records.create') }}" method="GET" class="admin-form" id="grade-records-select-form">
                            <div class="row g-3">
                                <div class="col-md-5">
                                    <div class="admin-form-field">
                                        <label class="admin-form-label">الفصل <span class="required">*</span></label>
                                        <select name="section_id" class="form-select" data-admin-choices required>
                                            <option value="">اختر الفصل</option>
                                            @foreach ($sections as $section)
                                                <option value="{{ $section->id }}" {{ request('section_id') == $section->id ? 'selected' : '' }}>
                                                    {{ $section->class->name }} - {{ $section->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="admin-form-field">
                                        <label class="admin-form-label">المادة <span class="required">*</span></label>
                                        <select name="subject_id" class="form-select" data-admin-choices required>
                                            <option value="">اختر المادة</option>
                                            @foreach ($subjects as $subject)
                                                <option value="{{ $subject->id }}" {{ request('subject_id') == $subject->id ? 'selected' : '' }}>
                                                    {{ $subject->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2 d-flex align-items-end">
                                    <button type="submit" class="admin-btn admin-btn-primary w-100">
                                        <i class="ri-group-line"></i>
                                        عرض الطلاب
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                    @if ($selectedSection && $selectedSubject && $students->count() > 0)
                        <form action="{{ route('admin.grade-records.store') }}" method="POST" class="admin-form" id="grades-form">
                            @csrf
                            <input type="hidden" name="section_id" value="{{ $selectedSection->id }}">
                            <input type="hidden" name="subject_id" value="{{ $selectedSubject->id }}">

                            <div class="admin-form-section">
                                <div class="admin-form-section-head">
                                    <div class="admin-section-icon admin-section-icon-green">
                                        <i class="ri-file-list-3-line"></i>
                                    </div>
                                    <div>
                                        <h3>بيانات التقييم</h3>
                                        <p>{{ $selectedSection->class->name }} - {{ $selectedSection->name }} | {{ $selectedSubject->name }} ({{ $students->count() }} طالب)</p>
                                    </div>
                                </div>

                                <div class="row g-3">
                                    <div class="col-md-3">
                                        <div class="admin-form-field">
                                            <label class="admin-form-label">المعلم</label>
                                            <select name="teacher_id" class="form-select" data-admin-choices>
                                                <option value="">اختر المعلم</option>
                                                @foreach ($teachers as $teacher)
                                                    <option value="{{ $teacher->id }}">{{ $teacher->user->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="admin-form-field">
                                            <label class="admin-form-label">نوع التقييم <span class="required">*</span></label>
                                            <select name="exam_type" class="form-select" data-admin-choices required>
                                                @foreach ($examTypes as $key => $name)
                                                    <option value="{{ $key }}">{{ $name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="admin-form-field">
                                            <label class="admin-form-label">اسم التقييم <span class="required">*</span></label>
                                            <input type="text" name="exam_name" class="form-control" placeholder="مثال: امتحان نهائي" required>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="admin-form-field">
                                            <label class="admin-form-label">الدرجة الكلية <span class="required">*</span></label>
                                            <input type="number" name="total_marks" class="form-control" step="0.01" min="0" value="100" required>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="admin-form-field">
                                            <label class="admin-form-label">تاريخ التقييم <span class="required">*</span></label>
                                            <input type="date" name="exam_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="admin-form-field">
                                            <label class="admin-form-label">السنة الدراسية <span class="required">*</span></label>
                                            <input type="text" name="academic_year" class="form-control" value="{{ $academicYear }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="admin-form-field">
                                            <label class="admin-form-label">الفصل الدراسي <span class="required">*</span></label>
                                            <select name="semester" class="form-select" data-admin-choices required>
                                                @foreach ($semesters as $key => $name)
                                                    <option value="{{ $key }}">{{ $name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="admin-form-field">
                                            <label class="admin-form-label">ملاحظات</label>
                                            <input type="text" name="notes" class="form-control" placeholder="اختياري">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="admin-form-section">
                                <div class="admin-form-section-head">
                                    <div class="admin-section-icon admin-section-icon-purple">
                                        <i class="ri-user-star-line"></i>
                                    </div>
                                    <div>
                                        <h3>درجات الطلاب</h3>
                                        <p>أدخل الدرجة المحصلة لكل طالب</p>
                                    </div>
                                </div>

                                <div class="admin-table-wrap">
                                    <div class="table-responsive">
                                        <table class="admin-data-table">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>اسم الطالب</th>
                                                    <th>الدرجة المحصلة</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($students as $index => $student)
                                                    <tr>
                                                        <td class="row-number">{{ $loop->iteration }}</td>
                                                        <td>
                                                            <strong>{{ $student->user->name ?? 'غير محدد' }}</strong>
                                                            <small class="text-muted d-block">{{ $student->student_code }}</small>
                                                        </td>
                                                        <td style="max-width: 180px;">
                                                            <input type="hidden" name="grades[{{ $index }}][student_id]" value="{{ $student->id }}">
                                                            <input type="number" name="grades[{{ $index }}][marks_obtained]"
                                                                   class="form-control marks-input" step="0.01" min="0" placeholder="0.00" required>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="admin-form-footer">
                                <a href="{{ route('admin.grade-records.index') }}" class="admin-btn admin-btn-secondary">
                                    <i class="ri-close-line"></i>
                                    إلغاء
                                </a>
                                <button type="submit" class="admin-btn admin-btn-primary">
                                    <i class="ri-save-line"></i>
                                    حفظ الدرجات
                                </button>
                            </div>
                        </form>

                    @elseif ($selectedSection && $selectedSubject && $students->count() == 0)
                        <div class="admin-empty-state">
                            <i class="ri-user-unfollow-line"></i>
                            لا يوجد طلاب نشطون في الفصل {{ $selectedSection->class->name }} - {{ $selectedSection->name }}
                        </div>
                    @else
                        <div class="admin-empty-state">
                            <i class="ri-arrow-up-line"></i>
                            اختر الفصل والمادة أولاً لعرض قائمة الطلاب
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
@stop

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (window.AdminTables && AdminTables.initAdminForm) {
            AdminTables.initAdminForm(document.getElementById('grade-records-select-form'));
            const gradesForm = document.getElementById('grades-form');
            if (gradesForm) AdminTables.initAdminForm(gradesForm);
        }

        const totalMarksInput = document.querySelector('input[name="total_marks"]');
        const marksInputs = document.querySelectorAll('.marks-input');

        marksInputs.forEach(function (input) {
            input.addEventListener('input', function () {
                const totalMarks = parseFloat(totalMarksInput?.value) || 0;
                const marksObtained = parseFloat(this.value) || 0;
                if (marksObtained > totalMarks) {
                    this.setCustomValidity('لا يمكن أن تتجاوز الدرجة الكلية (' + totalMarks + ')');
                } else {
                    this.setCustomValidity('');
                }
            });
        });

        if (totalMarksInput) {
            totalMarksInput.addEventListener('input', function () {
                marksInputs.forEach((input) => input.dispatchEvent(new Event('input')));
            });
        }
    });
</script>
@endpush
