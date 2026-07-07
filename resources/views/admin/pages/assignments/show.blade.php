@extends('admin.layouts.master')

@section('page-title')
    تفاصيل الواجب
@stop

@section('content')
    @php
        $statusClasses = [
            'published' => 'admin-badge-success',
            'closed' => 'admin-badge-danger',
            'draft' => 'admin-badge-muted',
        ];
        $submissionTypes = is_string($assignment->submission_types)
            ? json_decode($assignment->submission_types, true)
            : ($assignment->submission_types ?? []);
        $typeLabels = ['file' => 'ملفات', 'text' => 'نصوص', 'link' => 'روابط'];
    @endphp

    <div class="main-content app-content">
        <div class="container-fluid">

            <div class="admin-page-header">
                <div class="page-title-wrap">
                    <h1>تفاصيل الواجب</h1>
                    <p>{{ $assignment->assignment_number }} — {{ $assignment->title }}</p>
                </div>
                <div class="admin-page-header-actions">
                    @can('assignment-edit')
                        <a href="{{ route('admin.assignments.edit', $assignment->id) }}" class="admin-btn admin-btn-primary">
                            <i class="ri-edit-line"></i>
                            تعديل
                        </a>
                    @endcan
                    <a href="{{ route('admin.assignments.submissions.index', $assignment->id) }}" class="admin-btn admin-btn-secondary">
                        <i class="ri-upload-2-line"></i>
                        التسليمات ({{ $stats['total_submissions'] }})
                    </a>
                    <a href="{{ route('admin.assignments.index') }}" class="admin-btn admin-btn-secondary">
                        <i class="ri-arrow-right-line"></i>
                        العودة
                    </a>
                </div>
            </div>

            <div class="row g-3">
                <div class="col-xl-8">
                    <div class="admin-page-card admin-detail-card">
                        <div class="admin-detail-card-head">
                            <div class="section-icon-sm admin-section-icon-blue">
                                <i class="ri-file-text-line"></i>
                            </div>
                            <h3>{{ $assignment->title }}</h3>
                            <span class="admin-badge {{ $statusClasses[$assignment->status] ?? 'admin-badge-muted' }} ms-auto">
                                {{ $assignment->status_name }}
                            </span>
                        </div>
                        <div class="admin-detail-card-body">
                            <div class="admin-detail-grid">
                                <div class="admin-detail-item">
                                    <span class="detail-label">رقم الواجب</span>
                                    <span class="detail-value">{{ $assignment->assignment_number }}</span>
                                </div>
                                <div class="admin-detail-item">
                                    <span class="detail-label">المادة</span>
                                    <span class="detail-value">{{ $assignment->subject->name ?? 'غير محدد' }}</span>
                                </div>
                                <div class="admin-detail-item">
                                    <span class="detail-label">المعلم</span>
                                    <span class="detail-value">{{ $assignment->teacher->user->name ?? 'غير محدد' }}</span>
                                </div>
                                <div class="admin-detail-item">
                                    <span class="detail-label">الفصل</span>
                                    <span class="detail-value">{{ $assignment->section->name ?? 'كل الفصول' }}</span>
                                </div>
                                <div class="admin-detail-item">
                                    <span class="detail-label">الدرجة الكلية</span>
                                    <span class="detail-value text-success">{{ number_format($assignment->total_marks, 2) }}</span>
                                </div>
                                <div class="admin-detail-item">
                                    <span class="detail-label">تاريخ الاستحقاق</span>
                                    <span class="detail-value">
                                        {{ $assignment->due_date->format('Y-m-d') }}
                                        @if ($assignment->isOverdue())
                                            <span class="admin-badge admin-badge-danger">متأخر</span>
                                        @endif
                                    </span>
                                </div>
                                <div class="admin-detail-item">
                                    <span class="detail-label">وقت الاستحقاق</span>
                                    <span class="detail-value">{{ $assignment->due_time }}</span>
                                </div>
                                <div class="admin-detail-item">
                                    <span class="detail-label">النشاط</span>
                                    <span class="detail-value">
                                        @if ($assignment->is_active)
                                            <span class="admin-badge admin-badge-success">نشط</span>
                                        @else
                                            <span class="admin-badge admin-badge-danger">غير نشط</span>
                                        @endif
                                    </span>
                                </div>
                                <div class="admin-detail-item">
                                    <span class="detail-label">السماح بالتأخير</span>
                                    <span class="detail-value">{{ $assignment->allow_late_submission ? 'نعم' : 'لا' }}</span>
                                </div>
                                <div class="admin-detail-item">
                                    <span class="detail-label">عدد المحاولات</span>
                                    <span class="detail-value">{{ $assignment->max_attempts ?? 'غير محدود' }}</span>
                                </div>
                            </div>

                            @if ($assignment->allow_late_submission && $assignment->late_penalty_per_day)
                                <p class="admin-detail-note">
                                    غرامة التأخير: {{ number_format($assignment->late_penalty_per_day, 2) }} لكل يوم
                                </p>
                            @endif

                            @if ($assignment->description)
                                <div class="admin-detail-block">
                                    <span class="detail-label">الوصف</span>
                                    <p class="admin-detail-text">{{ $assignment->description }}</p>
                                </div>
                            @endif

                            @if ($assignment->instructions)
                                <div class="admin-detail-block">
                                    <span class="detail-label">التعليمات</span>
                                    <p class="admin-detail-text">{{ $assignment->instructions }}</p>
                                </div>
                            @endif

                            @if (! empty($submissionTypes))
                                <div class="admin-detail-block">
                                    <span class="detail-label">أنواع التسليم المسموحة</span>
                                    <div class="admin-detail-tags">
                                        @foreach ($submissionTypes as $type)
                                            <span class="admin-badge admin-badge-role">{{ $typeLabels[$type] ?? $type }}</span>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    @if ($assignment->attachments->count() > 0)
                        <div class="admin-page-card admin-detail-card mt-3">
                            <div class="admin-detail-card-head">
                                <div class="section-icon-sm admin-section-icon-teal">
                                    <i class="ri-attachment-2"></i>
                                </div>
                                <h3>المرفقات</h3>
                            </div>
                            <div class="admin-detail-card-body">
                                @foreach ($assignment->attachments as $attachment)
                                    <a href="{{ asset('storage/' . $attachment->file_path) }}" target="_blank" class="admin-file-btn">
                                        <i class="ri-file-line"></i>
                                        {{ $attachment->file_name }}
                                        <small class="text-muted">({{ $attachment->formatted_file_size }})</small>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <div class="col-xl-4">
                    <div class="admin-page-card admin-detail-card">
                        <div class="admin-detail-card-head">
                            <div class="section-icon-sm admin-section-icon-purple">
                                <i class="ri-bar-chart-box-line"></i>
                            </div>
                            <h3>الإحصائيات</h3>
                        </div>
                        <div class="admin-detail-card-body">
                            <div class="admin-stat-grid">
                                <div class="admin-stat-item">
                                    <span class="admin-stat-label">إجمالي التسليمات</span>
                                    <span class="admin-stat-value admin-stat-info">{{ $stats['total_submissions'] }}</span>
                                </div>
                                <div class="admin-stat-item">
                                    <span class="admin-stat-label">المصححة</span>
                                    <span class="admin-stat-value admin-stat-success">{{ $stats['graded_submissions'] }}</span>
                                </div>
                                <div class="admin-stat-item">
                                    <span class="admin-stat-label">المعلقة</span>
                                    <span class="admin-stat-value admin-stat-warning">{{ $stats['pending_submissions'] }}</span>
                                </div>
                                <div class="admin-stat-item">
                                    <span class="admin-stat-label">متوسط الدرجات</span>
                                    <span class="admin-stat-value admin-stat-primary">{{ number_format($stats['average_marks'], 2) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@stop
