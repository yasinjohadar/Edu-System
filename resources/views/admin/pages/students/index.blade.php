@extends('admin.layouts.master')

@section('page-title')
    قائمة الطلاب
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
                    <h5 class="page-title fs-21 mb-1">كافة الطلاب</h5>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header align-items-center d-flex gap-3">
                            <div class="d-flex gap-2">
                                <a href="{{ route('admin.students.create') }}" class="btn btn-primary btn-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-left: 4px; vertical-align: middle;">
                                        <line x1="12" y1="5" x2="12" y2="19"></line>
                                        <line x1="5" y1="12" x2="19" y2="12"></line>
                                    </svg>
                                    إضافة طالب جديد
                                </a>
                                <a href="{{ route('admin.export.students', request()->all()) }}" class="btn btn-success btn-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-left: 4px; vertical-align: middle;">
                                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                        <polyline points="7 10 12 15 17 10"></polyline>
                                        <line x1="12" y1="15" x2="12" y2="3"></line>
                                    </svg>
                                    تصدير Excel
                                </a>
                            </div>
                            <div class="flex-shrink-0">
                                <form action="{{ route('admin.students.index') }}" method="GET" class="d-flex align-items-center gap-2">
                                    <input style="width: 250px" type="text" name="query" class="form-control" placeholder="بحث بالاسم أو الإيميل أو رقم القيد" value="{{ request('query') }}">
                                    <select name="status" class="form-select" style="width: 150px;">
                                        <option value="">كل الحالات</option>
                                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>نشط</option>
                                        <option value="graduated" {{ request('status') == 'graduated' ? 'selected' : '' }}>متخرج</option>
                                        <option value="transferred" {{ request('status') == 'transferred' ? 'selected' : '' }}>منقول</option>
                                        <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>معلق</option>
                                    </select>
                                    <select name="class_id" class="form-select" style="width: 180px;">
                                        <option value="">كل الصفوف</option>
                                        @foreach($classes as $class)
                                            <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>
                                                {{ $class->grade->name }} - {{ $class->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <select name="section_id" class="form-select" style="width: 150px;">
                                        <option value="">كل الفصول</option>
                                        @foreach($sections as $section)
                                            <option value="{{ $section->id }}" {{ request('section_id') == $section->id ? 'selected' : '' }}>
                                                {{ $section->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <button type="submit" class="btn btn-secondary">بحث</button>
                                    <a href="{{ route('admin.students.index') }}" class="btn btn-danger">مسح</a>
                                </form>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover align-middle table-nowrap mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>اسم الطالب</th>
                                            <th>رقم القيد</th>
                                            <th>البريد الإلكتروني</th>
                                            <th>الصف / الفصل</th>
                                            <th>أولياء الأمور</th>
                                            <th>الحالة</th>
                                            <th>العمليات</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($students as $student)
                                            <tr>
                                                <td>{{ $student->id }}</td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        @if($student->photo)
                                                            <img src="{{ asset('storage/' . $student->photo) }}" alt="{{ $student->user->name }}" class="avatar avatar-sm rounded-circle me-2">
                                                        @else
                                                            <div class="avatar avatar-sm rounded-circle bg-primary me-2 d-flex align-items-center justify-content-center text-white">
                                                                {{ substr($student->user->name, 0, 1) }}
                                                            </div>
                                                        @endif
                                                        <div>
                                                            <h6 class="mb-0">{{ $student->user->name }}</h6>
                                                            <small class="text-muted">{{ $student->user->phone ?? '-' }}</small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>{{ $student->student_code }}</td>
                                                <td>{{ $student->user->email }}</td>
                                                <td>
                                                    @if($student->class && $student->section)
                                                        <span class="badge bg-info">{{ $student->class->grade->name }} - {{ $student->class->name }}</span>
                                                        <br>
                                                        <small class="text-muted">الفصل: {{ $student->section->name }}</small>
                                                    @else
                                                        <span class="text-muted">غير مسجل</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($student->parents->count() > 0)
                                                        @foreach($student->parents->take(2) as $parent)
                                                            <span class="badge bg-secondary me-1">{{ $parent->user->name }}</span>
                                                        @endforeach
                                                        @if($student->parents->count() > 2)
                                                            <span class="badge bg-light text-dark">+{{ $student->parents->count() - 2 }}</span>
                                                        @endif
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($student->status == 'active')
                                                        <span class="badge bg-success">نشط</span>
                                                    @elseif($student->status == 'graduated')
                                                        <span class="badge bg-info">متخرج</span>
                                                    @elseif($student->status == 'transferred')
                                                        <span class="badge bg-warning">منقول</span>
                                                    @else
                                                        <span class="badge bg-danger">معلق</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="d-flex gap-2">
                                                        <a href="{{ route('admin.students.show', $student->id) }}" class="btn btn-sm btn-primary">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-left: 4px; vertical-align: middle;">
                                                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                                <circle cx="12" cy="12" r="3"></circle>
                                                            </svg>
                                                            عرض
                                                        </a>
                                                        <a href="{{ route('admin.students.edit', $student->id) }}" class="btn btn-sm btn-info">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-left: 4px; vertical-align: middle;">
                                                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                                            </svg>
                                                            تعديل
                                                        </a>
                                                        <button type="button" class="btn btn-sm btn-danger" 
                                                                data-delete-url="{{ route('admin.students.destroy', $student->id) }}"
                                                                data-delete-message="هل أنت متأكد من رغبتك في حذف الطالب <strong>{{ $student->user->name }}</strong>؟">
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
                                                <td colspan="8" class="text-center">لا يوجد طلاب</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-3">
                                {{ $students->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    @include('admin.components.delete-modal')
@stop

