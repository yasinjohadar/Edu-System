@extends('admin.layouts.master')

@section('page-title')
    تفاصيل الكتاب
@stop

@section('content')
    <!-- Start::app-content -->
    <div class="main-content app-content">
        <div class="container-fluid">
            <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
                <div class="my-auto">
                    <h5 class="page-title fs-21 mb-1">تفاصيل الكتاب</h5>
                </div>
                <div>
                    <a href="{{ route('admin.books.edit', $book->id) }}" class="btn btn-warning btn-sm">تعديل</a>
                    <a href="{{ route('admin.books.index') }}" class="btn btn-secondary btn-sm">رجوع</a>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-4">
                    <div class="card">
                        <div class="card-body text-center">
                            @if($book->cover_image)
                                <img src="{{ asset('storage/' . $book->cover_image) }}" alt="{{ $book->title }}" class="img-fluid rounded mb-3" style="max-height: 400px;">
                            @else
                                <div class="bg-secondary rounded d-flex align-items-center justify-content-center text-white mb-3" style="height: 400px;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                                        <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                                    </svg>
                                </div>
                            @endif
                            <h4>{{ $book->title }}</h4>
                            @if($book->title_en)
                                <p class="text-muted">{{ $book->title_en }}</p>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-xl-8">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">معلومات الكتاب</h5>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <strong>المؤلف:</strong> {{ $book->author }}
                                </div>
                                <div class="col-md-6">
                                    <strong>التصنيف:</strong> <span class="badge bg-info">{{ $book->category->name }}</span>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <strong>الناشر:</strong> {{ $book->publisher ?? '-' }}
                                </div>
                                <div class="col-md-6">
                                    <strong>سنة النشر:</strong> {{ $book->publication_year ?? '-' }}
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <strong>ISBN:</strong> {{ $book->isbn ?? '-' }}
                                </div>
                                <div class="col-md-6">
                                    <strong>اللغة:</strong> {{ $book->language == 'ar' ? 'عربي' : 'إنجليزي' }}
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <strong>عدد النسخ:</strong> {{ $book->total_copies }}
                                </div>
                                <div class="col-md-6">
                                    <strong>النسخ المتاحة:</strong> 
                                    @if($book->available_copies > 0)
                                        <span class="badge bg-success">{{ $book->available_copies }}</span>
                                    @else
                                        <span class="badge bg-danger">0</span>
                                    @endif
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <strong>عدد الصفحات:</strong> {{ $book->pages ?? '-' }}
                                </div>
                                <div class="col-md-6">
                                    <strong>الطبعة:</strong> {{ $book->edition ?? '-' }}
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <strong>السعر:</strong> {{ $book->price ? number_format($book->price, 2) . ' ر.س' : '-' }}
                                </div>
                                <div class="col-md-6">
                                    <strong>الموقع:</strong> {{ $book->location ?? '-' }}
                                </div>
                            </div>
                            @if($book->description)
                                <div class="mb-3">
                                    <strong>الوصف:</strong>
                                    <p>{{ $book->description }}</p>
                                </div>
                            @endif
                            <div class="mb-3">
                                <strong>الحالة:</strong>
                                @if($book->is_active)
                                    <span class="badge bg-success">نشط</span>
                                @else
                                    <span class="badge bg-danger">غير نشط</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    @if($book->borrowings->count() > 0)
                        <div class="card mt-3">
                            <div class="card-header">
                                <h5 class="card-title">سجل الاستعارات</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>رقم الاستعارة</th>
                                                <th>الطالب</th>
                                                <th>تاريخ الاستعارة</th>
                                                <th>تاريخ الاستحقاق</th>
                                                <th>الحالة</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($book->borrowings->take(10) as $borrowing)
                                                <tr>
                                                    <td>{{ $borrowing->borrowing_number }}</td>
                                                    <td>{{ $borrowing->student->user->name ?? '-' }}</td>
                                                    <td>{{ $borrowing->borrow_date->format('Y-m-d') }}</td>
                                                    <td>{{ $borrowing->due_date->format('Y-m-d') }}</td>
                                                    <td>
                                                        @if($borrowing->status == 'borrowed')
                                                            <span class="badge bg-primary">مستعار</span>
                                                        @elseif($borrowing->status == 'returned')
                                                            <span class="badge bg-success">مُرجع</span>
                                                        @elseif($borrowing->status == 'overdue')
                                                            <span class="badge bg-danger">متأخر</span>
                                                        @else
                                                            <span class="badge bg-warning">مفقود</span>
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

