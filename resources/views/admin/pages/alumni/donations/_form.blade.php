@php $donation = $donation ?? null; @endphp
<div class="admin-form-section"><div class="row g-3">
    <div class="col-md-6">
        <div class="admin-form-field">
            <label class="admin-form-label">الخريج <span class="required">*</span></label>
            <select name="alumni_id" class="form-select" data-admin-choices required>
                <option value="">— اختر الخريج —</option>
                @foreach ($alumniList as $alumnus)
                    <option value="{{ $alumnus->id }}" {{ old('alumni_id', $donation->alumni_id ?? '') == $alumnus->id ? 'selected' : '' }}>{{ $alumnus->name }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-md-3">
        <div class="admin-form-field">
            <label class="admin-form-label">المبلغ (ر.س) <span class="required">*</span></label>
            <input type="number" name="amount" class="form-control" step="0.01" min="0.01" required value="{{ old('amount', $donation->amount ?? '') }}">
        </div>
    </div>
    <div class="col-md-3">
        <div class="admin-form-field">
            <label class="admin-form-label">تاريخ التبرع <span class="required">*</span></label>
            <input type="date" name="donation_date" class="form-control" required value="{{ old('donation_date', isset($donation) && $donation->donation_date ? $donation->donation_date->format('Y-m-d') : now()->format('Y-m-d')) }}">
        </div>
    </div>
    <div class="col-md-4">
        <div class="admin-form-field">
            <label class="admin-form-label">طريقة الدفع <span class="required">*</span></label>
            <select name="payment_method" class="form-select" data-admin-choices required>
                @foreach ($paymentMethods as $key => $label)
                    <option value="{{ $key }}" {{ old('payment_method', $donation->payment_method ?? 'bank_transfer') === $key ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-md-4">
        <div class="admin-form-field">
            <label class="admin-form-label">الحالة <span class="required">*</span></label>
            <select name="status" class="form-select" data-admin-choices required>
                @foreach ($statuses as $key => $label)
                    <option value="{{ $key }}" {{ old('status', $donation->status ?? 'pending') === $key ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-md-4">
        <div class="admin-form-field">
            <label class="admin-form-label">رقم المرجع</label>
            <input type="text" name="reference_number" class="form-control" value="{{ old('reference_number', $donation->reference_number ?? '') }}" placeholder="يُولَّد تلقائياً إن تُرك فارغاً">
        </div>
    </div>
    <div class="col-12">
        <div class="admin-form-field">
            <label class="admin-form-label">الغرض</label>
            <input type="text" name="purpose" class="form-control" value="{{ old('purpose', $donation->purpose ?? '') }}">
        </div>
    </div>
    <div class="col-12">
        <div class="admin-form-field">
            <label class="admin-form-label">ملاحظات</label>
            <textarea name="notes" class="form-control" rows="3">{{ old('notes', $donation->notes ?? '') }}</textarea>
        </div>
    </div>
</div></div>
