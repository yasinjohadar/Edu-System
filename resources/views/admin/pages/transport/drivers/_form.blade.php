@php
    $driver = $driver ?? null;
    $statuses = ['active' => 'نشط', 'inactive' => 'غير نشط', 'on_leave' => 'في إجازة'];
@endphp
<div class="row g-3">
    <div class="col-md-6">
        <div class="admin-form-field">
            <label class="admin-form-label">ربط بمستخدم (اختياري)</label>
            <select name="user_id" class="form-select" data-admin-choices>
                <option value="">— بدون —</option>
                @foreach ($users as $user)
                    <option value="{{ $user->id }}" {{ old('user_id', $driver->user_id ?? '') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-md-3">
        <div class="admin-form-field">
            <label class="admin-form-label">رقم السائق <span class="required">*</span></label>
            <input type="text" name="driver_code" class="form-control" required value="{{ old('driver_code', $driver->driver_code ?? '') }}">
        </div>
    </div>
    <div class="col-md-3">
        <div class="admin-form-field">
            <label class="admin-form-label">رقم الرخصة <span class="required">*</span></label>
            <input type="text" name="license_number" class="form-control" required value="{{ old('license_number', $driver->license_number ?? '') }}">
        </div>
    </div>
    <div class="col-md-4">
        <div class="admin-form-field">
            <label class="admin-form-label">انتهاء الرخصة</label>
            <input type="date" name="license_expiry" class="form-control" value="{{ old('license_expiry', isset($driver) && $driver->license_expiry ? $driver->license_expiry->format('Y-m-d') : '') }}">
        </div>
    </div>
    <div class="col-md-4">
        <div class="admin-form-field">
            <label class="admin-form-label">الهاتف</label>
            <input type="text" name="phone" class="form-control" value="{{ old('phone', $driver->phone ?? '') }}">
        </div>
    </div>
    <div class="col-md-4">
        <div class="admin-form-field">
            <label class="admin-form-label">الحالة</label>
            <select name="status" class="form-select" data-admin-choices>
                @foreach ($statuses as $key => $label)
                    <option value="{{ $key }}" {{ old('status', $driver->status ?? 'active') === $key ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-12">
        <div class="admin-form-field">
            <label class="admin-form-label">العنوان</label>
            <input type="text" name="address" class="form-control" value="{{ old('address', $driver->address ?? '') }}">
        </div>
    </div>
</div>
