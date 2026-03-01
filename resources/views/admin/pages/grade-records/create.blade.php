@extends('admin.layouts.master')

@section('page-title')
    إدخال درجات جديدة
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
                    <h5 class="page-title fs-21 mb-1">إدخال درجات جديدة</h5>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">اختر الفصل والمادة</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.grade-records.create') }}" method="GET" class="mb-4">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="form-label">اختر الفصل</label>
                                        <select name="section_id" class="form-select" required>
                                            <option value="">-- اختر الفصل --</option>
                                            @foreach($sections as $section)
                                                <option value="{{ $section->id }}" {{ request('section_id') == $section->id ? 'selected' : '' }}>
                                                    {{ $section->class->name }} - {{ $section->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">اختر المادة</label>
                                        <select name="subject_id" class="form-select" required>
                                            <option value="">-- اختر المادة --</option>
                                            @foreach($subjects as $subject)
                                                <option value="{{ $subject->id }}" {{ request('subject_id') == $subject->id ? 'selected' : '' }}>
                                                    {{ $subject->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <button type="submit" class="btn btn-primary">عرض الطلاب</button>
                                </div>
                            </form>

                            @if($selectedSection && $selectedSubject && $students->count() > 0)
                                <form action="{{ route('admin.grade-records.store') }}" method="POST" id="gradesForm">
                                    @csrf
                                    <input type="hidden" name="section_id" value="{{ $selectedSection->id }}">
                                    <input type="hidden" name="subject_id" value="{{ $selectedSubject->id }}">
                                    
                                    <div class="row mb-3">
                                        <div class="col-md-3">
                                            <label class="form-label">المعلم</label>
                                            <select name="teacher_id" class="form-select">
                                                <option value="">-- اختر المعلم --</option>
                                                @foreach($teachers as $teacher)
                                                    <option value="{{ $teacher->id }}">
                                                        {{ $teacher->user->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">نوع التقييم <span class="text-danger">*</span></label>
                                            <select name="exam_type" class="form-select" required>
                                                @foreach($examTypes as $key => $name)
                                                    <option value="{{ $key }}">{{ $name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">اسم التقييم <span class="text-danger">*</span></label>
                                            <input type="text" name="exam_name" class="form-control" placeholder="مثال: اختبار الفصل الأول" required>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">الدرجة الكلية <span class="text-danger">*</span></label>
                                            <input type="number" name="total_marks" class="form-control" step="0.01" min="0" placeholder="100" required>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-md-3">
                                            <label class="form-label">تاريخ التقييم <span class="text-danger">*</span></label>
                                            <input type="date" name="exam_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">السنة الدراسية <span class="text-danger">*</span></label>
                                            <input type="text" name="academic_year" class="form-control" value="{{ $academicYear }}" placeholder="2024-2025" required>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">الفصل الدراسي <span class="text-danger">*</span></label>
                                            <select name="semester" class="form-select" required>
                                                @foreach($semesters as $key => $name)
                                                    <option value="{{ $key }}">{{ $name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">ملاحظات</label>
                                            <input type="text" name="notes" class="form-control" placeholder="ملاحظات (اختياري)">
                                        </div>
                                    </div>

                                    <div class="alert alert-info mb-3">
                                        <strong>الفصل المحدد:</strong> {{ $selectedSection->class->name }} - {{ $selectedSection->name }}
                                        <br>
                                        <strong>المادة:</strong> {{ $selectedSubject->name }}
                                        <br>
                                        <strong>عدد الطلاب:</strong> {{ $students->count() }} طالب
                                    </div>

                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover">
                                            <thead class="table-light">
                                                <tr>
                                                    <th style="width: 50px;">#</th>
                                                    <th>اسم الطالب</th>
                                                    <th style="width: 200px;">الدرجة المحصل عليها</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($students as $index => $student)
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>
                                                            <strong>{{ $student->user->name ?? 'غير محدد' }}</strong>
                                                            <br>
                                                            <small class="text-muted">{{ $student->student_code }}</small>
                                                        </td>
                                                        <td>
                                                            <input type="hidden" name="grades[{{ $index }}][student_id]" value="{{ $student->id }}">
                                                            <input type="number" 
                                                                   name="grades[{{ $index }}][marks_obtained]" 
                                                                   class="form-control form-control-sm marks-input" 
                                                                   step="0.01" 
                                                                   min="0" 
                                                                   placeholder="0.00"
                                                                   required>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="mt-3">
                                        <button type="submit" class="btn btn-primary">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-left: 5px; vertical-align: middle;">
                                                <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                                                <polyline points="17 21 17 13 7 13 7 21"></polyline>
                                                <polyline points="7 3 7 8 15 8"></polyline>
                                            </svg>
                                            حفظ الدرجات
                                        </button>
                                        <a href="{{ route('admin.grade-records.index') }}" class="btn btn-secondary">إلغاء</a>
                                    </div>
                                </form>
                            @elseif($selectedSection && $selectedSubject && $students->count() == 0)
                                <div class="alert alert-warning">
                                    <h5 class="alert-heading">لا يوجد طلاب في هذا الفصل</h5>
                                    <p class="mb-0">
                                        الفصل المحدد: <strong>{{ $selectedSection->class->name }} - {{ $selectedSection->name }}</strong>
                                        <br>
                                        المادة: <strong>{{ $selectedSubject->name }}</strong>
                                        <br>
                                        لا يوجد طلاب نشطين مسجلين في هذا الفصل.
                                    </p>
                                </div>
                            @else
                                <div class="alert alert-info">
                                    <p class="mb-0">يرجى اختيار الفصل والمادة أولاً لعرض قائمة الطلاب</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const totalMarksInput = document.querySelector('input[name="total_marks"]');
    const marksInputs = document.querySelectorAll('.marks-input');
    
    // التحقق من أن الدرجة المحصل عليها لا تتجاوز الدرجة الكلية
    marksInputs.forEach(function(input) {
        input.addEventListener('input', function() {
            const totalMarks = parseFloat(totalMarksInput.value) || 0;
            const marksObtained = parseFloat(this.value) || 0;
            
            if (marksObtained > totalMarks) {
                this.setCustomValidity('الدرجة المحصل عليها لا يمكن أن تتجاوز الدرجة الكلية (' + totalMarks + ')');
            } else {
                this.setCustomValidity('');
            }
        });
    });
    
    // تحديث التحقق عند تغيير الدرجة الكلية
    if (totalMarksInput) {
        totalMarksInput.addEventListener('input', function() {
            marksInputs.forEach(function(input) {
                input.dispatchEvent(new Event('input'));
            });
        });
    }
});
</script>
@endpush

