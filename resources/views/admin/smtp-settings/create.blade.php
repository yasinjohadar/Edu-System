@extends('admin.layouts.master')

@section('page-title')
    إضافة إعدادات SMTP جديدة
@stop

@section('content')
    <!-- Start::app-content -->
    <div class="main-content app-content">
        <div class="container-fluid">
            <!-- Page Header -->
            <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
                <div>
                    <h4 class="mb-0">إضافة إعدادات SMTP جديدة</h4>
                    <p class="mb-0 text-muted">تكوين خادم بريد إلكتروني جديد</p>
                </div>
                <div class="main-dashboard-header-right">
                    <a href="{{ route('admin.smtp-settings.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> عودة
                    </a>
                </div>
            </div>
            <!-- End Page Header -->

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            @if($errors->any())
                                <div class="alert alert-danger">
                                    <i class="fas fa-exclamation-circle"></i>
                                    <h6 class="alert-heading">يرجى تصحيح الأخطاء التالية:</h6>
                                    <ul class="mb-0">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form id="smtpForm" action="{{ route('admin.smtp-settings.store') }}" method="POST" class="form-horizontal">
                                @csrf
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">الاسم <span class="text-danger">*</span></label>
                                            <input type="text" 
                                                   name="name" 
                                                   id="name" 
                                                   class="form-control" 
                                                   placeholder="مثال: Gmail SMTP"
                                                   value="{{ old('name') }}"
                                                   required>
                                            <small class="form-text text-muted">اسم تعريفي لإعدادات SMTP</small>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">المضيف (Host) <span class="text-danger">*</span></label>
                                            <input type="text" 
                                                   name="host" 
                                                   id="host" 
                                                   class="form-control" 
                                                   placeholder="مثال: smtp.gmail.com"
                                                   value="{{ old('host') }}"
                                                   required>
                                            <small class="form-text text-muted">عنوان خادم SMTP</small>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">المنفذ (Port) <span class="text-danger">*</span></label>
                                            <input type="number" 
                                                   name="port" 
                                                   id="port" 
                                                   class="form-control" 
                                                   placeholder="مثال: 587"
                                                   value="{{ old('port', 587) }}"
                                                   required>
                                            <small class="form-text text-muted">منفذ SMTP (عادة 587 لـ TLS أو 465 لـ SSL)</small>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">اسم المستخدم <span class="text-danger">*</span></label>
                                            <input type="text" 
                                                   name="username" 
                                                   id="username" 
                                                   class="form-control" 
                                                   placeholder="مثال: your-email@gmail.com"
                                                   value="{{ old('username') }}"
                                                   required>
                                            <small class="form-text text-muted">اسم مستخدم SMTP (عادة البريد الإلكتروني)</small>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">كلمة المرور <span class="text-danger">*</span></label>
                                            <input type="password" 
                                                   name="password" 
                                                   id="password" 
                                                   class="form-control" 
                                                   placeholder="كلمة مرور SMTP"
                                                   required>
                                            <small class="form-text text-muted">كلمة مرور حساب SMTP</small>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">التشفير <span class="text-danger">*</span></label>
                                            <select name="encryption" id="encryption" class="form-control" required>
                                                <option value="tls" {{ old('encryption') == 'tls' ? 'selected' : '' }}>TLS</option>
                                                <option value="ssl" {{ old('encryption') == 'ssl' ? 'selected' : '' }}>SSL</option>
                                                <option value="none" {{ old('encryption') == 'none' ? 'selected' : '' }}>بدون تشفير</option>
                                            </select>
                                            <small class="form-text text-muted">نوع تشفير الاتصال</small>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">عنوان المرسل <span class="text-danger">*</span></label>
                                            <input type="email" 
                                                   name="from_address" 
                                                   id="from_address" 
                                                   class="form-control" 
                                                   placeholder="مثال: noreply@school.com"
                                                   value="{{ old('from_address') }}"
                                                   required>
                                            <small class="form-text text-muted">البريد الإلكتروني الذي سيتم إرسال الرسائل منه</small>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">اسم المرسل <span class="text-danger">*</span></label>
                                            <input type="text" 
                                                   name="from_name" 
                                                   id="from_name" 
                                                   class="form-control" 
                                                   placeholder="مثال: نظام إدارة التعليم"
                                                   value="{{ old('from_name') }}"
                                                   required>
                                            <small class="form-text text-muted">الاسم الذي سيظهر في رسائل البريد الإلكتروني</small>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <div class="checkbox checkbox-primary">
                                                <input type="checkbox" name="is_default" id="is_default" value="1">
                                                <label for="is_default">تعيين كإعداد افتراضي</label>
                                            </div>
                                            <small class="form-text text-muted">سيتم استخدام هذه الإعدادات افتراضياً لإرسال الرسائل</small>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <div class="checkbox checkbox-primary">
                                                <input type="checkbox" name="is_active" id="is_active" value="1" checked>
                                                <label for="is_active">نشط</label>
                                            </div>
                                            <small class="form-text text-muted">تفعيل استخدام هذه الإعدادات</small>
                                        </div>
                                    </div>
                                </div>

                                <hr class="my-4">

                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> حفظ الإعدادات
                                    </button>
                                    <button type="button" 
                                            class="btn btn-info" 
                                            onclick="testConnection()">
                                        <i class="fas fa-plug"></i> اختبار الاتصال
                                    </button>
                                    <a href="{{ route('admin.smtp-settings.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-times"></i> إلغاء
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End::app-content -->

<div id="testResult" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">نتيجة اختبار الاتصال</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="testResultContent"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function testConnection() {
    // Get form values
    const data = {
        host: document.getElementById('host').value,
        port: document.getElementById('port').value,
        username: document.getElementById('username').value,
        password: document.getElementById('password').value,
        encryption: document.getElementById('encryption').value,
        from_address: document.getElementById('from_address').value,
        from_name: document.getElementById('from_name').value,
    };

    // Validate required fields
    if (!data.host || !data.port || !data.username || !data.password || !data.from_address || !data.from_name) {
        alert('يرجى ملء جميع الحقول المطلوبة قبل اختبار الاتصال');
        return;
    }

    const testResultContent = document.getElementById('testResultContent');
    testResultContent.innerHTML = '<div class="text-center"><div class="spinner-border" role="status"><span class="sr-only">جاري الاختبار...</span></div><p class="mt-2">جاري اختبار الاتصال...</p></div>';
    
    const modal = new bootstrap.Modal(document.getElementById('testResult'));
    modal.show();

    fetch('{{ route('admin.smtp-settings.test-connection') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            testResultContent.innerHTML = `
                <div class="alert alert-success">
                    <h5><i class="fas fa-check-circle"></i> نجح الاتصال!</h5>
                    <p>${data.message}</p>
                </div>
            `;
        } else {
            testResultContent.innerHTML = `
                <div class="alert alert-danger">
                    <h5><i class="fas fa-times-circle"></i> فشل الاتصال</h5>
                    <p>${data.message}</p>
                </div>
            `;
        }
    })
    .catch(error => {
        testResultContent.innerHTML = `
            <div class="alert alert-danger">
                <h5><i class="fas fa-exclamation-triangle"></i> خطأ</h5>
                <p>حدث خطأ أثناء اختبار الاتصال: ${error.message}</p>
            </div>
        `;
    });
}
</script>
@endpush
