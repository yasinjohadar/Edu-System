@extends('admin.layouts.master')

@section('page-title')
    تعديل الفاتورة
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
                    <h5 class="page-title fs-21 mb-1">تعديل الفاتورة</h5>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">معلومات الفاتورة</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.invoices.update', $invoice->id) }}" method="POST" id="invoiceForm">
                                @csrf
                                @method('PUT')
                                
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <label class="form-label">الطالب</label>
                                        <input type="text" class="form-control" value="{{ $invoice->student->user->name ?? 'غير محدد' }} ({{ $invoice->student->student_code }})" disabled>
                                        <small class="text-muted">لا يمكن تغيير الطالب</small>
                                    </div>

                                    <div class="col-md-3">
                                        <label class="form-label">تاريخ الفاتورة <span class="text-danger">*</span></label>
                                        <input type="date" name="invoice_date" class="form-control" value="{{ old('invoice_date', $invoice->invoice_date->format('Y-m-d')) }}" required>
                                        @error('invoice_date')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-3">
                                        <label class="form-label">تاريخ الاستحقاق <span class="text-danger">*</span></label>
                                        <input type="date" name="due_date" class="form-control" value="{{ old('due_date', $invoice->due_date->format('Y-m-d')) }}" required>
                                        @error('due_date')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="card mb-4">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <h6 class="mb-0">عناصر الفاتورة</h6>
                                        <button type="button" class="btn btn-sm btn-primary" id="addItemBtn">
                                            <i class="fa-solid fa-plus"></i> إضافة عنصر
                                        </button>
                                    </div>
                                    <div class="card-body">
                                        <div id="itemsContainer">
                                            @foreach($invoice->items as $index => $item)
                                            <div class="item-row mb-3 p-3 border rounded">
                                                <div class="row">
                                                    <div class="col-md-4 mb-2">
                                                        <label class="form-label">نوع الرسوم</label>
                                                        <select name="items[{{ $index }}][fee_type_id]" class="form-select fee-type-select">
                                                            <option value="">-- اختر نوع الرسوم --</option>
                                                            @foreach($feeTypes as $feeType)
                                                                <option value="{{ $feeType->id }}" data-amount="{{ $feeType->default_amount }}" {{ $item->fee_type_id == $feeType->id ? 'selected' : '' }}>
                                                                    {{ $feeType->name }} ({{ number_format($feeType->default_amount, 2) }} ر.س)
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-md-4 mb-2">
                                                        <label class="form-label">اسم البند <span class="text-danger">*</span></label>
                                                        <input type="text" name="items[{{ $index }}][item_name]" class="form-control" value="{{ old('items.'.$index.'.item_name', $item->item_name) }}" required>
                                                    </div>
                                                    <div class="col-md-4 mb-2">
                                                        <label class="form-label">الكمية <span class="text-danger">*</span></label>
                                                        <input type="number" name="items[{{ $index }}][quantity]" class="form-control quantity" value="{{ old('items.'.$index.'.quantity', $item->quantity) }}" min="1" required>
                                                    </div>
                                                    <div class="col-md-3 mb-2">
                                                        <label class="form-label">سعر الوحدة <span class="text-danger">*</span></label>
                                                        <input type="number" name="items[{{ $index }}][unit_price]" class="form-control unit-price" step="0.01" min="0" value="{{ old('items.'.$index.'.unit_price', $item->unit_price) }}" required>
                                                    </div>
                                                    <div class="col-md-3 mb-2">
                                                        <label class="form-label">الخصم</label>
                                                        <input type="number" name="items[{{ $index }}][discount]" class="form-control discount" step="0.01" min="0" value="{{ old('items.'.$index.'.discount', $item->discount) }}">
                                                    </div>
                                                    <div class="col-md-3 mb-2">
                                                        <label class="form-label">الضريبة</label>
                                                        <input type="number" name="items[{{ $index }}][tax]" class="form-control tax" step="0.01" min="0" value="{{ old('items.'.$index.'.tax', $item->tax) }}">
                                                    </div>
                                                    <div class="col-md-3 mb-2">
                                                        <label class="form-label">الإجمالي</label>
                                                        <input type="text" class="form-control item-total" readonly value="{{ number_format($item->total, 2) }}">
                                                    </div>
                                                    <div class="col-md-12 mb-2">
                                                        <label class="form-label">الوصف</label>
                                                        <textarea name="items[{{ $index }}][description]" class="form-control" rows="2">{{ old('items.'.$index.'.description', $item->description) }}</textarea>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <button type="button" class="btn btn-sm btn-danger remove-item" {{ $invoice->items->count() == 1 ? 'style="display:none;"' : '' }}>
                                                            <i class="fa-solid fa-trash"></i> حذف
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <label class="form-label">ملاحظات</label>
                                        <textarea name="notes" class="form-control" rows="3">{{ old('notes', $invoice->notes) }}</textarea>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">شروط الدفع</label>
                                        <textarea name="terms" class="form-control" rows="3">{{ old('terms', $invoice->terms) }}</textarea>
                                    </div>
                                </div>

                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <label class="form-label">الخصم الإجمالي</label>
                                        <input type="number" name="discount_amount" id="discount_amount" class="form-control" step="0.01" min="0" value="{{ old('discount_amount', $invoice->discount_amount) }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">الضريبة الإجمالية</label>
                                        <input type="number" name="tax_amount" id="tax_amount" class="form-control" step="0.01" min="0" value="{{ old('tax_amount', $invoice->tax_amount) }}">
                                    </div>
                                </div>

                                <div class="card bg-light">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <h6>المجموع الفرعي: <span id="subtotal">{{ number_format($invoice->subtotal, 2) }}</span> ر.س</h6>
                                            </div>
                                            <div class="col-md-6 text-end">
                                                <h4>المبلغ الإجمالي: <span id="total_amount" class="text-primary">{{ number_format($invoice->total_amount, 2) }}</span> ر.س</h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex gap-2 mt-4">
                                    <button type="submit" class="btn btn-primary">تحديث الفاتورة</button>
                                    <a href="{{ route('admin.invoices.show', $invoice->id) }}" class="btn btn-secondary">إلغاء</a>
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
let itemIndex = {{ $invoice->items->count() }};

document.getElementById('addItemBtn').addEventListener('click', function() {
    const container = document.getElementById('itemsContainer');
    const newItem = container.querySelector('.item-row').cloneNode(true);
    
    // تحديث الأسماء والفهارس
    newItem.querySelectorAll('input, select, textarea').forEach(function(input) {
        const name = input.getAttribute('name');
        if (name) {
            input.setAttribute('name', name.replace(/\[\d+\]/, '[' + itemIndex + ']'));
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
    if (e.target.classList.contains('remove-item') || e.target.closest('.remove-item')) {
        const btn = e.target.classList.contains('remove-item') ? e.target : e.target.closest('.remove-item');
        if (document.querySelectorAll('.item-row').length > 1) {
            btn.closest('.item-row').remove();
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

// إرفاق الأحداث للعناصر الموجودة
document.querySelectorAll('.item-row').forEach(function(itemRow) {
    attachItemEvents(itemRow);
});

document.getElementById('discount_amount').addEventListener('input', calculateTotals);
document.getElementById('tax_amount').addEventListener('input', calculateTotals);
</script>
@endpush

