@extends('admin.layouts.master')

@section('page-title')
    تسجيل دفعة جديدة
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
                    <h1>تسجيل دفعة جديدة</h1>
                    <p>تسجيل دفعة مالية لطالب مع ربطها بفاتورة اختيارياً</p>
                </div>
                <a href="{{ route('admin.payments.index') }}" class="admin-btn admin-btn-secondary">
                    <i class="ri-arrow-right-line"></i>
                    العودة للقائمة
                </a>
            </div>

            <div class="admin-page-card">
                <form action="{{ route('admin.payments.store') }}" method="POST" class="admin-form" id="payment-create-form">
                    @csrf

                    <div class="admin-form-body">
                        <div class="admin-form-section">
                            <div class="admin-form-section-head">
                                <div class="admin-section-icon admin-section-icon-blue">
                                    <i class="ri-wallet-3-line"></i>
                                </div>
                                <div>
                                    <h3>بيانات الدفعة</h3>
                                    <p>الطالب والفاتورة والمبلغ</p>
                                </div>
                            </div>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="admin-form-field">
                                        <label class="admin-form-label">الطالب <span class="required">*</span></label>
                                        <select name="student_id" id="student_id" class="form-select" data-admin-choices required>
                                            <option value="">— اختر الطالب —</option>
                                            @foreach ($students as $student)
                                                <option value="{{ $student->id }}" {{ old('student_id') == $student->id ? 'selected' : '' }}>
                                                    {{ $student->user->name }} ({{ $student->student_code }})
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('student_id')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="admin-form-field">
                                        <label class="admin-form-label">الفاتورة (اختياري)</label>
                                        <select name="invoice_id" id="invoice_id" class="form-select" data-admin-choices>
                                            <option value="">— اختر الفاتورة —</option>
                                            @if ($selectedInvoice)
                                                <option value="{{ $selectedInvoice->id }}" selected>
                                                    {{ $selectedInvoice->invoice_number }} — المتبقي: {{ number_format($selectedInvoice->remaining_amount, 2) }} ر.س
                                                </option>
                                            @endif
                                        </select>
                                        <small class="admin-form-hint">تُحدَّث قائمة الفواتير بعد اختيار الطالب</small>
                                        @error('invoice_id')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="admin-form-field">
                                        <label class="admin-form-label">تاريخ الدفع <span class="required">*</span></label>
                                        <input type="date" name="payment_date" class="form-control" value="{{ old('payment_date', date('Y-m-d')) }}" required>
                                        @error('payment_date')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="admin-form-field">
                                        <label class="admin-form-label">المبلغ <span class="required">*</span></label>
                                        <input type="number" name="amount" id="amount" class="form-control" step="0.01" min="0.01" value="{{ old('amount') }}" required>
                                        <small class="admin-form-hint" id="remaining_amount_hint"></small>
                                        @error('amount')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="admin-form-field">
                                        <label class="admin-form-label">طريقة الدفع <span class="required">*</span></label>
                                        <select name="payment_method" class="form-select" data-admin-choices required>
                                            <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>نقدي</option>
                                            <option value="bank_transfer" {{ old('payment_method') == 'bank_transfer' ? 'selected' : '' }}>تحويل بنكي</option>
                                            <option value="card" {{ old('payment_method') == 'card' ? 'selected' : '' }}>بطاقة</option>
                                            <option value="check" {{ old('payment_method') == 'check' ? 'selected' : '' }}>شيك</option>
                                            <option value="online" {{ old('payment_method') == 'online' ? 'selected' : '' }}>دفع إلكتروني</option>
                                            <option value="other" {{ old('payment_method') == 'other' ? 'selected' : '' }}>أخرى</option>
                                        </select>
                                        @error('payment_method')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="col-md-6" id="reference_div">
                                    <div class="admin-form-field">
                                        <label class="admin-form-label">رقم المرجع</label>
                                        <input type="text" name="reference_number" class="form-control" value="{{ old('reference_number') }}" placeholder="رقم الشيك أو التحويل">
                                        @error('reference_number')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="col-md-6" id="bank_div">
                                    <div class="admin-form-field">
                                        <label class="admin-form-label">اسم البنك</label>
                                        <input type="text" name="bank_name" class="form-control" value="{{ old('bank_name') }}">
                                        @error('bank_name')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="admin-form-field">
                                        <label class="admin-form-label">ملاحظات</label>
                                        <textarea name="notes" class="form-control" rows="3">{{ old('notes') }}</textarea>
                                        @error('notes')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="admin-form-footer">
                        <button type="submit" class="admin-btn admin-btn-primary">
                            <i class="ri-save-line"></i>
                            حفظ الدفعة
                        </button>
                        <a href="{{ route('admin.payments.index') }}" class="admin-btn admin-btn-secondary">إلغاء</a>
                    </div>
                </form>
            </div>

        </div>
    </div>
@stop

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    AdminTables.initAdminForm('#payment-create-form');

    document.getElementById('student_id').addEventListener('change', function() {
        const studentId = this.value;
        const invoiceSelect = document.getElementById('invoice_id');

        if (studentId) {
            fetch(`{{ route('admin.invoices.json') }}?student_id=${studentId}`)
                .then(response => response.json())
                .then(data => {
                    invoiceSelect.innerHTML = '<option value="">— اختر الفاتورة —</option>';
                    if (data.invoices) {
                        data.invoices.forEach(invoice => {
                            const option = document.createElement('option');
                            option.value = invoice.id;
                            option.textContent = `${invoice.invoice_number} — المتبقي: ${invoice.remaining_amount} ر.س`;
                            option.setAttribute('data-remaining', invoice.remaining_amount.replace(',', ''));
                            invoiceSelect.appendChild(option);
                        });
                    }
                    if (typeof Choices !== 'undefined' && invoiceSelect._choices) {
                        invoiceSelect._choices.destroy();
                        AdminTables.initFilterSelects(document.getElementById('payment-create-form'));
                    }
                })
                .catch(error => console.error('Error:', error));
        } else {
            invoiceSelect.innerHTML = '<option value="">— اختر الفاتورة —</option>';
        }
    });

    document.getElementById('invoice_id').addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const remaining = selectedOption.getAttribute('data-remaining');
        const amountInput = document.getElementById('amount');
        const hint = document.getElementById('remaining_amount_hint');

        if (remaining) {
            hint.textContent = `المبلغ المتبقي في الفاتورة: ${remaining} ر.س`;
            amountInput.setAttribute('max', remaining);
        } else {
            hint.textContent = '';
            amountInput.removeAttribute('max');
        }
    });

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
