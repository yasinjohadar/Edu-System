@extends('admin.layouts.master')

@section('page-title')
    تعديل الطالب
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
                    <h5 class="page-title fs-21 mb-1">تعديل الطالب</h5>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">بيانات الطالب</h4>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.students.update', $student->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                
                                <!-- البيانات الأساسية -->
                                <h5 class="mb-3">البيانات الأساسية</h5>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">الاسم الكامل <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="name" value="{{ old('name', $student->user->name) }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">البريد الإلكتروني <span class="text-danger">*</span></label>
                                            <input type="email" class="form-control" name="email" value="{{ old('email', $student->user->email) }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">رقم الهاتف</label>
                                            <input type="text" class="form-control" name="phone" value="{{ old('phone', $student->user->phone) }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">رقم القيد <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="student_code" value="{{ old('student_code', $student->student_code) }}" required>
                                        </div>
                                    </div>
                                </div>

                                <!-- البيانات الشخصية -->
                                <h5 class="mb-3 mt-4">البيانات الشخصية</h5>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">تاريخ الميلاد</label>
                                            <input type="date" class="form-control" name="date_of_birth" value="{{ old('date_of_birth', $student->date_of_birth?->format('Y-m-d')) }}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">الجنس</label>
                                            <select class="form-control" name="gender">
                                                <option value="">اختر</option>
                                                <option value="male" {{ old('gender', $student->gender) == 'male' ? 'selected' : '' }}>ذكر</option>
                                                <option value="female" {{ old('gender', $student->gender) == 'female' ? 'selected' : '' }}>أنثى</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">تاريخ التسجيل</label>
                                            <input type="date" class="form-control" name="enrollment_date" value="{{ old('enrollment_date', $student->enrollment_date?->format('Y-m-d')) }}">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label class="form-label">العنوان</label>
                                            <textarea class="form-control" name="address" rows="2">{{ old('address', $student->address) }}</textarea>
                                        </div>
                                    </div>
                                </div>

                                <!-- البيانات الأكاديمية -->
                                <h5 class="mb-3 mt-4">البيانات الأكاديمية</h5>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">الصف</label>
                                            <select class="form-control" name="class_id" id="class_id">
                                                <option value="">اختر الصف</option>
                                                @foreach($classes as $class)
                                                    <option value="{{ $class->id }}" {{ old('class_id', $student->class_id) == $class->id ? 'selected' : '' }}>
                                                        {{ $class->grade->name }} - {{ $class->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">الفصل</label>
                                            <select class="form-control" name="section_id" id="section_id">
                                                <option value="">اختر الفصل</option>
                                                @foreach($sections as $section)
                                                    <option value="{{ $section->id }}" 
                                                            data-class-id="{{ $section->class_id }}"
                                                            {{ old('section_id', $student->section_id) == $section->id ? 'selected' : '' }}>
                                                        {{ $section->class->grade->name }} - {{ $section->class->name }} - {{ $section->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">الحالة <span class="text-danger">*</span></label>
                                            <select class="form-control" name="status" required>
                                                <option value="active" {{ old('status', $student->status) == 'active' ? 'selected' : '' }}>نشط</option>
                                                <option value="graduated" {{ old('status', $student->status) == 'graduated' ? 'selected' : '' }}>متخرج</option>
                                                <option value="transferred" {{ old('status', $student->status) == 'transferred' ? 'selected' : '' }}>منقول</option>
                                                <option value="suspended" {{ old('status', $student->status) == 'suspended' ? 'selected' : '' }}>معلق</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <!-- أولياء الأمور -->
                                <h5 class="mb-3 mt-4">أولياء الأمور</h5>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">اسم ولي الأمر الأساسي</label>
                                            <input type="text" class="form-control" name="parent_guardian" value="{{ old('parent_guardian', $student->parent_guardian) }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">جهة الاتصال في الطوارئ</label>
                                            <input type="text" class="form-control" name="emergency_contact" value="{{ old('emergency_contact', $student->emergency_contact) }}">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label class="form-label">ربط بأولياء الأمور المسجلين</label>
                                            <select class="form-control" name="parent_ids[]" multiple style="height: 120px;">
                                                @foreach($parents as $parent)
                                                    <option value="{{ $parent->id }}" 
                                                            {{ in_array($parent->id, old('parent_ids', $student->parents->pluck('id')->toArray())) ? 'selected' : '' }}>
                                                        {{ $parent->user->name }} - {{ $parent->parent_code }} ({{ $parent->relationship }})
                                                    </option>
                                                @endforeach
                                            </select>
                                            <small class="text-muted">اضغط Ctrl (أو Cmd على Mac) لاختيار أكثر من ولي أمر</small>
                                        </div>
                                    </div>
                                </div>

                                <!-- الملفات -->
                                <h5 class="mb-3 mt-4">الملفات</h5>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">صورة الطالب</label>
                                            @if($student->photo)
                                                <div class="mb-2">
                                                    <img src="{{ asset('storage/' . $student->photo) }}" alt="صورة الطالب" class="img-thumbnail" style="max-width: 100px;">
                                                </div>
                                            @endif
                                            <input type="file" class="form-control" name="photo" accept="image/*">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">شهادة الميلاد</label>
                                            @if($student->birth_certificate)
                                                <div class="mb-2">
                                                    <a href="{{ asset('storage/' . $student->birth_certificate) }}" target="_blank" class="btn btn-sm btn-info">عرض الملف</a>
                                                </div>
                                            @endif
                                            <input type="file" class="form-control" name="birth_certificate" accept=".pdf,.jpg,.jpeg,.png">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">الشهادة الصحية</label>
                                            @if($student->health_certificate)
                                                <div class="mb-2">
                                                    <a href="{{ asset('storage/' . $student->health_certificate) }}" target="_blank" class="btn btn-sm btn-info">عرض الملف</a>
                                                </div>
                                            @endif
                                            <input type="file" class="form-control" name="health_certificate" accept=".pdf,.jpg,.jpeg,.png">
                                        </div>
                                    </div>
                                </div>

                                <!-- ملاحظات -->
                                <h5 class="mb-3 mt-4">ملاحظات</h5>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label class="form-label">ملاحظات طبية</label>
                                            <textarea class="form-control" name="medical_notes" rows="3">{{ old('medical_notes', $student->medical_notes) }}</textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-4">
                                    <button type="submit" class="btn btn-primary">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-left: 5px; vertical-align: middle;">
                                            <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                                            <polyline points="17 21 17 13 7 13 7 21"></polyline>
                                            <polyline points="7 3 7 8 15 8"></polyline>
                                        </svg>
                                        حفظ التعديلات
                                    </button>
                                    <a href="{{ route('admin.students.index') }}" class="btn btn-secondary">إلغاء</a>
                                </div>
                            </form>
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
    const classSelect = document.getElementById('class_id');
    const sectionSelect = document.getElementById('section_id');
    const allSections = Array.from(sectionSelect.options);

    function filterSections() {
        const selectedClassId = classSelect.value;
        const currentSectionId = sectionSelect.value;
        
        // إزالة جميع الخيارات ما عدا الخيار الأول
        sectionSelect.innerHTML = '<option value="">اختر الفصل</option>';
        
        if (selectedClassId) {
            // إضافة الفصول التي تنتمي للصف المحدد
            allSections.forEach(option => {
                if (option.value && option.dataset.classId == selectedClassId) {
                    const newOption = option.cloneNode(true);
                    sectionSelect.appendChild(newOption);
                }
            });
        } else {
            // إذا لم يتم اختيار صف، عرض جميع الفصول
            allSections.forEach(option => {
                if (option.value) {
                    sectionSelect.appendChild(option.cloneNode(true));
                }
            });
        }
        
        // إعادة تحديد الفصل الحالي إذا كان موجوداً
        if (currentSectionId) {
            sectionSelect.value = currentSectionId;
        }
    }

    classSelect.addEventListener('change', filterSections);
    
    // تطبيق الفلترة عند تحميل الصفحة
    filterSections();
});
</script>
@stop

