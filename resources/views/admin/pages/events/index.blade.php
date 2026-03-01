@extends('admin.layouts.master')

@section('page-title')
    قائمة الأحداث
@stop

@section('content')
    @if (\Session::has('success'))
        <div class="alert alert-success">
            <ul>
                <li>{!! \Session::get('success') !!}</li>
            </ul>
        </div>
    @endif

    <!-- Start::app-content -->
    <div class="main-content app-content">
        <div class="container-fluid">
            <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
                <div class="my-auto">
                    <h5 class="page-title fs-21 mb-1">كافة الأحداث</h5>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header align-items-center d-flex gap-3">
                            <div class="d-flex gap-2">
                                <a href="{{ route('admin.events.create') }}" class="btn btn-primary btn-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <line x1="12" y1="5" x2="12" y2="19"></line>
                                        <line x1="5" y1="12" x2="19" y2="12"></line>
                                    </svg>
                                    إضافة حدث جديد
                                </a>
                            </div>
                            <div class="flex-shrink-0">
                                <form action="{{ route('admin.events.index') }}" method="GET" class="d-flex align-items-center gap-2">
                                    <select name="type" class="form-select" style="width: 150px;">
                                        <option value="">كل الأنواع</option>
                                        <option value="holiday" {{ request('type') == 'holiday' ? 'selected' : '' }}>عطلة</option>
                                        <option value="exam" {{ request('type') == 'exam' ? 'selected' : '' }}>امتحان</option>
                                        <option value="activity" {{ request('type') == 'activity' ? 'selected' : '' }}>نشاط</option>
                                        <option value="meeting" {{ request('type') == 'meeting' ? 'selected' : '' }}>اجتماع</option>
                                        <option value="other" {{ request('type') == 'other' ? 'selected' : '' }}>أخرى</option>
                                    </select>
                                    <select name="category_id" class="form-select" style="width: 180px;">
                                        <option value="">كل الفئات</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <input type="date" name="date_from" class="form-control" style="width: 150px;" value="{{ request('date_from') }}" placeholder="من تاريخ">
                                    <input type="date" name="date_to" class="form-control" style="width: 150px;" value="{{ request('date_to') }}" placeholder="إلى تاريخ">
                                    <button type="submit" class="btn btn-secondary">بحث</button>
                                    <a href="{{ route('admin.events.index') }}" class="btn btn-danger">مسح</a>
                                </form>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover align-middle table-nowrap mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>العنوان</th>
                                            <th>النوع</th>
                                            <th>الفئة</th>
                                            <th>تاريخ البدء</th>
                                            <th>تاريخ الانتهاء</th>
                                            <th>المكان</th>
                                            <th>الحالة</th>
                                            <th>العمليات</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($events as $event)
                                            <tr>
                                                <td>{{ $event->id }}</td>
                                                <td>{{ $event->title }}</td>
                                                <td><span class="badge bg-info">{{ $event->type_name }}</span></td>
                                                <td>
                                                    @if($event->category)
                                                        <span class="badge" style="background-color: {{ $event->category->color }}">{{ $event->category->name }}</span>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td>{{ $event->start_date->format('Y-m-d') }}</td>
                                                <td>{{ $event->end_date ? $event->end_date->format('Y-m-d') : '-' }}</td>
                                                <td>{{ $event->location ?? '-' }}</td>
                                                <td>
                                                    @if($event->is_active)
                                                        <span class="badge bg-success">نشط</span>
                                                    @else
                                                        <span class="badge bg-danger">غير نشط</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="d-flex gap-2">
                                                        <a href="{{ route('admin.events.show', $event) }}" class="btn btn-sm btn-info">عرض</a>
                                                        <a href="{{ route('admin.events.edit', $event) }}" class="btn btn-sm btn-primary">تعديل</a>
                                                        <form action="{{ route('admin.events.destroy', $event) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من الحذف؟')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-danger">حذف</button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="9" class="text-center">لا توجد أحداث</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-3">
                                {{ $events->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End::app-content -->
@stop

