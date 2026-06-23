@extends('admin.layouts.master')

@section('page-title')
    تسجيل حضور جديد
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
                    <h1>تسجيل حضور جديد</h1>
                    <p>اختر الفصل وسجّل حضور الطلاب</p>
                </div>
                <a href="{{ route('admin.attendances.index') }}" class="admin-btn admin-btn-secondary">
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
                                <h3>اختر الفصل</h3>
                                <p>حدد الفصل لعرض قائمة الطلاب</p>
                            </div>
                        </div>

                        <form action="{{ route('admin.attendances.create') }}" method="GET" id="attendance-section-form">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="admin-form-field">
                                        <label class="admin-form-label">الفصل الدراسي</label>
                                        <select name="section_id" class="form-select" data-admin-choices required onchange="this.form.submit()">
                                            <option value="">— اختر الفصل —</option>
                                            @foreach ($sections as $section)
                                                <option value="{{ $section->id }}" {{ request('section_id') == $section->id ? 'selected' : '' }}>
                                                    {{ $section->class->grade->name }} - {{ $section->class->name }} - {{ $section->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                    @if ($selectedSection)
                        @if ($students->count() > 0)
                            <form action="{{ route('admin.attendances.store') }}" method="POST" class="admin-form" id="attendance-create-form">
                                @csrf
                                <input type="hidden" name="section_id" value="{{ $selectedSection->id }}">

                                <div class="admin-form-section">
                                    <div class="admin-form-section-head">
                                        <div class="admin-section-icon admin-section-icon-green">
                                            <i class="ri-calendar-check-line"></i>
                                        </div>
                                        <div>
                                            <h3>تسجيل الحضور</h3>
                                            <p>{{ $selectedSection->class->grade->name }} - {{ $selectedSection->class->name }} - {{ $selectedSection->name }} ({{ $students->count() }} طالب)</p>
                                        </div>
                                    </div>

                                    <div class="row g-3 mb-3">
                                        <div class="col-md-4">
                                            <div class="admin-form-field">
                                                <label class="admin-form-label">التاريخ</label>
                                                <input type="date" name="date" class="form-control" value="{{ date('Y-m-d') }}" required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="table-responsive">
                                        <table class="admin-data-table">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>اسم الطالب</th>
                                                    <th>الحالة</th>
                                                    <th>وقت الحضور</th>
                                                    <th>ملاحظات</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($students as $index => $student)
                                                    <tr>
                                                        <th scope="row" class="row-number">{{ $loop->iteration }}</th>
                                                        <td>
                                                            <strong class="d-block">{{ $student->user->name ?? 'غير محدد' }}</strong>
                                                            <small class="text-muted">{{ $student->student_code }}</small>
                                                        </td>
                                                        <td>
                                                            <input type="hidden" name="attendances[{{ $index }}][student_id]" value="{{ $student->id }}">
                                                            <select name="attendances[{{ $index }}][status]" class="form-select form-select-sm" data-admin-choices required>
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
                                </div>

                                <div class="admin-form-footer">
                                    <a href="{{ route('admin.attendances.index') }}" class="admin-btn admin-btn-secondary">
                                        <i class="ri-close-line"></i>
                                        إلغاء
                                    </a>
                                    <button type="submit" class="admin-btn admin-btn-primary">
                                        <i class="ri-save-line"></i>
                                        حفظ الحضور
                                    </button>
                                </div>
                            </form>
                        @else
                            <div class="alert alert-warning mb-0">
                                <strong>لا يوجد طلاب في هذا الفصل</strong>
                                <p class="mb-0 mt-2">
                                    الفصل: {{ $selectedSection->class->grade->name }} - {{ $selectedSection->class->name }} - {{ $selectedSection->name }}
                                </p>
                            </div>
                        @endif
                    @else
                        <div class="alert alert-info mb-0">
                            يرجى اختيار الفصل أولاً لعرض قائمة الطلاب
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
        const sectionForm = document.getElementById('attendance-section-form');
        if (sectionForm && window.AdminTables && AdminTables.initAdminForm) {
            AdminTables.initAdminForm(sectionForm);
        }

        const createForm = document.getElementById('attendance-create-form');
        if (createForm && window.AdminTables && AdminTables.initAdminForm) {
            AdminTables.initAdminForm(createForm);
        }

        document.querySelectorAll('select[name*="[status]"]').forEach(function (select) {
            select.addEventListener('change', function () {
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
@endpush
