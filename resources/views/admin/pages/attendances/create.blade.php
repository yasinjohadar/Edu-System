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
                            <h5 class="card-title">اختر الفصل والتاريخ</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.attendances.create') }}" method="GET" class="mb-4">
                                <div class="row">
                                    <div class="col-md-4">
                                        <label class="form-label">اختر الفصل</label>
                                        <select name="section_id" class="form-select" required onchange="this.form.submit()">
                                            <option value="">-- اختر الفصل --</option>
                                            @foreach($sections as $section)
                                                <option value="{{ $section->id }}" {{ request('section_id') == $section->id ? 'selected' : '' }}>
                                                    {{ $section->class->grade->name }} - {{ $section->class->name }} - {{ $section->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </form>

                            @if($selectedSection)
                                @if($students->count() > 0)
                                    <form action="{{ route('admin.attendances.store') }}" method="POST" id="attendanceForm">
                                        @csrf
                                        <input type="hidden" name="section_id" value="{{ $selectedSection->id }}">
                                        
                                        <div class="row mb-3">
                                            <div class="col-md-4">
                                                <label class="form-label">التاريخ</label>
                                                <input type="date" name="date" class="form-control" value="{{ date('Y-m-d') }}" required>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="alert alert-info mb-0">
                                                    <strong>الفصل المحدد:</strong> {{ $selectedSection->class->grade->name }} - {{ $selectedSection->class->name }} - {{ $selectedSection->name }}
                                                    <br>
                                                    <strong>عدد الطلاب:</strong> {{ $students->count() }} طالب
                                                </div>
                                            </div>
                                        </div>
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-hover">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th style="width: 50px;">#</th>
                                                        <th>اسم الطالب</th>
                                                        <th style="width: 150px;">الحالة</th>
                                                        <th style="width: 150px;">وقت الحضور</th>
                                                        <th>ملاحظات</th>
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
                                                                <input type="hidden" name="attendances[{{ $index }}][student_id]" value="{{ $student->id }}">
                                                                <select name="attendances[{{ $index }}][status]" class="form-select form-select-sm" required>
                                                                    <option value="present">حاضر</option>
                                                                    <option value="absent" selected>غائب</option>
                                                                    <option value="late">متأخر</option>
                                                                    <option value="excused">معذور</option>
                                                                </select>
                                                            </td>
                                                            <td>
                                                                <input type="time" name="attendances[{{ $index }}][check_in_time]" class="form-control form-control-sm" value="08:00">
                                                            </td>
                                                            <td>
                                                                <input type="text" name="attendances[{{ $index }}][notes]" class="form-control form-control-sm" placeholder="ملاحظات (اختياري)">
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-hover">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th style="width: 50px;">#</th>
                                                        <th>اسم الطالب</th>
                                                        <th style="width: 150px;">الحالة</th>
                                                        <th style="width: 150px;">وقت الحضور</th>
                                                        <th>ملاحظات</th>
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
                                                                <input type="hidden" name="attendances[{{ $index }}][student_id]" value="{{ $student->id }}">
                                                                <select name="attendances[{{ $index }}][status]" class="form-select form-select-sm" required>
                                                                    <option value="present">حاضر</option>
                                                                    <option value="absent" selected>غائب</option>
                                                                    <option value="late">متأخر</option>
                                                                    <option value="excused">معذور</option>
                                                                </select>
                                                            </td>
                                                            <td>
                                                                <input type="time" name="attendances[{{ $index }}][check_in_time]" class="form-control form-control-sm" value="08:00">
                                                            </td>
                                                            <td>
                                                                <input type="text" name="attendances[{{ $index }}][notes]" class="form-control form-control-sm" placeholder="ملاحظات (اختياري)">
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
                                                حفظ الحضور
                                            </button>
                                            <a href="{{ route('admin.attendances.index') }}" class="btn btn-secondary">إلغاء</a>
                                        </div>
                                    </form>
                                @else
                                    <div class="alert alert-warning">
                                        <h5 class="alert-heading">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-left: 5px; vertical-align: middle;">
                                                <circle cx="12" cy="12" r="10"></circle>
                                                <line x1="12" y1="8" x2="12" y2="12"></line>
                                                <line x1="12" y1="16" x2="12.01" y2="16"></line>
                                            </svg>
                                            لا يوجد طلاب في هذا الفصل
                                        </h5>
                                        <p class="mb-0">
                                            الفصل المحدد: <strong>{{ $selectedSection->class->grade->name }} - {{ $selectedSection->class->name }} - {{ $selectedSection->name }}</strong>
                                            <br>
                                            لا يوجد طلاب نشطين مسجلين في هذا الفصل. يرجى التأكد من:
                                            <ul class="mt-2 mb-0">
                                                <li>تسجيل الطلاب في هذا الفصل</li>
                                                <li>أن حالة الطلاب هي "نشط" (active)</li>
                                                <li>أن الطلاب لديهم section_id محدد</li>
                                            </ul>
                                        </p>
                                    </div>
                                @endif
                            @else
                                <div class="alert alert-info">
                                    <p class="mb-0">يرجى اختيار الفصل أولاً لعرض قائمة الطلاب</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('js')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // تحديث وقت الحضور تلقائياً عند تغيير الحالة
    document.querySelectorAll('select[name*="[status]"]').forEach(function(select) {
        select.addEventListener('change', function() {
            const row = this.closest('tr');
            const timeInput = row.querySelector('input[type="time"]');
            
            if (this.value === 'present' || this.value === 'late') {
                if (!timeInput.value) {
                    const now = new Date();
                    const hours = String(now.getHours()).padStart(2, '0');
                    const minutes = String(now.getMinutes()).padStart(2, '0');
                    timeInput.value = hours + ':' + minutes;
                }
            } else {
                timeInput.value = '';
            }
        });
    });
});
</script>
@stop

