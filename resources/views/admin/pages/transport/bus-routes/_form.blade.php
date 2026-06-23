@php
    $route = $busRoute ?? null;
@endphp

<div class="admin-form-section">
    <div class="row g-3">
        <div class="col-md-6">
            <div class="admin-form-field">
                <label class="admin-form-label">اسم المسار <span class="required">*</span></label>
                <input type="text" name="route_name" class="form-control" required
                       value="{{ old('route_name', $route->route_name ?? '') }}">
            </div>
        </div>
        <div class="col-md-6">
            <div class="admin-form-field">
                <label class="admin-form-label">رقم المسار <span class="required">*</span></label>
                <input type="text" name="route_number" class="form-control" required
                       value="{{ old('route_number', $route->route_number ?? '') }}">
            </div>
        </div>
        <div class="col-md-4">
            <div class="admin-form-field">
                <label class="admin-form-label">وقت البدء <span class="required">*</span></label>
                <input type="time" name="start_time" class="form-control" required
                       value="{{ old('start_time', isset($route) ? \Carbon\Carbon::parse($route->start_time)->format('H:i') : '07:00') }}">
            </div>
        </div>
        <div class="col-md-4">
            <div class="admin-form-field">
                <label class="admin-form-label">وقت الانتهاء <span class="required">*</span></label>
                <input type="time" name="end_time" class="form-control" required
                       value="{{ old('end_time', isset($route) ? \Carbon\Carbon::parse($route->end_time)->format('H:i') : '08:30') }}">
            </div>
        </div>
        <div class="col-md-4">
            <div class="admin-form-field">
                <label class="admin-form-label">الرسوم (ر.س)</label>
                <input type="number" name="fee" class="form-control" step="0.01" min="0"
                       value="{{ old('fee', $route->fee ?? 0) }}">
            </div>
        </div>
        <div class="col-md-4">
            <div class="admin-form-field">
                <label class="admin-form-label">المسافة (كم)</label>
                <input type="number" name="distance" class="form-control" step="0.01" min="0"
                       value="{{ old('distance', $route->distance ?? '') }}">
            </div>
        </div>
        <div class="col-md-4">
            <div class="admin-form-field">
                <label class="admin-form-label">الحالة</label>
                <select name="is_active" class="form-select" data-admin-choices>
                    <option value="1" {{ old('is_active', $route->is_active ?? true) == '1' ? 'selected' : '' }}>نشط</option>
                    <option value="0" {{ old('is_active', $route->is_active ?? true) == '0' ? 'selected' : '' }}>غير نشط</option>
                </select>
            </div>
        </div>
        <div class="col-12">
            <div class="admin-form-field">
                <label class="admin-form-label">الوصف</label>
                <textarea name="description" class="form-control" rows="3">{{ old('description', $route->description ?? '') }}</textarea>
            </div>
        </div>
    </div>
</div>
