@php $stop = $busStop ?? null; @endphp
<div class="admin-form-section">
    <div class="row g-3">
        <div class="col-md-6">
            <div class="admin-form-field">
                <label class="admin-form-label">المسار <span class="required">*</span></label>
                <select name="route_id" class="form-select" data-admin-choices required>
                    <option value="">— اختر المسار —</option>
                    @foreach ($routes as $r)
                        <option value="{{ $r->id }}" {{ old('route_id', $stop->route_id ?? '') == $r->id ? 'selected' : '' }}>{{ $r->route_name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-6">
            <div class="admin-form-field">
                <label class="admin-form-label">اسم المحطة <span class="required">*</span></label>
                <input type="text" name="stop_name" class="form-control" required value="{{ old('stop_name', $stop->stop_name ?? '') }}">
            </div>
        </div>
        <div class="col-md-6">
            <div class="admin-form-field">
                <label class="admin-form-label">العنوان</label>
                <input type="text" name="address" class="form-control" value="{{ old('address', $stop->address ?? '') }}">
            </div>
        </div>
        <div class="col-md-3">
            <div class="admin-form-field">
                <label class="admin-form-label">الترتيب</label>
                <input type="number" name="order" class="form-control" min="0" value="{{ old('order', $stop->order ?? 0) }}">
            </div>
        </div>
        <div class="col-md-3">
            <div class="admin-form-field">
                <label class="admin-form-label">وقت الوصول</label>
                <input type="time" name="arrival_time" class="form-control"
                       value="{{ old('arrival_time', isset($stop) && $stop->arrival_time ? \Carbon\Carbon::parse($stop->arrival_time)->format('H:i') : '') }}">
            </div>
        </div>
    </div>
</div>
