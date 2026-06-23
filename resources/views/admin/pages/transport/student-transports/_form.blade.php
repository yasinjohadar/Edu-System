@php
    $transport = $transport ?? null;
    $statuses = ['active' => 'نشط', 'inactive' => 'غير نشط', 'suspended' => 'موقوف'];
    $selectedRoute = old('route_id', $transport->route_id ?? '');
    $selectedStop = old('stop_id', $transport->stop_id ?? '');
@endphp
<div class="row g-3">
    <div class="col-md-6">
        <div class="admin-form-field">
            <label class="admin-form-label">الطالب <span class="required">*</span></label>
            <select name="student_id" class="form-select" data-admin-choices required>
                <option value="">— اختر الطالب —</option>
                @foreach ($students as $student)
                    <option value="{{ $student->id }}" {{ old('student_id', $transport->student_id ?? '') == $student->id ? 'selected' : '' }}>
                        {{ $student->user->name ?? $student->student_code }} ({{ $student->student_code }})
                    </option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-md-6">
        <div class="admin-form-field">
            <label class="admin-form-label">المسار <span class="required">*</span></label>
            <select name="route_id" id="route_id" class="form-select" data-admin-choices required>
                <option value="">— اختر المسار —</option>
                @foreach ($routes as $route)
                    <option value="{{ $route->id }}" {{ (string) $selectedRoute === (string) $route->id ? 'selected' : '' }}>{{ $route->route_name }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-md-6">
        <div class="admin-form-field">
            <label class="admin-form-label">المحطة</label>
            <select name="stop_id" id="stop_id" class="form-select" data-admin-choices>
                <option value="">— اختر المحطة —</option>
                @foreach ($stops as $stop)
                    <option value="{{ $stop->id }}" data-route-id="{{ $stop->route_id }}"
                        {{ (string) $selectedStop === (string) $stop->id ? 'selected' : '' }}
                        @if($selectedRoute && (string) $stop->route_id !== (string) $selectedRoute) hidden @endif>
                        {{ $stop->stop_name }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-md-3">
        <div class="admin-form-field">
            <label class="admin-form-label">السائق</label>
            <select name="driver_id" class="form-select" data-admin-choices>
                <option value="">— بدون —</option>
                @foreach ($drivers as $driver)
                    <option value="{{ $driver->id }}" {{ old('driver_id', $transport->driver_id ?? '') == $driver->id ? 'selected' : '' }}>
                        {{ $driver->user->name ?? $driver->driver_code }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-md-3">
        <div class="admin-form-field">
            <label class="admin-form-label">المشرف</label>
            <select name="supervisor_id" class="form-select" data-admin-choices>
                <option value="">— بدون —</option>
                @foreach ($supervisors as $supervisor)
                    <option value="{{ $supervisor->id }}" {{ old('supervisor_id', $transport->supervisor_id ?? '') == $supervisor->id ? 'selected' : '' }}>
                        {{ $supervisor->user->name ?? $supervisor->supervisor_code }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-md-4">
        <div class="admin-form-field">
            <label class="admin-form-label">تاريخ البدء <span class="required">*</span></label>
            <input type="date" name="start_date" class="form-control" required
                   value="{{ old('start_date', isset($transport) && $transport->start_date ? $transport->start_date->format('Y-m-d') : '') }}">
        </div>
    </div>
    <div class="col-md-4">
        <div class="admin-form-field">
            <label class="admin-form-label">تاريخ الانتهاء</label>
            <input type="date" name="end_date" class="form-control"
                   value="{{ old('end_date', isset($transport) && $transport->end_date ? $transport->end_date->format('Y-m-d') : '') }}">
        </div>
    </div>
    <div class="col-md-4">
        <div class="admin-form-field">
            <label class="admin-form-label">الحالة</label>
            <select name="status" class="form-select" data-admin-choices>
                @foreach ($statuses as $key => $label)
                    <option value="{{ $key }}" {{ old('status', $transport->status ?? 'active') === $key ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>
