@extends('admin.layouts.master')

@section('page-title')
    تعديل المعلم
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
                    <h5 class="page-title fs-21 mb-1">تعديل المعلم</h5>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">بيانات المعلم</h4>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.teachers.update', $teacher->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">الاسم الكامل <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="name" value="{{ old('name', $teacher->user->name) }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">البريد الإلكتروني <span class="text-danger">*</span></label>
                                            <input type="email" class="form-control" name="email" value="{{ old('email', $teacher->user->email) }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">رقم الهاتف</label>
                                            <input type="text" class="form-control" name="phone" value="{{ old('phone', $teacher->user->phone) }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">رقم المعلم <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="teacher_code" value="{{ old('teacher_code', $teacher->teacher_code) }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">تاريخ الميلاد</label>
                                            <input type="date" class="form-control" name="date_of_birth" value="{{ old('date_of_birth', $teacher->date_of_birth?->format('Y-m-d')) }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">الجنس</label>
                                            <select class="form-control" name="gender">
                                                <option value="">اختر</option>
                                                <option value="male" {{ old('gender', $teacher->gender) == 'male' ? 'selected' : '' }}>ذكر</option>
                                                <option value="female" {{ old('gender', $teacher->gender) == 'female' ? 'selected' : '' }}>أنثى</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label class="form-label">العنوان</label>
                                            <textarea class="form-control" name="address" rows="2">{{ old('address', $teacher->address) }}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">تاريخ التعيين</label>
                                            <input type="date" class="form-control" name="hire_date" value="{{ old('hire_date', $teacher->hire_date?->format('Y-m-d')) }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">المؤهل العلمي</label>
                                            <input type="text" class="form-control" name="qualification" value="{{ old('qualification', $teacher->qualification) }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">التخصص</label>
                                            <input type="text" class="form-control" name="specialization" value="{{ old('specialization', $teacher->specialization) }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">سنوات الخبرة</label>
                                            <input type="text" class="form-control" name="experience_years" value="{{ old('experience_years', $teacher->experience_years) }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">الراتب</label>
                                            <input type="number" step="0.01" class="form-control" name="salary" value="{{ old('salary', $teacher->salary) }}" min="0">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">الحالة <span class="text-danger">*</span></label>
                                            <select class="form-control" name="status" required>
                                                <option value="active" {{ old('status', $teacher->status) == 'active' ? 'selected' : '' }}>نشط</option>
                                                <option value="inactive" {{ old('status', $teacher->status) == 'inactive' ? 'selected' : '' }}>غير نشط</option>
                                                <option value="on_leave" {{ old('status', $teacher->status) == 'on_leave' ? 'selected' : '' }}>في إجازة</option>
                                                <option value="resigned" {{ old('status', $teacher->status) == 'resigned' ? 'selected' : '' }}>استقال</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label class="form-label">المواد التي يدرسها</label>
                                            <select class="form-control" name="subjects[]" multiple>
                                                @foreach($subjects as $subject)
                                                    <option value="{{ $subject->id }}" {{ in_array($subject->id, old('subjects', $teacher->subjects->pluck('id')->toArray())) ? 'selected' : '' }}>{{ $subject->name }}</option>
                                                @endforeach
                                            </select>
                                            <small class="text-muted">اضغط Ctrl للاختيار المتعدد</small>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label class="form-label">ملاحظات</label>
                                            <textarea class="form-control" name="notes" rows="3">{{ old('notes', $teacher->notes) }}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label class="form-label">الصورة</label>
                                            @if($teacher->photo)
                                                <div class="mb-2">
                                                    <img src="{{ asset('storage/' . $teacher->photo) }}" alt="{{ $teacher->user->name }}" style="max-width: 100px; max-height: 100px;">
                                                </div>
                                            @endif
                                            <input type="file" class="form-control" name="photo" accept="image/*">
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary">تحديث</button>
                                    <a href="{{ route('admin.teachers.index') }}" class="btn btn-secondary">إلغاء</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

