@php $template = $template ?? null; @endphp
<div class="admin-form-section">
    <div class="row g-3">
        <div class="col-md-6">
            <div class="admin-form-field">
                <label class="admin-form-label">اسم القالب <span class="required">*</span></label>
                <input type="text" name="name" class="form-control" required value="{{ old('name', $template->name ?? '') }}">
            </div>
        </div>
        <div class="col-md-3">
            <div class="admin-form-field">
                <label class="admin-form-label">نوع الشهادة <span class="required">*</span></label>
                <select name="type" class="form-select" data-admin-choices required>
                    @foreach ($types as $key => $label)
                        <option value="{{ $key }}" {{ old('type', $template->type ?? '') === $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-3">
            <div class="admin-form-field">
                <label class="admin-form-label">الحالة</label>
                <select name="is_active" class="form-select" data-admin-choices>
                    <option value="1" {{ old('is_active', $template->is_active ?? true) == '1' ? 'selected' : '' }}>نشط</option>
                    <option value="0" {{ old('is_active', $template->is_active ?? true) == '0' ? 'selected' : '' }}>غير نشط</option>
                </select>
            </div>
        </div>
        <div class="col-12">
            <div class="admin-form-field">
                <label class="admin-form-label">قالب HTML <span class="required">*</span></label>
                <textarea name="html_template" class="form-control font-monospace" rows="8" required placeholder="استخدم @{{student_name}} و @{{issue_date}} كمتغيرات">{{ old('html_template', $template->html_template ?? '<div class="certificate"><h1>شهادة @{{type}}</h1><p>يُمنح الطالب/ة: @{{student_name}}</p><p>بتاريخ: @{{issue_date}}</p></div>') }}</textarea>
            </div>
        </div>
        <div class="col-md-6">
            <div class="admin-form-field">
                <label class="admin-form-label">الحقول (JSON اختياري)</label>
                <textarea name="fields" class="form-control font-monospace" rows="3" placeholder='["student_name","issue_date"]'>{{ old('fields', isset($template) && $template->fields ? json_encode($template->fields, JSON_UNESCAPED_UNICODE) : '') }}</textarea>
            </div>
        </div>
        <div class="col-md-6">
            <div class="admin-form-field">
                <label class="admin-form-label">صورة الخلفية</label>
                <input type="file" name="background_image" class="form-control" accept="image/*">
                @if(isset($template) && $template->background_image)
                    <small class="text-muted d-block mt-1">الصورة الحالية: {{ basename($template->background_image) }}</small>
                @endif
            </div>
        </div>
    </div>
</div>
