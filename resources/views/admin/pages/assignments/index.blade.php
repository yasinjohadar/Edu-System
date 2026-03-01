@extends('admin.layouts.master')

@section('page-title')
    قائمة الواجبات
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
                    <h5 class="page-title fs-21 mb-1">قائمة الواجبات</h5>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header align-items-center d-flex gap-3">
                            @can('assignment-create')
                            <a href="{{ route('admin.assignments.create') }}" class="btn btn-primary btn-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-left: 4px; vertical-align: middle;">
                                    <line x1="12" y1="5" x2="12" y2="19"></line>
                                    <line x1="5" y1="12" x2="19" y2="12"></line>
                                </svg>
                                إضافة واجب جديد
                            </a>
                            @endcan
                            <div class="flex-shrink-0">
                                <form action="{{ route('admin.assignments.index') }}" method="GET" class="d-flex align-items-center gap-2">
                                    <input style="width: 200px" type="text" name="query" class="form-control" placeholder="بحث بالعنوان أو الوصف" value="{{ request('query') }}">
                                    <select name="subject_id" class="form-select" style="width: 150px;">
                                        <option value="">كل المواد</option>
                                        @foreach($subjects as $subject)
                                            <option value="{{ $subject->id }}" {{ request('subject_id') == $subject->id ? 'selected' : '' }}>{{ $subject->name }}</option>
                                        @endforeach
                                    </select>
                                    <select name="teacher_id" class="form-select" style="width: 150px;">
                                        <option value="">كل المعلمين</option>
                                        @foreach($teachers as $teacher)
                                            <option value="{{ $teacher->id }}" {{ request('teacher_id') == $teacher->id ? 'selected' : '' }}>{{ $teacher->user->name }}</option>
                                        @endforeach
                                    </select>
                                    <select name="section_id" class="form-select" style="width: 150px;">
                                        <option value="">كل الفصول</option>
                                        @foreach($sections as $section)
                                            <option value="{{ $section->id }}" {{ request('section_id') == $section->id ? 'selected' : '' }}>{{ $section->name }}</option>
                                        @endforeach
                                    </select>
                                    <select name="status" class="form-select" style="width: 120px;">
                                        <option value="">كل الحالات</option>
                                        <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>مسودة</option>
                                        <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>منشور</option>
                                        <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>مغلق</option>
                                    </select>
                                    <select name="is_active" class="form-select" style="width: 100px;">
                                        <option value="">كل الحالات</option>
                                        <option value="1" {{ request('is_active') == '1' ? 'selected' : '' }}>نشط</option>
                                        <option value="0" {{ request('is_active') == '0' ? 'selected' : '' }}>غير نشط</option>
                                    </select>
                                    <button type="submit" class="btn btn-secondary">بحث</button>
                                    <a href="{{ route('admin.assignments.index') }}" class="btn btn-danger">مسح</a>
                                </form>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover align-middle table-nowrap mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>رقم الواجب</th>
                                            <th>العنوان</th>
                                            <th>المادة</th>
                                            <th>المعلم</th>
                                            <th>الفصل</th>
                                            <th>الدرجة الكلية</th>
                                            <th>تاريخ الاستحقاق</th>
                                            <th>الحالة</th>
                                            <th>التسليمات</th>
                                            <th>العمليات</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($assignments as $assignment)
                                            <tr>
                                                <td>{{ $assignment->id }}</td>
                                                <td><strong>{{ $assignment->assignment_number }}</strong></td>
                                                <td>
                                                    <strong>{{ $assignment->title }}</strong>
                                                    @if($assignment->description)
                                                        <br><small class="text-muted">{{ Str::limit($assignment->description, 50) }}</small>
                                                    @endif
                                                </td>
                                                <td>{{ $assignment->subject->name ?? 'غير محدد' }}</td>
                                                <td>{{ $assignment->teacher->user->name ?? 'غير محدد' }}</td>
                                                <td>{{ $assignment->section->name ?? 'كل الفصول' }}</td>
                                                <td><strong>{{ number_format($assignment->total_marks, 2) }}</strong></td>
                                                <td>
                                                    {{ $assignment->due_date->format('Y-m-d') }}
                                                    @if($assignment->isOverdue())
                                                        <br><small class="text-danger">متأخر</small>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($assignment->status == 'published')
                                                        <span class="badge bg-success">{{ $assignment->status_name }}</span>
                                                    @elseif($assignment->status == 'closed')
                                                        <span class="badge bg-danger">{{ $assignment->status_name }}</span>
                                                    @else
                                                        <span class="badge bg-secondary">{{ $assignment->status_name }}</span>
                                                    @endif
                                                    @if(!$assignment->is_active)
                                                        <br><small class="text-muted">غير نشط</small>
                                                    @endif
                                                </td>
                                                <td>
                                                    <span class="badge bg-info">{{ $assignment->submissions()->count() }}</span>
                                                </td>
                                                <td>
                                                    <div class="d-flex gap-2">
                                                        <a href="{{ route('admin.assignments.show', $assignment->id) }}" class="btn btn-sm btn-info" title="عرض">
                                                            <i class="fa-solid fa-eye"></i>
                                                        </a>
                                                        <a href="{{ route('admin.assignments.submissions.index', $assignment->id) }}" class="btn btn-sm btn-primary" title="التسليمات">
                                                            <i class="fa-solid fa-file-arrow-up"></i>
                                                        </a>
                                                        @can('assignment-edit')
                                                        <a href="{{ route('admin.assignments.edit', $assignment->id) }}" class="btn btn-sm btn-warning" title="تعديل">
                                                            <i class="fa-solid fa-edit"></i>
                                                        </a>
                                                        @if($assignment->status == 'draft')
                                                        <form action="{{ route('admin.assignments.publish', $assignment->id) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            <button type="submit" class="btn btn-sm btn-success" title="نشر">
                                                                <i class="fa-solid fa-check"></i>
                                                            </button>
                                                        </form>
                                                        @endif
                                                        @if($assignment->status == 'published')
                                                        <form action="{{ route('admin.assignments.close', $assignment->id) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            <button type="submit" class="btn btn-sm btn-secondary" title="إغلاق">
                                                                <i class="fa-solid fa-lock"></i>
                                                            </button>
                                                        </form>
                                                        @endif
                                                        @endcan
                                                        @can('assignment-delete')
                                                        @if($assignment->submissions()->count() == 0)
                                                        <button type="button" class="btn btn-sm btn-danger" 
                                                                data-delete-url="{{ route('admin.assignments.destroy', $assignment->id) }}"
                                                                data-delete-message="هل أنت متأكد من رغبتك في حذف الواجب <strong>{{ $assignment->title }}</strong>؟"
                                                                title="حذف">
                                                            <i class="fa-solid fa-trash-can"></i>
                                                        </button>
                                                        @endif
                                                        @endcan
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="11" class="text-center">لا توجد واجبات</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-3">
                                {{ $assignments->links() }}
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

