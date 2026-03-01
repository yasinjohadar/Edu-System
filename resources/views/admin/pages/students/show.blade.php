@extends('admin.layouts.master')

@section('page-title')
    تفاصيل الطالب
@stop

@section('content')
    <!-- Start::app-content -->
    <div class="main-content app-content">
        <div class="container-fluid">
            <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
                <div class="my-auto">
                    <h5 class="page-title fs-21 mb-1">تفاصيل الطالب</h5>
                </div>
                <div>
                    <a href="{{ route('admin.students.edit', $student->id) }}" class="btn btn-primary btn-sm">تعديل</a>
                    <a href="{{ route('admin.students.index') }}" class="btn btn-secondary btn-sm">العودة</a>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-4">
                    <div class="card">
                        <div class="card-body text-center">
                            @if($student->photo)
                                <img src="{{ asset('storage/' . $student->photo) }}" alt="{{ $student->user->name }}" class="img-fluid rounded-circle mb-3" style="width: 150px; height: 150px; object-fit: cover;">
                            @else
                                <div class="avatar avatar-xl rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mb-3" style="width: 150px; height: 150px; margin: 0 auto;">
                                    <span style="font-size: 48px;">{{ substr($student->user->name, 0, 1) }}</span>
                                </div>
                            @endif
                            <h4 class="mb-1">{{ $student->user->name }}</h4>
                            <p class="text-muted mb-0">{{ $student->student_code }}</p>
                            <p class="text-muted mb-3">{{ $student->user->email }}</p>
                            @if($student->status == 'active')
                                <span class="badge bg-success">نشط</span>
                            @elseif($student->status == 'graduated')
                                <span class="badge bg-info">متخرج</span>
                            @elseif($student->status == 'transferred')
                                <span class="badge bg-warning">منقول</span>
                            @else
                                <span class="badge bg-danger">معلق</span>
                            @endif
                        </div>
                    </div>

                    @if($student->birth_certificate || $student->health_certificate)
                        <div class="card mt-3">
                            <div class="card-header">
                                <h5 class="card-title">الملفات</h5>
                            </div>
                            <div class="card-body">
                                @if($student->birth_certificate)
                                    <div class="mb-2">
                                        <a href="{{ asset('storage/' . $student->birth_certificate) }}" target="_blank" class="btn btn-sm btn-info w-100">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-left: 5px; vertical-align: middle;">
                                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                                <polyline points="14 2 14 8 20 8"></polyline>
                                                <line x1="16" y1="13" x2="8" y2="13"></line>
                                                <line x1="16" y1="17" x2="8" y2="17"></line>
                                                <polyline points="10 9 9 9 8 9"></polyline>
                                            </svg>
                                            شهادة الميلاد
                                        </a>
                                    </div>
                                @endif
                                @if($student->health_certificate)
                                    <div>
                                        <a href="{{ asset('storage/' . $student->health_certificate) }}" target="_blank" class="btn btn-sm btn-info w-100">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-left: 5px; vertical-align: middle;">
                                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                                <polyline points="14 2 14 8 20 8"></polyline>
                                                <line x1="16" y1="13" x2="8" y2="13"></line>
                                                <line x1="16" y1="17" x2="8" y2="17"></line>
                                                <polyline points="10 9 9 9 8 9"></polyline>
                                            </svg>
                                            الشهادة الصحية
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
                <div class="col-xl-8">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">المعلومات الشخصية</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>رقم الهاتف:</strong> {{ $student->user->phone ?? '-' }}</p>
                                    <p><strong>تاريخ الميلاد:</strong> {{ $student->date_of_birth ? $student->date_of_birth->format('Y-m-d') : '-' }}</p>
                                    <p><strong>الجنس:</strong> {{ $student->gender == 'male' ? 'ذكر' : ($student->gender == 'female' ? 'أنثى' : '-') }}</p>
                                    <p><strong>العنوان:</strong> {{ $student->address ?? '-' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>تاريخ التسجيل:</strong> {{ $student->enrollment_date ? $student->enrollment_date->format('Y-m-d') : '-' }}</p>
                                    <p><strong>ولي الأمر الأساسي:</strong> {{ $student->parent_guardian ?? '-' }}</p>
                                    <p><strong>جهة الاتصال في الطوارئ:</strong> {{ $student->emergency_contact ?? '-' }}</p>
                                </div>
                            </div>
                            @if($student->medical_notes)
                                <div class="mt-3">
                                    <p><strong>ملاحظات طبية:</strong></p>
                                    <p class="text-muted">{{ $student->medical_notes }}</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="card mt-3">
                        <div class="card-header">
                            <h4 class="card-title">البيانات الأكاديمية</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>المرحلة:</strong> {{ $student->class->grade->name ?? '-' }}</p>
                                    <p><strong>الصف:</strong> {{ $student->class->name ?? '-' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>الفصل:</strong> {{ $student->section->name ?? '-' }}</p>
                                    <p><strong>الحالة:</strong> 
                                        @if($student->status == 'active')
                                            <span class="badge bg-success">نشط</span>
                                        @elseif($student->status == 'graduated')
                                            <span class="badge bg-info">متخرج</span>
                                        @elseif($student->status == 'transferred')
                                            <span class="badge bg-warning">منقول</span>
                                        @else
                                            <span class="badge bg-danger">معلق</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($student->parents->count() > 0)
                        <div class="card mt-3">
                            <div class="card-header">
                                <h4 class="card-title">أولياء الأمور</h4>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>الاسم</th>
                                                <th>البريد الإلكتروني</th>
                                                <th>الهاتف</th>
                                                <th>العلاقة</th>
                                                <th>نوع</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($student->parents as $parent)
                                                <tr>
                                                    <td>{{ $parent->user->name }}</td>
                                                    <td>{{ $parent->user->email }}</td>
                                                    <td>{{ $parent->user->phone ?? '-' }}</td>
                                                    <td>
                                                        @if($parent->pivot->relationship_type == 'father')
                                                            أب
                                                        @elseif($parent->pivot->relationship_type == 'mother')
                                                            أم
                                                        @else
                                                            ولي أمر
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($parent->pivot->is_primary)
                                                            <span class="badge bg-primary">أساسي</span>
                                                        @else
                                                            <span class="badge bg-secondary">ثانوي</span>
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

                    @if($student->attendances->count() > 0)
                        <div class="card mt-3">
                            <div class="card-header">
                                <h4 class="card-title">سجل الحضور الأخير</h4>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>التاريخ</th>
                                                <th>الحالة</th>
                                                <th>وقت الحضور</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($student->attendances->take(10) as $attendance)
                                                <tr>
                                                    <td>{{ $attendance->date->format('Y-m-d') }}</td>
                                                    <td>
                                                        <span class="badge bg-{{ $attendance->status_color }}">
                                                            {{ $attendance->status_label }}
                                                        </span>
                                                    </td>
                                                    <td>{{ $attendance->check_in_time ? \Carbon\Carbon::parse($attendance->check_in_time)->format('H:i') : '-' }}</td>
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
@stop

