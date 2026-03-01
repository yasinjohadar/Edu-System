@extends('admin.layouts.master')

@section('page-title')
    تفاصيل التصنيف
@stop

@section('content')
    <!-- Start::app-content -->
    <div class="main-content app-content">
        <div class="container-fluid">
            <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
                <div class="my-auto">
                    <h5 class="page-title fs-21 mb-1">تفاصيل التصنيف</h5>
                </div>
                <div>
                    <a href="{{ route('admin.book-categories.edit', $category->id) }}" class="btn btn-warning btn-sm">تعديل</a>
                    <a href="{{ route('admin.book-categories.index') }}" class="btn btn-secondary btn-sm">رجوع</a>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">{{ $category->name }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <strong>الرمز:</strong> {{ $category->code }}
                                </div>
                                <div class="col-md-6">
                                    <strong>الاسم بالإنجليزية:</strong> {{ $category->name_en ?? '-' }}
                                </div>
                            </div>
                            @if($category->parent)
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <strong>التصنيف الأب:</strong> {{ $category->parent->name }}
                                    </div>
                                </div>
                            @endif
                            @if($category->description)
                                <div class="mb-3">
                                    <strong>الوصف:</strong>
                                    <p>{{ $category->description }}</p>
                                </div>
                            @endif
                            <div class="mb-3">
                                <strong>الحالة:</strong>
                                @if($category->is_active)
                                    <span class="badge bg-success">نشط</span>
                                @else
                                    <span class="badge bg-danger">غير نشط</span>
                                @endif
                            </div>
                            <div class="mb-3">
                                <strong>عدد الكتب:</strong> <span class="badge bg-info">{{ $category->books->count() }}</span>
                            </div>
                        </div>
                    </div>

                    @if($category->books->count() > 0)
                        <div class="card mt-3">
                            <div class="card-header">
                                <h5 class="card-title">الكتب في هذا التصنيف</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>العنوان</th>
                                                <th>المؤلف</th>
                                                <th>النسخ المتاحة</th>
                                                <th>الحالة</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($category->books->take(10) as $book)
                                                <tr>
                                                    <td>
                                                        <a href="{{ route('admin.books.show', $book->id) }}">{{ $book->title }}</a>
                                                    </td>
                                                    <td>{{ $book->author }}</td>
                                                    <td>{{ $book->available_copies }} / {{ $book->total_copies }}</td>
                                                    <td>
                                                        @if($book->is_active)
                                                            <span class="badge bg-success">نشط</span>
                                                        @else
                                                            <span class="badge bg-danger">غير نشط</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!-- End::app-content -->
@stop

