@extends('admin.layouts.master')

@section('page-title')
    إضافة كتاب جديد
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
                    <h5 class="page-title fs-21 mb-1">إضافة كتاب جديد</h5>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">معلومات الكتاب</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.books.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">التصنيف <span class="text-danger">*</span></label>
                                        <select name="category_id" class="form-select" required>
                                            <option value="">اختر التصنيف</option>
                                            @foreach($categories as $category)
                                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('category_id')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">ISBN</label>
                                        <input type="text" name="isbn" class="form-control" value="{{ old('isbn') }}">
                                        @error('isbn')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">العنوان <span class="text-danger">*</span></label>
                                        <input type="text" name="title" class="form-control" value="{{ old('title') }}" required>
                                        @error('title')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">العنوان بالإنجليزية</label>
                                        <input type="text" name="title_en" class="form-control" value="{{ old('title_en') }}">
                                        @error('title_en')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">المؤلف <span class="text-danger">*</span></label>
                                        <input type="text" name="author" class="form-control" value="{{ old('author') }}" required>
                                        @error('author')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">الناشر</label>
                                        <input type="text" name="publisher" class="form-control" value="{{ old('publisher') }}">
                                        @error('publisher')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">سنة النشر</label>
                                        <input type="number" name="publication_year" class="form-control" min="1000" max="{{ date('Y') }}" value="{{ old('publication_year') }}">
                                        @error('publication_year')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">اللغة</label>
                                        <select name="language" class="form-select">
                                            <option value="ar" {{ old('language', 'ar') == 'ar' ? 'selected' : '' }}>عربي</option>
                                            <option value="en" {{ old('language') == 'en' ? 'selected' : '' }}>إنجليزي</option>
                                        </select>
                                        @error('language')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">عدد النسخ <span class="text-danger">*</span></label>
                                        <input type="number" name="total_copies" class="form-control" min="1" value="{{ old('total_copies', 1) }}" required>
                                        @error('total_copies')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">السعر</label>
                                        <input type="number" name="price" class="form-control" step="0.01" min="0" value="{{ old('price') }}">
                                        @error('price')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">عدد الصفحات</label>
                                        <input type="number" name="pages" class="form-control" min="1" value="{{ old('pages') }}">
                                        @error('pages')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">الطبعة</label>
                                        <input type="text" name="edition" class="form-control" value="{{ old('edition') }}">
                                        @error('edition')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">موقع الكتاب</label>
                                        <input type="text" name="location" class="form-control" value="{{ old('location') }}" placeholder="مثال: رف A-3">
                                        @error('location')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">صورة الغلاف</label>
                                        <input type="file" name="cover_image" class="form-control" accept="image/*">
                                        @error('cover_image')
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
                                    <a href="{{ route('admin.books.index') }}" class="btn btn-secondary">إلغاء</a>
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

