@php
    $supervisor = $supervisor ?? null;
    $statuses = ['active' => 'نشط', 'inactive' => 'غير نشط'];
@endphp
<div class="row g-3">
    <div class="col-md-6">
        <div class="admin-form-field">
            <label class="admin-form-label">ربط بمستخدم (اختياري)</label>
            <select name="user_id" class="form-select" data-admin-choices>
                <option value="">— بدون —</option>
                @foreach ($users as $user)
                    <option value="{{ $user->id }}" {{ old('user_id', $supervisor->user_id ?? '') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-md-3">
        <div class="admin-form-field">
            <label class="admin-form-label">رقم المشرف <span class="required">*</span></label>
            <input type="text" name="supervisor_code" class="form-control" required value="{{ old('supervisor_code', $supervisor->supervisor_code ?? '') }}">
        </div>
    </div>
    <div class="col-md-3">
        <div class="admin-form-field">
            <label class="admin-form-label">الهاتف</label>
            <input type="text" name="phone" class="form-control" value="{{ old('phone', $supervisor->phone ?? '') }}">
        </div>
    </div>
    <div class="col-md-4">
        <div class="admin-form-field">
            <label class="admin-form-label">الحالة</label>
            <select name="status" class="form-select" data-admin-choices>
                @foreach ($statuses as $key => $label)
                    <option value="{{ $key }}" {{ old('status', $supervisor->status ?? 'active') === $key ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>
