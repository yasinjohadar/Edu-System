@extends('admin.layouts.master')

@section('page-title')
    قائمة الكتب
@stop

@section('content')
    @if (\Session::has('success'))
        <div class="alert alert-success">
            <ul>
                <li>{!! \Session::get('success') !!}</li>
            </ul>
        </div>
    @endif

    @if (\Session::has('error'))
        <div class="alert alert-danger">
            <ul>
                <li>{!! \Session::get('error') !!}</li>
            </ul>
        </div>
    @endif

    <!-- Start::app-content -->
    <div class="main-content app-content">
        <div class="container-fluid">
            <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
                <div class="my-auto">
                    <h5 class="page-title fs-21 mb-1">قائمة الكتب</h5>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header align-items-center d-flex gap-3">
                            @can('book-create')
                            <a href="{{ route('admin.books.create') }}" class="btn btn-primary btn-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-left: 4px; vertical-align: middle;">
                                    <line x1="12" y1="5" x2="12" y2="19"></line>
                                    <line x1="5" y1="12" x2="19" y2="12"></line>
                                </svg>
                                إضافة كتاب جديد
                            </a>
                            @endcan
                            <div class="flex-shrink-0">
                                <form action="{{ route('admin.books.index') }}" method="GET" class="d-flex align-items-center gap-2">
                                    <input style="width: 250px" type="text" name="query" class="form-control" placeholder="بحث بالعنوان أو المؤلف أو ISBN" value="{{ request('query') }}">
                                    <select name="category_id" class="form-select" style="width: 180px;">
                                        <option value="">كل التصنيفات</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                    <select name="is_active" class="form-select" style="width: 150px;">
                                        <option value="">كل الحالات</option>
                                        <option value="1" {{ request('is_active') == '1' ? 'selected' : '' }}>نشط</option>
                                        <option value="0" {{ request('is_active') == '0' ? 'selected' : '' }}>غير نشط</option>
                                    </select>
                                    <button type="submit" class="btn btn-secondary">بحث</button>
                                    <a href="{{ route('admin.books.index') }}" class="btn btn-danger">مسح</a>
                                </form>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover align-middle table-nowrap mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>الغلاف</th>
                                            <th>العنوان</th>
                                            <th>المؤلف</th>
                                            <th>التصنيف</th>
                                            <th>ISBN</th>
                                            <th>النسخ</th>
                                            <th>المتاح</th>
                                            <th>الحالة</th>
                                            <th>العمليات</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($books as $book)
                                            <tr>
                                                <td>{{ $book->id }}</td>
                                                <td>
                                                    @if($book->cover_image)
                                                        <img src="{{ asset('storage/' . $book->cover_image) }}" alt="{{ $book->title }}" class="avatar avatar-sm rounded" style="object-fit: cover;">
                                                    @else
                                                        <div class="avatar avatar-sm rounded bg-secondary d-flex align-items-center justify-content-center text-white">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                                <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                                                                <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                                                            </svg>
                                                        </div>
                                                    @endif
                                                </td>
                                                <td>
                                                    <strong>{{ $book->title }}</strong>
                                                    @if($book->title_en)
                                                        <br><small class="text-muted">{{ $book->title_en }}</small>
                                                    @endif
                                                </td>
                                                <td>{{ $book->author }}</td>
                                                <td><span class="badge bg-info">{{ $book->category->name }}</span></td>
                                                <td><small>{{ $book->isbn ?? '-' }}</small></td>
                                                <td>{{ $book->total_copies }}</td>
                                                <td>
                                                    @if($book->available_copies > 0)
                                                        <span class="badge bg-success">{{ $book->available_copies }}</span>
                                                    @else
                                                        <span class="badge bg-danger">0</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($book->is_active)
                                                        <span class="badge bg-success">نشط</span>
                                                    @else
                                                        <span class="badge bg-danger">غير نشط</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="d-flex gap-2">
                                                        <a href="{{ route('admin.books.show', $book->id) }}" class="btn btn-sm btn-info" title="عرض">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                                <circle cx="12" cy="12" r="3"></circle>
                                                            </svg>
                                                        </a>
                                                        @can('book-edit')
                                                        <a href="{{ route('admin.books.edit', $book->id) }}" class="btn btn-sm btn-warning" title="تعديل">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                                            </svg>
                                                        </a>
                                                        @endcan
                                                        @can('book-delete')
                                                        <button type="button" class="btn btn-sm btn-danger" 
                                                                data-delete-url="{{ route('admin.books.destroy', $book->id) }}"
                                                                data-delete-message="هل أنت متأكد من رغبتك في حذف الكتاب <strong>{{ $book->title }}</strong>؟"
                                                                title="حذف">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                                <polyline points="3 6 5 6 21 6"></polyline>
                                                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                                            </svg>
                                                        </button>
                                                        @endcan
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="10" class="text-center">لا توجد كتب</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-3">
                                {{ $books->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End::app-content -->

    @include('admin.components.delete-modal')
@stop

