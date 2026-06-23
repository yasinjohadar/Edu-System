@extends('admin.layouts.master')

@section('page-title')
    تعديل الدفعة
@stop

@section('content')
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li class="small">{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="main-content app-content">
        <div class="container-fluid">

            <div class="admin-page-header">
                <div class="page-title-wrap">
                    <h1>تعديل الدفعة</h1>
                    <p>{{ $payment->payment_number }}</p>
                </div>
                <a href="{{ route('admin.payments.show', $payment->id) }}" class="admin-btn admin-btn-secondary">
                    <i class="ri-arrow-right-line"></i>
                    العودة
                </a>
            </div>

            <div class="admin-page-card">
                <form action="{{ route('admin.payments.update', $payment->id) }}" method="POST" class="admin-form" id="payment-edit-form">
                    @csrf
                    @method('PUT')

                    <div class="admin-form-body">
                        <div class="admin-form-section">
                            <div class="admin-form-section-head">
                                <div class="admin-section-icon admin-section-icon-blue">
                                    <i class="ri-wallet-3-line"></i>
                                </div>
                                <div>
                                    <h3>بيانات الدفعة</h3>
                                    <p>تعديل تفاصيل الدفعة المالية</p>
                                </div>
                            </div>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="admin-form-field">
                                        <label class="admin-form-label">الطالب</label>
                                        <input type="text" class="form-control" value="{{ $payment->student->user->name ?? 'غير محدد' }} ({{ $payment->student->student_code }})" disabled>
                                        <small class="admin-form-hint">لا يمكن تغيير الطالب</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="admin-form-field">
                                        <label class="admin-form-label">الفاتورة</label>
                                        @if ($payment->invoice)
                                            <input type="text" class="form-control" value="{{ $payment->invoice->invoice_number }} — المتبقي: {{ number_format($payment->invoice->remaining_amount, 2) }} ر.س" disabled>
                                        @else
                                            <input type="text" class="form-control" value="لا توجد فاتورة مرتبطة" disabled>
                                        @endif
                                        <small class="admin-form-hint">لا يمكن تغيير الفاتورة</small>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="admin-form-field">
                                        <label class="admin-form-label">تاريخ الدفع <span class="required">*</span></label>
                                        <input type="date" name="payment_date" class="form-control" value="{{ old('payment_date', $payment->payment_date->format('Y-m-d')) }}" required>
                                        @error('payment_date')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="admin-form-field">
                                        <label class="admin-form-label">المبلغ <span class="required">*</span></label>
                                        <input type="number" name="amount" class="form-control" step="0.01" min="0.01" value="{{ old('amount', $payment->amount) }}" required>
                                        @if ($payment->invoice)
                                            <small class="admin-form-hint">المتاح في الفاتورة: {{ number_format($payment->invoice->remaining_amount + $payment->amount, 2) }} ر.س</small>
                                        @endif
                                        @error('amount')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="admin-form-field">
                                        <label class="admin-form-label">طريقة الدفع <span class="required">*</span></label>
                                        <select name="payment_method" class="form-select" data-admin-choices required>
                                            <option value="cash" {{ old('payment_method', $payment->payment_method) == 'cash' ? 'selected' : '' }}>نقدي</option>
                                            <option value="bank_transfer" {{ old('payment_method', $payment->payment_method) == 'bank_transfer' ? 'selected' : '' }}>تحويل بنكي</option>
                                            <option value="card" {{ old('payment_method', $payment->payment_method) == 'card' ? 'selected' : '' }}>بطاقة</option>
                                            <option value="check" {{ old('payment_method', $payment->payment_method) == 'check' ? 'selected' : '' }}>شيك</option>
                                            <option value="online" {{ old('payment_method', $payment->payment_method) == 'online' ? 'selected' : '' }}>دفع إلكتروني</option>
                                            <option value="other" {{ old('payment_method', $payment->payment_method) == 'other' ? 'selected' : '' }}>أخرى</option>
                                        </select>
                                        @error('payment_method')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="col-md-6" id="reference_div">
                                    <div class="admin-form-field">
                                        <label class="admin-form-label">رقم المرجع</label>
                                        <input type="text" name="reference_number" class="form-control" value="{{ old('reference_number', $payment->reference_number) }}">
                                        @error('reference_number')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="col-md-6" id="bank_div">
                                    <div class="admin-form-field">
                                        <label class="admin-form-label">اسم البنك</label>
                                        <input type="text" name="bank_name" class="form-control" value="{{ old('bank_name', $payment->bank_name) }}">
                                        @error('bank_name')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="admin-form-field">
                                        <label class="admin-form-label">الحالة <span class="required">*</span></label>
                                        <select name="status" class="form-select" data-admin-choices required>
                                            <option value="pending" {{ old('status', $payment->status) == 'pending' ? 'selected' : '' }}>معلق</option>
                                            <option value="completed" {{ old('status', $payment->status) == 'completed' ? 'selected' : '' }}>مكتمل</option>
                                            <option value="failed" {{ old('status', $payment->status) == 'failed' ? 'selected' : '' }}>فاشل</option>
                                            <option value="refunded" {{ old('status', $payment->status) == 'refunded' ? 'selected' : '' }}>مسترد</option>
                                        </select>
                                        @error('status')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="admin-form-field">
                                        <label class="admin-form-label">ملاحظات</label>
                                        <textarea name="notes" class="form-control" rows="3">{{ old('notes', $payment->notes) }}</textarea>
                                        @error('notes')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="admin-form-footer">
                        <button type="submit" class="admin-btn admin-btn-primary">
                            <i class="ri-save-line"></i>
                            حفظ التعديلات
                        </button>
                        <a href="{{ route('admin.payments.show', $payment->id) }}" class="admin-btn admin-btn-secondary">إلغاء</a>
                    </div>
                </form>
            </div>

        </div>
    </div>
@stop

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    AdminTables.initAdminForm('#payment-edit-form');

    document.querySelector('select[name="payment_method"]').addEventListener('change', function() {
        const method = this.value;
        const referenceDiv = document.getElementById('reference_div');
        const bankDiv = document.getElementById('bank_div');

        if (method === 'cash') {
            referenceDiv.style.display = 'none';
            bankDiv.style.display = 'none';
        } else if (method === 'bank_transfer' || method === 'check') {
            referenceDiv.style.display = '';
            bankDiv.style.display = '';
        } else {
            referenceDiv.style.display = '';
            bankDiv.style.display = 'none';
        }
    });

    document.querySelector('select[name="payment_method"]').dispatchEvent(new Event('change'));
});
</script>
@endpush
