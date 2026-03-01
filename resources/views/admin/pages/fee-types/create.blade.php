@extends('admin.layouts.master')

@section('page-title')
    إضافة نوع رسوم جديد
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
                    <h5 class="page-title fs-21 mb-1">إضافة نوع رسوم جديد</h5>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">معلومات نوع الرسوم</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.fee-types.store') }}" method="POST">
                                @csrf
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">الاسم <span class="text-danger">*</span></label>
                                        <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                                        @error('name')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">الاسم بالإنجليزية</label>
                                        <input type="text" name="name_en" class="form-control" value="{{ old('name_en') }}">
                                        @error('name_en')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">الرمز <span class="text-danger">*</span></label>
                                        <input type="text" name="code" class="form-control" value="{{ old('code') }}" required>
                                        <small class="text-muted">رمز فريد لنوع الرسوم (مثال: TUITION-001)</small>
                                        @error('code')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">الفئة <span class="text-danger">*</span></label>
                                        <select name="category" class="form-select" required>
                                            <option value="tuition" {{ old('category') == 'tuition' ? 'selected' : '' }}>رسوم دراسية</option>
                                            <option value="registration" {{ old('category') == 'registration' ? 'selected' : '' }}>رسوم تسجيل</option>
                                            <option value="activity" {{ old('category') == 'activity' ? 'selected' : '' }}>رسوم نشاطات</option>
                                            <option value="book" {{ old('category') == 'book' ? 'selected' : '' }}>رسوم كتب</option>
                                            <option value="uniform" {{ old('category') == 'uniform' ? 'selected' : '' }}>رسوم زي موحد</option>
                                            <option value="transport" {{ old('category') == 'transport' ? 'selected' : '' }}>رسوم مواصلات</option>
                                            <option value="other" {{ old('category') == 'other' ? 'selected' : '' }}>أخرى</option>
                                        </select>
                                        @error('category')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">المبلغ الافتراضي <span class="text-danger">*</span></label>
                                        <input type="number" name="default_amount" class="form-control" step="0.01" min="0" value="{{ old('default_amount', 0) }}" required>
                                        @error('default_amount')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">ترتيب العرض</label>
                                        <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', 0) }}" min="0">
                                        @error('sort_order')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="is_recurring" id="is_recurring" value="1" {{ old('is_recurring') ? 'checked' : '' }}>
                                            <label class="form-check-label" for="is_recurring">
                                                رسوم متكررة
                                            </label>
                                        </div>
                                    </div>

                                    <div class="col-md-6 mb-3" id="recurring_period_div" style="display: none;">
                                        <label class="form-label">فترة التكرار</label>
                                        <select name="recurring_period" class="form-select">
                                            <option value="monthly" {{ old('recurring_period') == 'monthly' ? 'selected' : '' }}>شهري</option>
                                            <option value="quarterly" {{ old('recurring_period') == 'quarterly' ? 'selected' : '' }}>ربع سنوي</option>
                                            <option value="semester" {{ old('recurring_period') == 'semester' ? 'selected' : '' }}>فصلي</option>
                                            <option value="yearly" {{ old('recurring_period') == 'yearly' ? 'selected' : '' }}>سنوي</option>
                                        </select>
                                    </div>

                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">الوصف</label>
                                        <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
                                        @error('description')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">الحالة</label>
                                        <select name="is_active" class="form-select">
                                            <option value="1" {{ old('is_active', '1') == '1' ? 'selected' : '' }}>نشط</option>
                                            <option value="0" {{ old('is_active') == '0' ? 'selected' : '' }}>غير نشط</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-left: 5px; vertical-align: middle;">
                                            <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                                            <polyline points="17 21 17 13 7 13 7 21"></polyline>
                                            <polyline points="7 3 7 8 15 8"></polyline>
                                        </svg>
                                        حفظ
                                    </button>
                                    <a href="{{ route('admin.fee-types.index') }}" class="btn btn-secondary">إلغاء</a>
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
document.getElementById('is_recurring').addEventListener('change', function() {
    document.getElementById('recurring_period_div').style.display = this.checked ? 'block' : 'none';
});
</script>
@endpush

