@php
    $certificate = $certificate ?? null;
    $notes = old('data', isset($certificate) && is_array($certificate->data) ? ($certificate->data['notes'] ?? '') : '');
@endphp
<div class="admin-form-section">
    <div class="row g-3">
        <div class="col-md-6">
            <div class="admin-form-field">
                <label class="admin-form-label">القالب <span class="required">*</span></label>
                <select name="template_id" class="form-select" data-admin-choices required>
                    <option value="">— اختر القالب —</option>
                    @foreach ($templates as $template)
                        <option value="{{ $template->id }}" {{ old('template_id', $certificate->template_id ?? '') == $template->id ? 'selected' : '' }}>{{ $template->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-6">
            <div class="admin-form-field">
                <label class="admin-form-label">الطالب</label>
                <select name="student_id" class="form-select" data-admin-choices>
                    <option value="">— بدون —</option>
                    @foreach ($students as $student)
                        <option value="{{ $student->id }}" {{ old('student_id', $certificate->student_id ?? '') == $student->id ? 'selected' : '' }}>
                            {{ $student->user->name ?? $student->student_code }} ({{ $student->student_code }})
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-4">
            <div class="admin-form-field">
                <label class="admin-form-label">نوع الشهادة <span class="required">*</span></label>
                <select name="type" class="form-select" data-admin-choices required>
                    @foreach ($types as $key => $label)
                        <option value="{{ $key }}" {{ old('type', $certificate->type ?? '') === $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-4">
            <div class="admin-form-field">
                <label class="admin-form-label">تاريخ الإصدار <span class="required">*</span></label>
                <input type="date" name="issue_date" class="form-control" required
                       value="{{ old('issue_date', isset($certificate) && $certificate->issue_date ? $certificate->issue_date->format('Y-m-d') : now()->format('Y-m-d')) }}">
            </div>
        </div>
        <div class="col-md-4">
            <div class="admin-form-field">
                <label class="admin-form-label">حالة التحقق</label>
                <select name="is_verified" class="form-select" data-admin-choices>
                    <option value="1" {{ old('is_verified', $certificate->is_verified ?? false) == '1' ? 'selected' : '' }}>موثّقة</option>
                    <option value="0" {{ old('is_verified', $certificate->is_verified ?? false) == '0' ? 'selected' : '' }}>غير موثّقة</option>
                </select>
            </div>
        </div>
        <div class="col-12">
            <div class="admin-form-field">
                <label class="admin-form-label">ملاحظات إضافية</label>
                <textarea name="data" class="form-control" rows="3" placeholder="بيانات أو ملاحظات تُحفظ مع الشهادة">{{ $notes }}</textarea>
            </div>
        </div>
        @if(isset($certificate))
        <div class="col-md-6">
            <div class="admin-form-field">
                <label class="admin-form-label">رقم الشهادة</label>
                <input type="text" class="form-control" value="{{ $certificate->certificate_number }}" disabled>
            </div>
        </div>
        <div class="col-md-6">
            <div class="admin-form-field">
                <label class="admin-form-label">رمز التحقق</label>
                <input type="text" class="form-control" value="{{ $certificate->verification_code }}" disabled>
            </div>
        </div>
        @endif
    </div>
</div>
