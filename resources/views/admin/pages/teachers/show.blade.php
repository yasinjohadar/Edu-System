@extends('admin.layouts.master')

@section('page-title')
    تفاصيل المعلم
@stop

@section('content')
    <div class="main-content app-content">
        <div class="container-fluid">

            <div class="admin-page-header">
                <div class="page-title-wrap">
                    <h1>تفاصيل المعلم</h1>
                    <p>{{ $teacher->user->name }} — {{ $teacher->teacher_code }}</p>
                </div>
                <div class="admin-page-header-actions">
                    <a href="{{ route('admin.teachers.edit', $teacher->id) }}" class="admin-btn admin-btn-primary">
                        <i class="ri-edit-line"></i>
                        تعديل
                    </a>
                    <a href="{{ route('admin.teachers.index') }}" class="admin-btn admin-btn-secondary">
                        <i class="ri-arrow-right-line"></i>
                        العودة
                    </a>
                </div>
            </div>

            <div class="row g-3">
                <div class="col-xl-4">
                    <div class="admin-page-card admin-profile-card">
                        <div class="admin-profile-body">
                            @if ($teacher->photo)
                                <img src="{{ asset('storage/' . $teacher->photo) }}" alt="{{ $teacher->user->name }}"
                                     class="admin-profile-avatar">
                            @else
                                <div class="admin-profile-avatar-initial">
                                    {{ mb_substr($teacher->user->name, 0, 1) }}
                                </div>
                            @endif

                            <h2 class="admin-profile-name">{{ $teacher->user->name }}</h2>
                            <p class="admin-profile-code">{{ $teacher->teacher_code }}</p>

                            @if ($teacher->user->email)
                                <div class="admin-profile-email">
                                    <a href="mailto:{{ $teacher->user->email }}">{{ $teacher->user->email }}</a>
                                    <button type="button" class="admin-copy-btn" data-copy-email="{{ $teacher->user->email }}" title="نسخ البريد">
                                        <i class="ri-file-copy-line"></i>
                                    </button>
                                </div>
                            @endif

                            @if ($teacher->status === 'active')
                                <span class="admin-badge admin-badge-success">نشط</span>
                            @elseif ($teacher->status === 'inactive')
                                <span class="admin-badge admin-badge-danger">غير نشط</span>
                            @elseif ($teacher->status === 'on_leave')
                                <span class="admin-badge admin-badge-warning">في إجازة</span>
                            @else
                                <span class="admin-badge admin-badge-muted">استقال</span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-xl-8">
                    <div class="admin-page-card admin-detail-card">
                        <div class="admin-detail-card-head">
                            <div class="section-icon-sm admin-section-icon-blue">
                                <i class="ri-user-line"></i>
                            </div>
                            <h3>المعلومات الشخصية</h3>
                        </div>
                        <div class="admin-detail-card-body">
                            <div class="admin-detail-grid">
                                <div class="admin-detail-item">
                                    <span class="detail-label">رقم الهاتف</span>
                                    <span class="detail-value">{{ $teacher->user->phone ?? '—' }}</span>
                                </div>
                                <div class="admin-detail-item">
                                    <span class="detail-label">تاريخ الميلاد</span>
                                    <span class="detail-value">{{ $teacher->date_of_birth ? $teacher->date_of_birth->format('Y-m-d') : '—' }}</span>
                                </div>
                                <div class="admin-detail-item">
                                    <span class="detail-label">الجنس</span>
                                    <span class="detail-value">{{ $teacher->gender === 'male' ? 'ذكر' : ($teacher->gender === 'female' ? 'أنثى' : '—') }}</span>
                                </div>
                                <div class="admin-detail-item">
                                    <span class="detail-label">العنوان</span>
                                    <span class="detail-value">{{ $teacher->address ?? '—' }}</span>
                                </div>
                                <div class="admin-detail-item">
                                    <span class="detail-label">تاريخ التعيين</span>
                                    <span class="detail-value">{{ $teacher->hire_date ? $teacher->hire_date->format('Y-m-d') : '—' }}</span>
                                </div>
                                <div class="admin-detail-item">
                                    <span class="detail-label">المؤهل العلمي</span>
                                    <span class="detail-value">{{ $teacher->qualification ?? '—' }}</span>
                                </div>
                                <div class="admin-detail-item">
                                    <span class="detail-label">التخصص</span>
                                    <span class="detail-value">{{ $teacher->specialization ?? '—' }}</span>
                                </div>
                                <div class="admin-detail-item">
                                    <span class="detail-label">سنوات الخبرة</span>
                                    <span class="detail-value">{{ $teacher->experience_years ?? '—' }}</span>
                                </div>
                                <div class="admin-detail-item">
                                    <span class="detail-label">الراتب</span>
                                    <span class="detail-value">{{ $teacher->salary ? number_format($teacher->salary, 2) . ' ر.س' : '—' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="admin-page-card admin-detail-card">
                        <div class="admin-detail-card-head">
                            <div class="section-icon-sm admin-section-icon-green">
                                <i class="ri-book-open-line"></i>
                            </div>
                            <h3>المواد التي يدرسها</h3>
                        </div>
                        <div class="admin-detail-card-body">
                            @if ($teacher->subjects->count() > 0)
                                <div class="admin-role-grid">
                                    @foreach ($teacher->subjects as $subject)
                                        <span class="admin-badge admin-badge-role" style="font-size: 0.82rem; padding: 0.4rem 0.8rem;">
                                            <i class="ri-book-2-line"></i>
                                            {{ $subject->name }}
                                        </span>
                                    @endforeach
                                </div>
                            @else
                                <div class="admin-empty-state py-3">
                                    <i class="ri-book-line"></i>
                                    لا توجد مواد مرتبطة
                                </div>
                            @endif
                        </div>
                    </div>

                    @if ($teacher->sections->count() > 0)
                        <div class="admin-page-card admin-detail-card">
                            <div class="admin-detail-card-head">
                                <div class="section-icon-sm admin-section-icon-purple">
                                    <i class="ri-group-line"></i>
                                </div>
                                <h3>الفصول المكلف بها (معلم رئيسي)</h3>
                            </div>
                            <div class="admin-table-wrap">
                                <div class="table-responsive">
                                    <table class="admin-data-table">
                                        <thead>
                                            <tr>
                                                <th>اسم الفصل</th>
                                                <th>الصف</th>
                                                <th>المرحلة</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($teacher->sections as $section)
                                                <tr>
                                                    <td><strong>{{ $section->name }}</strong></td>
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

                    @if ($teacher->notes)
                        <div class="admin-page-card admin-detail-card">
                            <div class="admin-detail-card-head">
                                <div class="section-icon-sm admin-section-icon-amber">
                                    <i class="ri-sticky-note-line"></i>
                                </div>
                                <h3>ملاحظات</h3>
                            </div>
                            <div class="admin-detail-card-body">
                                <p class="detail-value mb-0" style="font-weight: 500; line-height: 1.6;">{{ $teacher->notes }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
@stop

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (window.AdminTables) AdminTables.initCopyButtons(document);
    });
</script>
@endpush
