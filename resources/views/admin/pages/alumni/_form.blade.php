@php $alumnus = $alumni ?? null; @endphp
<div class="admin-form-section"><div class="row g-3">
    <div class="col-md-6">
        <div class="admin-form-field">
            <label class="admin-form-label">ربط بطالب (اختياري)</label>
            <select name="student_id" id="student_id" class="form-select" data-admin-choices>
                <option value="">— بدون —</option>
                @foreach ($students as $student)
                    <option value="{{ $student->id }}" data-name="{{ $student->user->name ?? '' }}" data-email="{{ $student->user->email ?? '' }}"
                        {{ old('student_id', $alumnus->student_id ?? '') == $student->id ? 'selected' : '' }}>
                        {{ $student->user->name ?? $student->student_code }} ({{ $student->student_code }})
                    </option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-md-6">
        <div class="admin-form-field">
            <label class="admin-form-label">اسم الخريج <span class="required">*</span></label>
            <input type="text" name="name" id="alumni_name" class="form-control" required value="{{ old('name', $alumnus->name ?? '') }}">
        </div>
    </div>
    <div class="col-md-6">
        <div class="admin-form-field">
            <label class="admin-form-label">البريد الإلكتروني <span class="required">*</span></label>
            <input type="email" name="email" id="alumni_email" class="form-control" required value="{{ old('email', $alumnus->email ?? '') }}">
        </div>
    </div>
    <div class="col-md-6">
        <div class="admin-form-field">
            <label class="admin-form-label">الهاتف</label>
            <input type="text" name="phone" class="form-control" value="{{ old('phone', $alumnus->phone ?? '') }}">
        </div>
    </div>
    <div class="col-md-4">
        <div class="admin-form-field">
            <label class="admin-form-label">تاريخ التخرج <span class="required">*</span></label>
            <input type="date" name="graduation_date" class="form-control" required value="{{ old('graduation_date', isset($alumnus) && $alumnus->graduation_date ? $alumnus->graduation_date->format('Y-m-d') : '') }}">
        </div>
    </div>
    <div class="col-md-4">
        <div class="admin-form-field">
            <label class="admin-form-label">الدرجة</label>
            <input type="text" name="degree" class="form-control" value="{{ old('degree', $alumnus->degree ?? '') }}">
        </div>
    </div>
    <div class="col-md-4">
        <div class="admin-form-field">
            <label class="admin-form-label">التخصص</label>
            <input type="text" name="major" class="form-control" value="{{ old('major', $alumnus->major ?? '') }}">
        </div>
    </div>
    <div class="col-md-6">
        <div class="admin-form-field">
            <label class="admin-form-label">الوظيفة الحالية</label>
            <input type="text" name="current_job" class="form-control" value="{{ old('current_job', $alumnus->current_job ?? '') }}">
        </div>
    </div>
    <div class="col-md-6">
        <div class="admin-form-field">
            <label class="admin-form-label">الشركة</label>
            <input type="text" name="company" class="form-control" value="{{ old('company', $alumnus->company ?? '') }}">
        </div>
    </div>
    <div class="col-md-8">
        <div class="admin-form-field">
            <label class="admin-form-label">العنوان</label>
            <textarea name="address" class="form-control" rows="2">{{ old('address', $alumnus->address ?? '') }}</textarea>
        </div>
    </div>
    <div class="col-md-4">
        <div class="admin-form-field">
            <label class="admin-form-label">الحالة</label>
            <select name="is_active" class="form-select" data-admin-choices>
                <option value="1" {{ old('is_active', $alumnus->is_active ?? true) == '1' ? 'selected' : '' }}>نشط</option>
                <option value="0" {{ old('is_active', $alumnus->is_active ?? true) == '0' ? 'selected' : '' }}>غير نشط</option>
            </select>
        </div>
    </div>
</div></div>
