@extends('admin.layouts.master')

@section('page-title')
    تفاصيل المعلم
@stop

@section('content')
    <!-- Start::app-content -->
    <div class="main-content app-content">
        <div class="container-fluid">
            <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
                <div class="my-auto">
                    <h5 class="page-title fs-21 mb-1">تفاصيل المعلم</h5>
                </div>
                <div>
                    <a href="{{ route('admin.teachers.edit', $teacher->id) }}" class="btn btn-primary btn-sm">تعديل</a>
                    <a href="{{ route('admin.teachers.index') }}" class="btn btn-secondary btn-sm">العودة</a>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-4">
                    <div class="card">
                        <div class="card-body text-center">
                            @if($teacher->photo)
                                <img src="{{ asset('storage/' . $teacher->photo) }}" alt="{{ $teacher->user->name }}" class="img-fluid rounded-circle mb-3" style="width: 150px; height: 150px; object-fit: cover;">
                            @else
                                <div class="avatar avatar-xl rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mb-3" style="width: 150px; height: 150px; margin: 0 auto;">
                                    <span style="font-size: 48px;">{{ substr($teacher->user->name, 0, 1) }}</span>
                                </div>
                            @endif
                            <h4 class="mb-1">{{ $teacher->user->name }}</h4>
                            <p class="text-muted mb-0">{{ $teacher->teacher_code }}</p>
                            <p class="text-muted mb-3">{{ $teacher->user->email }}</p>
                            @if($teacher->status == 'active')
                                <span class="badge bg-success">نشط</span>
                            @elseif($teacher->status == 'inactive')
                                <span class="badge bg-danger">غير نشط</span>
                            @elseif($teacher->status == 'on_leave')
                                <span class="badge bg-warning">في إجازة</span>
                            @else
                                <span class="badge bg-secondary">استقال</span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-xl-8">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">المعلومات الشخصية</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>رقم الهاتف:</strong> {{ $teacher->user->phone ?? '-' }}</p>
                                    <p><strong>تاريخ الميلاد:</strong> {{ $teacher->date_of_birth ? $teacher->date_of_birth->format('Y-m-d') : '-' }}</p>
                                    <p><strong>الجنس:</strong> {{ $teacher->gender == 'male' ? 'ذكر' : ($teacher->gender == 'female' ? 'أنثى' : '-') }}</p>
                                    <p><strong>العنوان:</strong> {{ $teacher->address ?? '-' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>تاريخ التعيين:</strong> {{ $teacher->hire_date ? $teacher->hire_date->format('Y-m-d') : '-' }}</p>
                                    <p><strong>المؤهل العلمي:</strong> {{ $teacher->qualification ?? '-' }}</p>
                                    <p><strong>التخصص:</strong> {{ $teacher->specialization ?? '-' }}</p>
                                    <p><strong>سنوات الخبرة:</strong> {{ $teacher->experience_years ?? '-' }}</p>
                                    <p><strong>الراتب:</strong> {{ $teacher->salary ? number_format($teacher->salary, 2) . ' ر.س' : '-' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card mt-3">
                        <div class="card-header">
                            <h4 class="card-title">المواد التي يدرسها</h4>
                        </div>
                        <div class="card-body">
                            @if($teacher->subjects->count() > 0)
                                <div class="d-flex flex-wrap gap-2">
                                    @foreach($teacher->subjects as $subject)
                                        <span class="badge bg-primary">{{ $subject->name }}</span>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-muted">لا توجد مواد مرتبطة</p>
                            @endif
                        </div>
                    </div>

                    @if($teacher->sections->count() > 0)
                        <div class="card mt-3">
                            <div class="card-header">
                                <h4 class="card-title">الفصول المكلف بها (معلم رئيسي)</h4>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>اسم الفصل</th>
                                                <th>الصف</th>
                                                <th>المرحلة</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($teacher->sections as $section)
                                                <tr>
                                                    <td>{{ $section->name }}</td>
                                                    <td>{{ $section->class->name }}</td>
                                                    <td>{{ $section->class->grade->name }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if($teacher->notes)
                        <div class="card mt-3">
                            <div class="card-header">
                                <h4 class="card-title">ملاحظات</h4>
                            </div>
                            <div class="card-body">
                                <p>{{ $teacher->notes }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@stop

