@extends('admin.layouts.master')

@section('page-title')
    قائمة الدرجات والتقييم
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
                    <h5 class="page-title fs-21 mb-1">الدرجات والتقييم</h5>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header align-items-center d-flex gap-3">
                            @can('grade-create')
                            <a href="{{ route('admin.grade-records.create') }}" class="btn btn-primary btn-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-left: 4px; vertical-align: middle;">
                                    <line x1="12" y1="5" x2="12" y2="19"></line>
                                    <line x1="5" y1="12" x2="19" y2="12"></line>
                                </svg>
                                إدخال درجات جديدة
                            </a>
                            @endcan
                            <div class="flex-shrink-0">
                                <form action="{{ route('admin.grade-records.index') }}" method="GET" class="d-flex align-items-center gap-2">
                                    <select name="section_id" class="form-select" style="width: 180px;">
                                        <option value="">كل الفصول</option>
                                        @foreach($sections as $section)
                                            <option value="{{ $section->id }}" {{ request('section_id') == $section->id ? 'selected' : '' }}>
                                                {{ $section->class->name }} - {{ $section->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <select name="subject_id" class="form-select" style="width: 180px;">
                                        <option value="">كل المواد</option>
                                        @foreach($subjects as $subject)
                                            <option value="{{ $subject->id }}" {{ request('subject_id') == $subject->id ? 'selected' : '' }}>
                                                {{ $subject->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <select name="exam_type" class="form-select" style="width: 150px;">
                                        <option value="">كل الأنواع</option>
                                        @foreach($examTypes as $key => $name)
                                            <option value="{{ $key }}" {{ request('exam_type') == $key ? 'selected' : '' }}>
                                                {{ $name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <select name="academic_year" class="form-select" style="width: 150px;">
                                        <option value="">كل السنوات</option>
                                        @foreach($academicYears as $year)
                                            <option value="{{ $year }}" {{ request('academic_year') == $year ? 'selected' : '' }}>
                                                {{ $year }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <button type="submit" class="btn btn-secondary">بحث</button>
                                    <a href="{{ route('admin.grade-records.index') }}" class="btn btn-danger">مسح</a>
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
                                            <th>المادة</th>
                                            <th>نوع التقييم</th>
                                            <th>اسم التقييم</th>
                                            <th>الدرجة</th>
                                            <th>النسبة</th>
                                            <th>الدرجة الحرفية</th>
                                            <th>التاريخ</th>
                                            <th>السنة الدراسية</th>
                                            <th>الحالة</th>
                                            <th>العمليات</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($gradeRecords as $record)
                                            <tr>
                                                <td>{{ $record->id }}</td>
                                                <td>
                                                    <strong>{{ $record->student->user->name ?? 'غير محدد' }}</strong>
                                                    <br>
                                                    <small class="text-muted">{{ $record->student->student_code }}</small>
                                                </td>
                                                <td>{{ $record->subject->name }}</td>
                                                <td>
                                                    <span class="badge bg-info">{{ $record->exam_type_name }}</span>
                                                </td>
                                                <td>{{ $record->exam_name }}</td>
                                                <td>
                                                    <strong>{{ $record->marks_obtained }}</strong> / {{ $record->total_marks }}
                                                </td>
                                                <td>
                                                    <span class="badge bg-{{ $record->percentage >= 50 ? 'success' : 'danger' }}">
                                                        {{ $record->percentage }}%
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="badge bg-{{ $record->grade == 'F' ? 'danger' : ($record->percentage >= 80 ? 'success' : 'warning') }}">
                                                        {{ $record->grade }}
                                                    </span>
                                                </td>
                                                <td>{{ $record->exam_date->format('Y-m-d') }}</td>
                                                <td>{{ $record->academic_year }}</td>
                                                <td>
                                                    @if($record->is_published)
                                                        <span class="badge bg-success">منشور</span>
                                                    @else
                                                        <span class="badge bg-warning">مسودة</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="d-flex gap-2">
                                                        @can('grade-edit')
                                                        <a href="{{ route('admin.grade-records.edit', $record->id) }}" class="btn btn-sm btn-warning" title="تعديل">
                                                            <i class="fa-solid fa-edit"></i>
                                                        </a>
                                                        @endcan
                                                        @can('grade-delete')
                                                        <button type="button" class="btn btn-sm btn-danger" 
                                                                data-delete-url="{{ route('admin.grade-records.destroy', $record->id) }}"
                                                                data-delete-message="هل أنت متأكد من رغبتك في حذف الدرجة للطالب <strong>{{ $record->student->user->name }}</strong> في <strong>{{ $record->subject->name }}</strong>؟"
                                                                title="حذف">
                                                            <i class="fa-solid fa-trash-can"></i>
                                                        </button>
                                                        @endcan
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="12" class="text-center">لا توجد درجات مسجلة</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-3">
                                {{ $gradeRecords->links() }}
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

