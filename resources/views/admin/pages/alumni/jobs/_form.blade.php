@php $job = $job ?? null; @endphp
<div class="admin-form-section"><div class="row g-3">
    <div class="col-md-8">
        <div class="admin-form-field">
            <label class="admin-form-label">عنوان الوظيفة <span class="required">*</span></label>
            <input type="text" name="title" class="form-control" required value="{{ old('title', $job->title ?? '') }}">
        </div>
    </div>
    <div class="col-md-4">
        <div class="admin-form-field">
            <label class="admin-form-label">نوع التوظيف <span class="required">*</span></label>
            <select name="employment_type" class="form-select" data-admin-choices required>
                @foreach ($employmentTypes as $key => $label)
                    <option value="{{ $key }}" {{ old('employment_type', $job->employment_type ?? 'full_time') === $key ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-md-6">
        <div class="admin-form-field">
            <label class="admin-form-label">الشركة <span class="required">*</span></label>
            <input type="text" name="company" class="form-control" required value="{{ old('company', $job->company ?? '') }}">
        </div>
    </div>
    <div class="col-md-6">
        <div class="admin-form-field">
            <label class="admin-form-label">المكان</label>
            <input type="text" name="location" class="form-control" value="{{ old('location', $job->location ?? '') }}">
        </div>
    </div>
    <div class="col-md-4">
        <div class="admin-form-field">
            <label class="admin-form-label">نطاق الراتب</label>
            <input type="text" name="salary_range" class="form-control" placeholder="مثال: 8000-12000" value="{{ old('salary_range', $job->salary_range ?? '') }}">
        </div>
    </div>
    <div class="col-md-4">
        <div class="admin-form-field">
            <label class="admin-form-label">آخر موعد للتقديم</label>
            <input type="date" name="application_deadline" class="form-control" value="{{ old('application_deadline', isset($job) && $job->application_deadline ? $job->application_deadline->format('Y-m-d') : '') }}">
        </div>
    </div>
    <div class="col-md-4">
        <div class="admin-form-field">
            <label class="admin-form-label">الحالة</label>
            <select name="is_active" class="form-select" data-admin-choices>
                <option value="1" {{ old('is_active', $job->is_active ?? true) == '1' ? 'selected' : '' }}>نشط</option>
                <option value="0" {{ old('is_active', $job->is_active ?? true) == '0' ? 'selected' : '' }}>غير نشط</option>
            </select>
        </div>
    </div>
    <div class="col-md-6">
        <div class="admin-form-field">
            <label class="admin-form-label">بريد التواصل</label>
            <input type="email" name="contact_email" class="form-control" value="{{ old('contact_email', $job->contact_email ?? '') }}">
        </div>
    </div>
    <div class="col-md-6">
        <div class="admin-form-field">
            <label class="admin-form-label">هاتف التواصل</label>
            <input type="text" name="contact_phone" class="form-control" value="{{ old('contact_phone', $job->contact_phone ?? '') }}">
        </div>
    </div>
    <div class="col-12">
        <div class="admin-form-field">
            <label class="admin-form-label">وصف الوظيفة <span class="required">*</span></label>
            <textarea name="description" class="form-control" rows="5" required>{{ old('description', $job->description ?? '') }}</textarea>
        </div>
    </div>
</div></div>
