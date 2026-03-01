@extends('admin.layouts.master')

@section('page-title')
    تعديل الدفعة
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
                    <h5 class="page-title fs-21 mb-1">تعديل الدفعة</h5>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">معلومات الدفعة</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.payments.update', $payment->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">الطالب</label>
                                        <input type="text" class="form-control" value="{{ $payment->student->user->name ?? 'غير محدد' }} ({{ $payment->student->student_code }})" disabled>
                                        <small class="text-muted">لا يمكن تغيير الطالب</small>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">الفاتورة</label>
                                        @if($payment->invoice)
                                            <input type="text" class="form-control" value="{{ $payment->invoice->invoice_number }} - المتبقي: {{ number_format($payment->invoice->remaining_amount, 2) }} ر.س" disabled>
                                        @else
                                            <input type="text" class="form-control" value="لا توجد فاتورة مرتبطة" disabled>
                                        @endif
                                        <small class="text-muted">لا يمكن تغيير الفاتورة</small>
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">تاريخ الدفع <span class="text-danger">*</span></label>
                                        <input type="date" name="payment_date" class="form-control" value="{{ old('payment_date', $payment->payment_date->format('Y-m-d')) }}" required>
                                        @error('payment_date')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">المبلغ <span class="text-danger">*</span></label>
                                        <input type="number" name="amount" id="amount" class="form-control" step="0.01" min="0.01" value="{{ old('amount', $payment->amount) }}" required>
                                        @if($payment->invoice)
                                            <small class="text-muted">المبلغ المتبقي في الفاتورة: {{ number_format($payment->invoice->remaining_amount + $payment->amount, 2) }} ر.س</small>
                                        @endif
                                        @error('amount')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">طريقة الدفع <span class="text-danger">*</span></label>
                                        <select name="payment_method" class="form-select" required>
                                            <option value="cash" {{ old('payment_method', $payment->payment_method) == 'cash' ? 'selected' : '' }}>نقدي</option>
                                            <option value="bank_transfer" {{ old('payment_method', $payment->payment_method) == 'bank_transfer' ? 'selected' : '' }}>تحويل بنكي</option>
                                            <option value="card" {{ old('payment_method', $payment->payment_method) == 'card' ? 'selected' : '' }}>بطاقة</option>
                                            <option value="check" {{ old('payment_method', $payment->payment_method) == 'check' ? 'selected' : '' }}>شيك</option>
                                            <option value="online" {{ old('payment_method', $payment->payment_method) == 'online' ? 'selected' : '' }}>دفع إلكتروني</option>
                                            <option value="other" {{ old('payment_method', $payment->payment_method) == 'other' ? 'selected' : '' }}>أخرى</option>
                                        </select>
                                        @error('payment_method')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3" id="reference_div">
                                        <label class="form-label">رقم المرجع</label>
                                        <input type="text" name="reference_number" class="form-control" value="{{ old('reference_number', $payment->reference_number) }}" placeholder="رقم الشيك، رقم التحويل، إلخ">
                                        @error('reference_number')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3" id="bank_div">
                                        <label class="form-label">اسم البنك</label>
                                        <input type="text" name="bank_name" class="form-control" value="{{ old('bank_name', $payment->bank_name) }}">
                                        @error('bank_name')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">الحالة <span class="text-danger">*</span></label>
                                        <select name="status" class="form-select" required>
                                            <option value="pending" {{ old('status', $payment->status) == 'pending' ? 'selected' : '' }}>معلق</option>
                                            <option value="completed" {{ old('status', $payment->status) == 'completed' ? 'selected' : '' }}>مكتمل</option>
                                            <option value="failed" {{ old('status', $payment->status) == 'failed' ? 'selected' : '' }}>فاشل</option>
                                            <option value="refunded" {{ old('status', $payment->status) == 'refunded' ? 'selected' : '' }}>مسترد</option>
                                        </select>
                                        @error('status')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">ملاحظات</label>
                                        <textarea name="notes" class="form-control" rows="3">{{ old('notes', $payment->notes) }}</textarea>
                                        @error('notes')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary">تحديث الدفعة</button>
                                    <a href="{{ route('admin.payments.show', $payment->id) }}" class="btn btn-secondary">إلغاء</a>
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

