@extends('student.layouts.master')

@section('page-title')
    تسليم واجب
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
                    <h5 class="page-title fs-21 mb-1">تسليم واجب: {{ $assignment->title }}</h5>
                </div>
                <div>
                    <a href="{{ route('student.assignments.show', $assignment->id) }}" class="btn btn-secondary btn-sm">رجوع</a>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">معلومات الواجب</h5>
                        </div>
                        <div class="card-body">
                            <p><strong>المادة:</strong> {{ $assignment->subject->name ?? 'غير محدد' }}</p>
                            <p><strong>تاريخ الاستحقاق:</strong> {{ $assignment->due_date->format('Y-m-d') }} في {{ $assignment->due_time }}</p>
                            <p><strong>الدرجة الكلية:</strong> {{ number_format($assignment->total_marks, 2) }}</p>
                            @if($remainingAttempts !== null)
                            <p><strong>المحاولات المتبقية:</strong> <span class="badge bg-info">{{ $remainingAttempts }}</span></p>
                            @endif
                            @if($previousSubmission)
                            <div class="alert alert-warning">
                                <strong>إعادة تسليم:</strong> هذه محاولة رقم {{ $nextAttemptNumber }}. 
                                @if($previousSubmission->requires_resubmission)
                                    <br>تم طلب إعادة التسليم بسبب: {{ $previousSubmission->resubmission_reason }}
                                @endif
                            </div>
                            @endif
                        </div>
                    </div>

                    <div class="card mt-3">
                        <div class="card-header">
                            <h5 class="card-title">تسليم الواجب</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('student.assignments.store-submission', $assignment->id) }}" method="POST" enctype="multipart/form-data" id="submitForm">
                                @csrf
                                
                                @php
                                    $submissionTypes = is_string($assignment->submission_types) ? json_decode($assignment->submission_types, true) : ($assignment->submission_types ?? []);
                                @endphp

                                @if(in_array('text', $submissionTypes))
                                <div class="mb-4">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <label class="form-label"><strong>النصوص</strong></label>
                                        <button type="button" class="btn btn-sm btn-primary" id="addTextBtn">
                                            <i class="fa-solid fa-plus"></i> إضافة نص
                                        </button>
                                    </div>
                                    <div id="textsContainer">
                                        <div class="text-item mb-3 p-3 border rounded">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <label class="form-label">النص #<span class="text-number">1</span></label>
                                                <button type="button" class="btn btn-sm btn-danger remove-text-btn" style="display: none;">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                            </div>
                                            <textarea name="texts[]" class="form-control" rows="5" placeholder="اكتب إجابتك هنا..."></textarea>
                                        </div>
                                    </div>
                                </div>
                                @endif

                                @if(in_array('file', $submissionTypes))
                                <div class="mb-4">
                                    <label class="form-label"><strong>الملفات</strong></label>
                                    <input type="file" name="files[]" class="form-control" multiple accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.zip,.rar,image/*">
                                    <small class="text-muted">يمكن رفع ملفات متعددة (حجم كل ملف: 50MB كحد أقصى)</small>
                                    <div id="filesPreview" class="mt-2"></div>
                                </div>
                                @endif

                                @if(in_array('link', $submissionTypes))
                                <div class="mb-4">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <label class="form-label"><strong>الروابط</strong></label>
                                        <button type="button" class="btn btn-sm btn-primary" id="addLinkBtn">
                                            <i class="fa-solid fa-plus"></i> إضافة رابط
                                        </button>
                                    </div>
                                    <div id="linksContainer">
                                        <div class="link-item mb-3 p-3 border rounded">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <label class="form-label">الرابط #<span class="link-number">1</span></label>
                                                <button type="button" class="btn btn-sm btn-danger remove-link-btn" style="display: none;">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6 mb-2">
                                                    <input type="url" name="links[0][url]" class="form-control" placeholder="https://...">
                                                </div>
                                                <div class="col-md-3 mb-2">
                                                    <select name="links[0][link_type]" class="form-select">
                                                        <option value="other">نوع آخر</option>
                                                        <option value="google_drive">Google Drive</option>
                                                        <option value="dropbox">Dropbox</option>
                                                        <option value="youtube">YouTube</option>
                                                        <option value="onedrive">OneDrive</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-3 mb-2">
                                                    <input type="text" name="links[0][title]" class="form-control" placeholder="عنوان الرابط">
                                                </div>
                                                <div class="col-md-12 mb-2">
                                                    <textarea name="links[0][description]" class="form-control" rows="2" placeholder="وصف الرابط"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif

                                <div class="mb-3">
                                    <label class="form-label">ملاحظات (اختياري)</label>
                                    <textarea name="student_notes" class="form-control" rows="3" placeholder="أي ملاحظات إضافية...">{{ old('student_notes') }}</textarea>
                                </div>

                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fa-solid fa-upload"></i> تسليم الواجب
                                    </button>
                                    <a href="{{ route('student.assignments.show', $assignment->id) }}" class="btn btn-secondary">إلغاء</a>
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
document.addEventListener('DOMContentLoaded', function() {
    // إدارة النصوص
    let textCount = 1;
    const addTextBtn = document.getElementById('addTextBtn');
    const textsContainer = document.getElementById('textsContainer');
    
    if (addTextBtn && textsContainer) {
        addTextBtn.addEventListener('click', function() {
            textCount++;
            const textItem = document.createElement('div');
            textItem.className = 'text-item mb-3 p-3 border rounded';
            textItem.innerHTML = `
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <label class="form-label">النص #<span class="text-number">${textCount}</span></label>
                    <button type="button" class="btn btn-sm btn-danger remove-text-btn">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                </div>
                <textarea name="texts[]" class="form-control" rows="5" placeholder="اكتب إجابتك هنا..."></textarea>
            `;
            textsContainer.appendChild(textItem);
            updateTextNumbers();
            updateRemoveButtons();
        });

        textsContainer.addEventListener('click', function(e) {
            if (e.target.closest('.remove-text-btn')) {
                e.target.closest('.text-item').remove();
                updateTextNumbers();
                updateRemoveButtons();
            }
        });
    }

    // إدارة الروابط
    let linkCount = 1;
    const addLinkBtn = document.getElementById('addLinkBtn');
    const linksContainer = document.getElementById('linksContainer');
    
    if (addLinkBtn && linksContainer) {
        addLinkBtn.addEventListener('click', function() {
            linkCount++;
            const linkItem = document.createElement('div');
            linkItem.className = 'link-item mb-3 p-3 border rounded';
            linkItem.innerHTML = `
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <label class="form-label">الرابط #<span class="link-number">${linkCount}</span></label>
                    <button type="button" class="btn btn-sm btn-danger remove-link-btn">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-2">
                        <input type="url" name="links[${linkCount - 1}][url]" class="form-control" placeholder="https://...">
                    </div>
                    <div class="col-md-3 mb-2">
                        <select name="links[${linkCount - 1}][link_type]" class="form-select">
                            <option value="other">نوع آخر</option>
                            <option value="google_drive">Google Drive</option>
                            <option value="dropbox">Dropbox</option>
                            <option value="youtube">YouTube</option>
                            <option value="onedrive">OneDrive</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-2">
                        <input type="text" name="links[${linkCount - 1}][title]" class="form-control" placeholder="عنوان الرابط">
                    </div>
                    <div class="col-md-12 mb-2">
                        <textarea name="links[${linkCount - 1}][description]" class="form-control" rows="2" placeholder="وصف الرابط"></textarea>
                    </div>
                </div>
            `;
            linksContainer.appendChild(linkItem);
            updateLinkNumbers();
            updateRemoveButtons();
        });

        linksContainer.addEventListener('click', function(e) {
            if (e.target.closest('.remove-link-btn')) {
                e.target.closest('.link-item').remove();
                updateLinkNumbers();
                updateRemoveButtons();
            }
        });
    }

    // معاينة الملفات
    const fileInput = document.querySelector('input[name="files[]"]');
    const filesPreview = document.getElementById('filesPreview');
    
    if (fileInput && filesPreview) {
        fileInput.addEventListener('change', function(e) {
            filesPreview.innerHTML = '';
            const files = e.target.files;
            
            for (let i = 0; i < files.length; i++) {
                const file = files[i];
                const fileSize = (file.size / 1024 / 1024).toFixed(2);
                const fileItem = document.createElement('div');
                fileItem.className = 'alert alert-info d-flex justify-content-between align-items-center';
                fileItem.innerHTML = `
                    <span><i class="fa-solid fa-file"></i> ${file.name} (${fileSize} MB)</span>
                    <button type="button" class="btn btn-sm btn-danger remove-file-btn" data-index="${i}">
                        <i class="fa-solid fa-times"></i>
                    </button>
                `;
                filesPreview.appendChild(fileItem);
            }
        });

        filesPreview.addEventListener('click', function(e) {
            if (e.target.closest('.remove-file-btn')) {
                const index = parseInt(e.target.closest('.remove-file-btn').dataset.index);
                const dt = new DataTransfer();
                const files = fileInput.files;
                
                for (let i = 0; i < files.length; i++) {
                    if (i !== index) {
                        dt.items.add(files[i]);
                    }
                }
                
                fileInput.files = dt.files;
                fileInput.dispatchEvent(new Event('change'));
            }
        });
    }

    function updateTextNumbers() {
        const textItems = textsContainer?.querySelectorAll('.text-item');
        if (textItems) {
            textItems.forEach((item, index) => {
                item.querySelector('.text-number').textContent = index + 1;
            });
        }
    }

    function updateLinkNumbers() {
        const linkItems = linksContainer?.querySelectorAll('.link-item');
        if (linkItems) {
            linkItems.forEach((item, index) => {
                item.querySelector('.link-number').textContent = index + 1;
                // تحديث أسماء الحقول
                const inputs = item.querySelectorAll('input, select, textarea');
                inputs.forEach(input => {
                    const name = input.getAttribute('name');
                    if (name) {
                        input.setAttribute('name', name.replace(/\[\d+\]/, `[${index}]`));
                    }
                });
            });
        }
    }

    function updateRemoveButtons() {
        const textItems = textsContainer?.querySelectorAll('.text-item');
        const linkItems = linksContainer?.querySelectorAll('.link-item');
        
        if (textItems) {
            textItems.forEach((item, index) => {
                const btn = item.querySelector('.remove-text-btn');
                if (btn) {
                    btn.style.display = textItems.length > 1 ? 'block' : 'none';
                }
            });
        }
        
        if (linkItems) {
            linkItems.forEach((item, index) => {
                const btn = item.querySelector('.remove-link-btn');
                if (btn) {
                    btn.style.display = linkItems.length > 1 ? 'block' : 'none';
                }
            });
        }
    }

    // التحقق من المحتوى قبل الإرسال
    const submitForm = document.getElementById('submitForm');
    if (submitForm) {
        submitForm.addEventListener('submit', function(e) {
            let hasContent = false;
            
            // التحقق من النصوص
            const texts = document.querySelectorAll('textarea[name="texts[]"]');
            texts.forEach(text => {
                if (text.value.trim()) {
                    hasContent = true;
                }
            });
            
            // التحقق من الملفات
            if (fileInput && fileInput.files.length > 0) {
                hasContent = true;
            }
            
            // التحقق من الروابط
            const links = document.querySelectorAll('input[name*="[url]"]');
            links.forEach(link => {
                if (link.value.trim()) {
                    hasContent = true;
                }
            });
            
            if (!hasContent) {
                e.preventDefault();
                alert('يجب إضافة محتوى على الأقل (نص، ملف، أو رابط)');
                return false;
            }
        });
    }

    // تحديث الأزرار الأولية
    updateRemoveButtons();
});
</script>
@endpush

