@extends('admin.layouts.master')

@section('page-title')
    تسجيل دفعة جديدة
@stop

@section('content')
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Start::app-content -->
    <div class="main-content app-content">
        <div class="container-fluid">
            <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
                <div class="my-auto">
                    <h5 class="page-title fs-21 mb-1">تسجيل دفعة جديدة</h5>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">معلومات الدفعة</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.payments.store') }}" method="POST">
                                @csrf
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">الطالب <span class="text-danger">*</span></label>
                                        <select name="student_id" id="student_id" class="form-select" required>
                                            <option value="">-- اختر الطالب --</option>
                                            @foreach($students as $student)
                                                <option value="{{ $student->id }}" {{ old('student_id') == $student->id ? 'selected' : '' }}>
                                                    {{ $student->user->name }} ({{ $student->student_code }})
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('student_id')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">الفاتورة (اختياري)</label>
                                        <select name="invoice_id" id="invoice_id" class="form-select">
                                            <option value="">-- اختر الفاتورة --</option>
                                            @if($selectedInvoice)
                                                <option value="{{ $selectedInvoice->id }}" selected>
                                                    {{ $selectedInvoice->invoice_number }} - المتبقي: {{ number_format($selectedInvoice->remaining_amount, 2) }} ر.س
                                                </option>
                                            @endif
                                        </select>
                                        <small class="text-muted">سيتم تحديث قائمة الفواتير بعد اختيار الطالب</small>
                                        @error('invoice_id')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">تاريخ الدفع <span class="text-danger">*</span></label>
                                        <input type="date" name="payment_date" class="form-control" value="{{ old('payment_date', date('Y-m-d')) }}" required>
                                        @error('payment_date')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">المبلغ <span class="text-danger">*</span></label>
                                        <input type="number" name="amount" id="amount" class="form-control" step="0.01" min="0.01" value="{{ old('amount') }}" required>
                                        <small class="text-muted" id="remaining_amount_hint"></small>
                                        @error('amount')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">طريقة الدفع <span class="text-danger">*</span></label>
                                        <select name="payment_method" class="form-select" required>
                                            <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>نقدي</option>
                                            <option value="bank_transfer" {{ old('payment_method') == 'bank_transfer' ? 'selected' : '' }}>تحويل بنكي</option>
                                            <option value="card" {{ old('payment_method') == 'card' ? 'selected' : '' }}>بطاقة</option>
                                            <option value="check" {{ old('payment_method') == 'check' ? 'selected' : '' }}>شيك</option>
                                            <option value="online" {{ old('payment_method') == 'online' ? 'selected' : '' }}>دفع إلكتروني</option>
                                            <option value="other" {{ old('payment_method') == 'other' ? 'selected' : '' }}>أخرى</option>
                                        </select>
                                        @error('payment_method')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3" id="reference_div">
                                        <label class="form-label">رقم المرجع</label>
                                        <input type="text" name="reference_number" class="form-control" value="{{ old('reference_number') }}" placeholder="رقم الشيك، رقم التحويل، إلخ">
                                        @error('reference_number')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3" id="bank_div">
                                        <label class="form-label">اسم البنك</label>
                                        <input type="text" name="bank_name" class="form-control" value="{{ old('bank_name') }}">
                                        @error('bank_name')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">ملاحظات</label>
                                        <textarea name="notes" class="form-control" rows="3">{{ old('notes') }}</textarea>
                                        @error('notes')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-left: 5px; vertical-align: middle;">
                                            <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                                            <polyline points="17 21 17 13 7 13 7 21"></polyline>
                                            <polyline points="7 3 7 8 15 8"></polyline>
                                        </svg>
                                        حفظ الدفعة
                                    </button>
                                    <a href="{{ route('admin.payments.index') }}" class="btn btn-secondary">إلغاء</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End::app-content -->
@stop

@push('scripts')
<script>
document.getElementById('student_id').addEventListener('change', function() {
    const studentId = this.value;
    const invoiceSelect = document.getElementById('invoice_id');
    
    if (studentId) {
        fetch(`{{ route('admin.invoices.json') }}?student_id=${studentId}`)
            .then(response => response.json())
            .then(data => {
                invoiceSelect.innerHTML = '<option value="">-- اختر الفاتورة --</option>';
                if (data.invoices) {
                    data.invoices.forEach(invoice => {
                        const option = document.createElement('option');
                        option.value = invoice.id;
                        option.textContent = `${invoice.invoice_number} - المتبقي: ${invoice.remaining_amount} ر.س`;
                        option.setAttribute('data-remaining', invoice.remaining_amount.replace(',', ''));
                        invoiceSelect.appendChild(option);
                    });
                }
            })
            .catch(error => console.error('Error:', error));
    } else {
        invoiceSelect.innerHTML = '<option value="">-- اختر الفاتورة --</option>';
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
        referenceDiv.style.display = 'block';
        bankDiv.style.display = 'block';
    } else {
        referenceDiv.style.display = 'block';
        bankDiv.style.display = 'none';
    }
});

// تشغيل عند التحميل
document.querySelector('select[name="payment_method"]').dispatchEvent(new Event('change'));
</script>
@endpush

