@extends('admin.layouts.master')

@section('page-title')
    قائمة المراحل التعليمية
@stop

@section('css')
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

            <!-- Page Header -->
            <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
                <div class="my-auto">
                    <h5 class="page-title fs-21 mb-1">كافة المراحل التعليمية</h5>
                </div>
            </div>
            <!-- Page Header Close -->

            <!-- Start::row-1 -->
            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header align-items-center d-flex gap-3">
                            <a href="{{ route('admin.grades.create') }}" class="btn btn-primary btn-sm">إضافة مرحلة جديدة</a>

                            <div class="flex-shrink-0">
                                <div class="form-check form-switch form-switch-right form-switch-md">
                                    <form action="{{ route('admin.grades.index') }}" method="GET"
                                        class="d-flex align-items-center gap-2">
                                        <input style="width: 300px" type="text" name="query" class="form-control"
                                            placeholder="بحث بالاسم" value="{{ request('query') }}">

                                        <select name="is_active" class="form-select">
                                            <option value="">كل الحالات</option>
                                            <option value="1" {{ request('is_active') == '1' ? 'selected' : '' }}>نشط</option>
                                            <option value="0" {{ request('is_active') == '0' ? 'selected' : '' }}>غير نشط</option>
                                        </select>

                                        <button type="submit" class="btn btn-secondary">بحث</button>
                                        <a href="{{ route('admin.grades.index') }}" class="btn btn-danger">مسح</a>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover align-middle table-nowrap mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th scope="col" style="width: 40px;">#</th>
                                            <th scope="col" style="min-width: 150px;">اسم المرحلة</th>
                                            <th scope="col" style="min-width: 120px;">الحد الأدنى للعمر</th>
                                            <th scope="col" style="min-width: 120px;">الحد الأقصى للعمر</th>
                                            <th scope="col" style="min-width: 120px;">الرسوم</th>
                                            <th scope="col" style="min-width: 100px;">الترتيب</th>
                                            <th scope="col" style="min-width: 110px;">الحالة</th>
                                            <th scope="col" style="min-width: 200px;">العمليات</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($grades as $grade)
                                            <tr>
                                                <td>{{ $grade->id }}</td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div>
                                                            <h6 class="mb-0">{{ $grade->name }}</h6>
                                                            @if($grade->name_en)
                                                                <small class="text-muted">{{ $grade->name_en }}</small>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>{{ $grade->min_age ?? '-' }}</td>
                                                <td>{{ $grade->max_age ?? '-' }}</td>
                                                <td>{{ $grade->fees ? number_format($grade->fees, 2) . ' ر.س' : '-' }}</td>
                                                <td>{{ $grade->order }}</td>
                                                <td>
                                                    @if($grade->is_active)
                                                        <span class="badge bg-success">نشط</span>
                                                    @else
                                                        <span class="badge bg-danger">غير نشط</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="d-flex gap-2">
                                                        <a href="{{ route('admin.grades.edit', $grade->id) }}" class="btn btn-sm btn-info">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-left: 4px; vertical-align: middle;">
                                                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                                            </svg>
                                                            تعديل
                                                        </a>
                                                        <button type="button" class="btn btn-sm btn-danger" 
                                                                data-delete-url="{{ route('admin.grades.destroy', $grade->id) }}"
                                                                data-delete-message="هل أنت متأكد من رغبتك في حذف المرحلة <strong>{{ $grade->name }}</strong>؟">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-left: 4px; vertical-align: middle;">
                                                                <polyline points="3 6 5 6 21 6"></polyline>
                                                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                                            </svg>
                                                            حذف
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="8" class="text-center">لا توجد مراحل</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-3">
                                {{ $grades->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End::row-1 -->

        </div>
    </div>
    <!-- End::app-content -->
    
    @include('admin.components.delete-modal')
@stop

