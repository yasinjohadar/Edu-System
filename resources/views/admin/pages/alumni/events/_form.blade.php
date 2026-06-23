@php $event = $event ?? null; @endphp
<div class="admin-form-section"><div class="row g-3">
    <div class="col-md-8">
        <div class="admin-form-field">
            <label class="admin-form-label">عنوان الحدث <span class="required">*</span></label>
            <input type="text" name="title" class="form-control" required value="{{ old('title', $event->title ?? '') }}">
        </div>
    </div>
    <div class="col-md-4">
        <div class="admin-form-field">
            <label class="admin-form-label">نوع الحدث <span class="required">*</span></label>
            <select name="type" class="form-select" data-admin-choices required>
                @foreach ($types as $key => $label)
                    <option value="{{ $key }}" {{ old('type', $event->type ?? 'other') === $key ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-md-4">
        <div class="admin-form-field">
            <label class="admin-form-label">تاريخ الحدث <span class="required">*</span></label>
            <input type="date" name="event_date" class="form-control" required value="{{ old('event_date', isset($event) && $event->event_date ? $event->event_date->format('Y-m-d') : '') }}">
        </div>
    </div>
    <div class="col-md-4">
        <div class="admin-form-field">
            <label class="admin-form-label">وقت الحدث</label>
            <input type="time" name="event_time" class="form-control" value="{{ old('event_time', isset($event) && $event->event_time ? \Carbon\Carbon::parse($event->event_time)->format('H:i') : '') }}">
        </div>
    </div>
    <div class="col-md-4">
        <div class="admin-form-field">
            <label class="admin-form-label">الرسوم (ر.س)</label>
            <input type="number" name="fee" class="form-control" step="0.01" min="0" value="{{ old('fee', $event->fee ?? 0) }}">
        </div>
    </div>
    <div class="col-md-6">
        <div class="admin-form-field">
            <label class="admin-form-label">المكان</label>
            <input type="text" name="location" class="form-control" value="{{ old('location', $event->location ?? '') }}">
        </div>
    </div>
    <div class="col-md-3">
        <div class="admin-form-field">
            <label class="admin-form-label">الحد الأقصى للحضور</label>
            <input type="number" name="max_attendees" class="form-control" min="1" value="{{ old('max_attendees', $event->max_attendees ?? '') }}">
        </div>
    </div>
    <div class="col-md-3">
        <div class="admin-form-field">
            <label class="admin-form-label">الحالة</label>
            <select name="is_active" class="form-select" data-admin-choices>
                <option value="1" {{ old('is_active', $event->is_active ?? true) == '1' ? 'selected' : '' }}>نشط</option>
                <option value="0" {{ old('is_active', $event->is_active ?? true) == '0' ? 'selected' : '' }}>غير نشط</option>
            </select>
        </div>
    </div>
    <div class="col-12">
        <div class="admin-form-field">
            <label class="admin-form-label">الوصف</label>
            <textarea name="description" class="form-control" rows="4">{{ old('description', $event->description ?? '') }}</textarea>
        </div>
    </div>
</div></div>
