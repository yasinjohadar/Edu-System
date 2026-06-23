@extends('admin.layouts.master')

@section('page-title')
    إنشاء فاتورة جديدة
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
                    <h1>إنشاء فاتورة جديدة</h1>
                    <p>إصدار فاتورة مالية لطالب مع تحديد البنود والمبالغ</p>
                </div>
                <a href="{{ route('admin.invoices.index') }}" class="admin-btn admin-btn-secondary">
                    <i class="ri-arrow-right-line"></i>
                    العودة للقائمة
                </a>
            </div>

            <div class="admin-page-card">
                <form action="{{ route('admin.invoices.store') }}" method="POST" id="invoiceForm" class="admin-form">
                    @csrf

                    <div class="admin-form-body">
                        <div class="admin-form-section">
                            <div class="admin-form-section-head">
                                <div class="admin-section-icon admin-section-icon-blue">
                                    <i class="ri-file-list-3-line"></i>
                                </div>
                                <div>
                                    <h3>معلومات الفاتورة</h3>
                                    <p>الطالب والتواريخ</p>
                                </div>
                            </div>

                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <div class="admin-form-field">
                                        <label class="admin-form-label">الطالب <span class="required">*</span></label>
                                        <select name="student_id" id="student_id" class="form-select" data-admin-choices required>
                                            <option value="">-- اختر الطالب --</option>
                                            @foreach($students as $student)
                                                <option value="{{ $student->id }}" {{ old('student_id') == $student->id ? 'selected' : '' }}>
                                                    {{ $student->user->name }} ({{ $student->student_code }})
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('student_id')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="admin-form-field">
                                        <label class="admin-form-label">تاريخ الفاتورة <span class="required">*</span></label>
                                        <input type="date" name="invoice_date" class="form-control" value="{{ old('invoice_date', date('Y-m-d')) }}" required>
                                        @error('invoice_date')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="admin-form-field">
                                        <label class="admin-form-label">تاريخ الاستحقاق <span class="required">*</span></label>
                                        <input type="date" name="due_date" class="form-control" value="{{ old('due_date', date('Y-m-d', strtotime('+30 days'))) }}" required>
                                        @error('due_date')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="admin-form-section">
                            <div class="admin-form-section-head">
                                <div class="admin-section-icon admin-section-icon-green">
                                    <i class="ri-list-check-2"></i>
                                </div>
                                <div class="d-flex justify-content-between align-items-center flex-grow-1">
                                    <div>
                                        <h3>عناصر الفاتورة</h3>
                                        <p>البنود والمبالغ</p>
                                    </div>
                                    <button type="button" class="admin-btn admin-btn-primary admin-btn-sm" id="addItemBtn">
                                        <i class="ri-add-line"></i> إضافة عنصر
                                    </button>
                                </div>
                            </div>

                            <div id="itemsContainer">
                                            <div class="item-row mb-3 p-3 border rounded">
                                                <div class="row">
                                                    <div class="col-md-4 mb-2">
                                                        <label class="form-label">نوع الرسوم</label>
                                                        <select name="items[0][fee_type_id]" class="form-select fee-type-select">
                                                            <option value="">-- اختر نوع الرسوم --</option>
                                                            @foreach($feeTypes as $feeType)
                                                                <option value="{{ $feeType->id }}" data-amount="{{ $feeType->default_amount }}">
                                                                    {{ $feeType->name }} ({{ number_format($feeType->default_amount, 2) }} ر.س)
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-md-4 mb-2">
                                                        <label class="form-label">اسم البند <span class="text-danger">*</span></label>
                                                        <input type="text" name="items[0][item_name]" class="form-control" required>
                                                    </div>
                                                    <div class="col-md-4 mb-2">
                                                        <label class="form-label">الكمية <span class="text-danger">*</span></label>
                                                        <input type="number" name="items[0][quantity]" class="form-control quantity" value="1" min="1" required>
                                                    </div>
                                                    <div class="col-md-3 mb-2">
                                                        <label class="form-label">سعر الوحدة <span class="text-danger">*</span></label>
                                                        <input type="number" name="items[0][unit_price]" class="form-control unit-price" step="0.01" min="0" required>
                                                    </div>
                                                    <div class="col-md-3 mb-2">
                                                        <label class="form-label">الخصم</label>
                                                        <input type="number" name="items[0][discount]" class="form-control discount" step="0.01" min="0" value="0">
                                                    </div>
                                                    <div class="col-md-3 mb-2">
                                                        <label class="form-label">الضريبة</label>
                                                        <input type="number" name="items[0][tax]" class="form-control tax" step="0.01" min="0" value="0">
                                                    </div>
                                                    <div class="col-md-3 mb-2">
                                                        <label class="form-label">الإجمالي</label>
                                                        <input type="text" class="form-control item-total" readonly value="0.00">
                                                    </div>
                                                    <div class="col-md-12 mb-2">
                                                        <label class="form-label">الوصف</label>
                                                        <textarea name="items[0][description]" class="form-control" rows="2"></textarea>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <button type="button" class="btn btn-sm btn-danger remove-item" style="display: none;">
                                                            <i class="fa-solid fa-trash"></i> حذف
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                            </div>
                        </div>

                        <div class="admin-form-section">
                            <div class="admin-form-section-head">
                                <div class="admin-section-icon admin-section-icon-purple">
                                    <i class="ri-sticky-note-line"></i>
                                </div>
                                <div>
                                    <h3>ملاحظات وإجماليات</h3>
                                    <p>الخصومات والضرائب والشروط</p>
                                </div>
                            </div>

                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <div class="admin-form-field">
                                        <label class="admin-form-label">ملاحظات</label>
                                        <textarea name="notes" class="form-control" rows="3">{{ old('notes') }}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="admin-form-field">
                                        <label class="admin-form-label">شروط الدفع</label>
                                        <textarea name="terms" class="form-control" rows="3">{{ old('terms') }}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="admin-form-field">
                                        <label class="admin-form-label">الخصم الإجمالي</label>
                                        <input type="number" name="discount_amount" id="discount_amount" class="form-control" step="0.01" min="0" value="0">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="admin-form-field">
                                        <label class="admin-form-label">الضريبة الإجمالية</label>
                                        <input type="number" name="tax_amount" id="tax_amount" class="form-control" step="0.01" min="0" value="0">
                                    </div>
                                </div>
                            </div>

                            <div class="admin-detail-card">
                                <div class="admin-detail-card-body">
                                <div class="row align-items-center">
                                    <div class="col-md-6">
                                        <span>المجموع الفرعي: <strong id="subtotal">0.00</strong> ر.س</span>
                                    </div>
                                    <div class="col-md-6 text-end">
                                        <h4 class="mb-0">المبلغ الإجمالي: <span id="total_amount" class="text-primary">0.00</span> ر.س</h4>
                                    </div>
                                </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="admin-form-footer">
                        <button type="submit" class="admin-btn admin-btn-primary">
                            <i class="ri-save-line"></i>
                            حفظ الفاتورة
                        </button>
                        <a href="{{ route('admin.invoices.index') }}" class="admin-btn admin-btn-secondary">إلغاء</a>
                    </div>
                </form>
            </div>

        </div>
    </div>
@stop

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    AdminTables.initAdminForm('#invoiceForm');
let itemIndex = 1;

document.getElementById('addItemBtn').addEventListener('click', function() {
    const container = document.getElementById('itemsContainer');
    const newItem = container.querySelector('.item-row').cloneNode(true);
    
    // تحديث الأسماء والفهارس
    newItem.querySelectorAll('input, select, textarea').forEach(function(input) {
        const name = input.getAttribute('name');
        if (name) {
            input.setAttribute('name', name.replace('[0]', '[' + itemIndex + ']'));
        }
    });
    
    // مسح القيم
    newItem.querySelectorAll('input[type="text"], input[type="number"], textarea').forEach(function(input) {
        if (!input.classList.contains('item-total')) {
            input.value = input.type === 'number' ? (input.classList.contains('quantity') ? '1' : '0') : '';
        } else {
            input.value = '0.00';
        }
    });
    
    newItem.querySelector('.remove-item').style.display = 'block';
    container.appendChild(newItem);
    itemIndex++;
    
    attachItemEvents(newItem);
});

document.addEventListener('click', function(e) {
    if (e.target.classList.contains('remove-item')) {
        if (document.querySelectorAll('.item-row').length > 1) {
            e.target.closest('.item-row').remove();
            calculateTotals();
        }
    }
});

function attachItemEvents(itemRow) {
    const inputs = itemRow.querySelectorAll('.quantity, .unit-price, .discount, .tax');
    inputs.forEach(function(input) {
        input.addEventListener('input', function() {
            calculateItemTotal(itemRow);
            calculateTotals();
        });
    });
    
    const feeTypeSelect = itemRow.querySelector('.fee-type-select');
    if (feeTypeSelect) {
        feeTypeSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const amount = selectedOption.getAttribute('data-amount');
            if (amount) {
                itemRow.querySelector('.unit-price').value = amount;
                calculateItemTotal(itemRow);
                calculateTotals();
            }
        });
    }
}

function calculateItemTotal(itemRow) {
    const quantity = parseFloat(itemRow.querySelector('.quantity').value) || 0;
    const unitPrice = parseFloat(itemRow.querySelector('.unit-price').value) || 0;
    const discount = parseFloat(itemRow.querySelector('.discount').value) || 0;
    const tax = parseFloat(itemRow.querySelector('.tax').value) || 0;
    
    const subtotal = (quantity * unitPrice) - discount;
    const total = subtotal + tax;
    
    itemRow.querySelector('.item-total').value = total.toFixed(2);
}

function calculateTotals() {
    let subtotal = 0;
    
    document.querySelectorAll('.item-row').forEach(function(itemRow) {
        const total = parseFloat(itemRow.querySelector('.item-total').value) || 0;
        subtotal += total;
    });
    
    const discountAmount = parseFloat(document.getElementById('discount_amount').value) || 0;
    const taxAmount = parseFloat(document.getElementById('tax_amount').value) || 0;
    
    const totalAmount = subtotal - discountAmount + taxAmount;
    
    document.getElementById('subtotal').textContent = subtotal.toFixed(2);
    document.getElementById('total_amount').textContent = totalAmount.toFixed(2);
}

// إرفاق الأحداث للعنصر الأول
document.querySelectorAll('.item-row').forEach(function(itemRow) {
    attachItemEvents(itemRow);
});

document.getElementById('discount_amount').addEventListener('input', calculateTotals);
document.getElementById('tax_amount').addEventListener('input', calculateTotals);

// حساب الإجماليات عند التحميل
calculateTotals();
});
</script>
@endpush

