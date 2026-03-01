@extends('admin.layouts.master')

@section('page-title')
    إضافة مادة تعليمية جديدة
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
                    <h5 class="page-title fs-21 mb-1">إضافة مادة تعليمية جديدة</h5>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">معلومات المادة</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.lecture-materials.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">المحاضرة <span class="text-danger">*</span></label>
                                        <select name="lecture_id" class="form-select" required>
                                            <option value="">اختر المحاضرة</option>
                                            @foreach($lectures as $lecture)
                                                <option value="{{ $lecture->id }}" {{ old('lecture_id') == $lecture->id ? 'selected' : '' }}>{{ $lecture->title }}</option>
                                            @endforeach
                                        </select>
                                        @error('lecture_id')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">النوع <span class="text-danger">*</span></label>
                                        <select name="type" class="form-select" required id="material_type">
                                            <option value="file" {{ old('type', 'file') == 'file' ? 'selected' : '' }}>ملف</option>
                                            <option value="link" {{ old('type') == 'link' ? 'selected' : '' }}>رابط</option>
                                            <option value="video" {{ old('type') == 'video' ? 'selected' : '' }}>فيديو</option>
                                            <option value="audio" {{ old('type') == 'audio' ? 'selected' : '' }}>صوت</option>
                                            <option value="image" {{ old('type') == 'image' ? 'selected' : '' }}>صورة</option>
                                        </select>
                                        @error('type')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">العنوان <span class="text-danger">*</span></label>
                                        <input type="text" name="title" class="form-control" value="{{ old('title') }}" required>
                                        @error('title')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">الوصف</label>
                                        <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
                                        @error('description')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3" id="file_path_div">
                                        <label class="form-label">الملف <span class="text-danger">*</span></label>
                                        <input type="file" name="file_path" class="form-control" accept="*/*">
                                        <small class="text-muted">الحد الأقصى: 10MB</small>
                                        @error('file_path')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3" id="external_url_div" style="display: none;">
                                        <label class="form-label">الرابط الخارجي <span class="text-danger">*</span></label>
                                        <input type="url" name="external_url" class="form-control" value="{{ old('external_url') }}" placeholder="https://example.com">
                                        @error('external_url')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">ترتيب العرض</label>
                                        <input type="number" name="sort_order" class="form-control" min="0" value="{{ old('sort_order', 0) }}">
                                        @error('sort_order')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                            <label class="form-check-label">نشط</label>
                                        </div>
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
                                    <a href="{{ route('admin.lecture-materials.index') }}" class="btn btn-secondary">إلغاء</a>
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
document.getElementById('material_type').addEventListener('change', function() {
    const type = this.value;
    const fileDiv = document.getElementById('file_path_div');
    const urlDiv = document.getElementById('external_url_div');

    if (type === 'link') {
        fileDiv.style.display = 'none';
        urlDiv.style.display = 'block';
    } else {
        fileDiv.style.display = 'block';
        urlDiv.style.display = 'none';
    }
});

// تشغيل عند التحميل
document.getElementById('material_type').dispatchEvent(new Event('change'));
</script>
@endpush

