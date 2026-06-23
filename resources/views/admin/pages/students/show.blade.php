@extends('admin.layouts.master')

@section('page-title')
    تفاصيل الطالب
@stop

@section('content')
    <div class="main-content app-content">
        <div class="container-fluid">

            <div class="admin-page-header">
                <div class="page-title-wrap">
                    <h1>تفاصيل الطالب</h1>
                    <p>{{ $student->user->name }} — {{ $student->student_code }}</p>
                </div>
                <div class="admin-page-header-actions">
                    <a href="{{ route('admin.students.edit', $student->id) }}" class="admin-btn admin-btn-primary">
                        <i class="ri-edit-line"></i>
                        تعديل
                    </a>
                    <a href="{{ route('admin.students.index') }}" class="admin-btn admin-btn-secondary">
                        <i class="ri-arrow-right-line"></i>
                        العودة
                    </a>
                </div>
            </div>

            <div class="row g-3">
                {{-- بطاقة الملف الشخصي --}}
                <div class="col-xl-4">
                    <div class="admin-page-card admin-profile-card">
                        <div class="admin-profile-body">
                            @if ($student->photo)
                                <img src="{{ asset('storage/' . $student->photo) }}" alt="{{ $student->user->name }}"
                                     class="admin-profile-avatar">
                            @else
                                <div class="admin-profile-avatar-initial">
                                    {{ mb_substr($student->user->name, 0, 1) }}
                                </div>
                            @endif

                            <h2 class="admin-profile-name">{{ $student->user->name }}</h2>
                            <p class="admin-profile-code">{{ $student->student_code }}</p>

                            @if ($student->user->email)
                                <div class="admin-profile-email">
                                    <a href="mailto:{{ $student->user->email }}">{{ $student->user->email }}</a>
                                    <button type="button" class="admin-copy-btn" data-copy-email="{{ $student->user->email }}" title="نسخ البريد">
                                        <i class="ri-file-copy-line"></i>
                                    </button>
                                </div>
                            @endif

                            @if ($student->status === 'active')
                                <span class="admin-badge admin-badge-success">نشط</span>
                            @elseif ($student->status === 'graduated')
                                <span class="admin-badge admin-badge-role">متخرج</span>
                            @elseif ($student->status === 'transferred')
                                <span class="admin-badge admin-badge-warning">منقول</span>
                            @else
                                <span class="admin-badge admin-badge-danger">معلق</span>
                            @endif
                        </div>

                        @if ($student->birth_certificate || $student->health_certificate)
                            <div class="card-section-title">
                                <i class="ri-folder-line me-1"></i> الملفات
                            </div>
                            <div class="card-section-body">
                                @if ($student->birth_certificate)
                                    <a href="{{ asset('storage/' . $student->birth_certificate) }}" target="_blank" class="admin-file-btn">
                                        <i class="ri-file-text-line"></i>
                                        شهادة الميلاد
                                    </a>
                                @endif
                                @if ($student->health_certificate)
                                    <a href="{{ asset('storage/' . $student->health_certificate) }}" target="_blank" class="admin-file-btn">
                                        <i class="ri-heart-pulse-line"></i>
                                        الشهادة الصحية
                                    </a>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>

                {{-- التفاصيل --}}
                <div class="col-xl-8">
                    {{-- المعلومات الشخصية --}}
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
                                    <span class="detail-value">{{ $student->user->phone ?? '—' }}</span>
                                </div>
                                <div class="admin-detail-item">
                                    <span class="detail-label">تاريخ الميلاد</span>
                                    <span class="detail-value">{{ $student->date_of_birth ? $student->date_of_birth->format('Y-m-d') : '—' }}</span>
                                </div>
                                <div class="admin-detail-item">
                                    <span class="detail-label">الجنس</span>
                                    <span class="detail-value">{{ $student->gender === 'male' ? 'ذكر' : ($student->gender === 'female' ? 'أنثى' : '—') }}</span>
                                </div>
                                <div class="admin-detail-item">
                                    <span class="detail-label">العنوان</span>
                                    <span class="detail-value">{{ $student->address ?? '—' }}</span>
                                </div>
                                <div class="admin-detail-item">
                                    <span class="detail-label">تاريخ التسجيل</span>
                                    <span class="detail-value">{{ $student->enrollment_date ? $student->enrollment_date->format('Y-m-d') : '—' }}</span>
                                </div>
                                <div class="admin-detail-item">
                                    <span class="detail-label">ولي الأمر الأساسي</span>
                                    <span class="detail-value">{{ $student->parent_guardian ?? '—' }}</span>
                                </div>
                                <div class="admin-detail-item">
                                    <span class="detail-label">جهة الاتصال في الطوارئ</span>
                                    <span class="detail-value">{{ $student->emergency_contact ?? '—' }}</span>
                                </div>
                            </div>
                            @if ($student->medical_notes)
                                <div class="mt-3 pt-3 border-top">
                                    <span class="detail-label d-block mb-1">ملاحظات طبية</span>
                                    <p class="detail-value mb-0" style="font-weight: 500; line-height: 1.6;">{{ $student->medical_notes }}</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- البيانات الأكاديمية --}}
                    <div class="admin-page-card admin-detail-card">
                        <div class="admin-detail-card-head">
                            <div class="section-icon-sm admin-section-icon-green">
                                <i class="ri-graduation-cap-line"></i>
                            </div>
                            <h3>البيانات الأكاديمية</h3>
                        </div>
                        <div class="admin-detail-card-body">
                            <div class="admin-detail-grid">
                                <div class="admin-detail-item">
                                    <span class="detail-label">المرحلة</span>
                                    <span class="detail-value">{{ $student->class->grade->name ?? '—' }}</span>
                                </div>
                                <div class="admin-detail-item">
                                    <span class="detail-label">الصف</span>
                                    <span class="detail-value">{{ $student->class->name ?? '—' }}</span>
                                </div>
                                <div class="admin-detail-item">
                                    <span class="detail-label">الفصل</span>
                                    <span class="detail-value">{{ $student->section->name ?? '—' }}</span>
                                </div>
                                <div class="admin-detail-item">
                                    <span class="detail-label">الحالة</span>
                                    <span class="detail-value">
                                        @if ($student->status === 'active')
                                            <span class="admin-badge admin-badge-success">نشط</span>
                                        @elseif ($student->status === 'graduated')
                                            <span class="admin-badge admin-badge-role">متخرج</span>
                                        @elseif ($student->status === 'transferred')
                                            <span class="admin-badge admin-badge-warning">منقول</span>
                                        @else
                                            <span class="admin-badge admin-badge-danger">معلق</span>
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- أولياء الأمور --}}
                    @if ($student->parents->count() > 0)
                        <div class="admin-page-card admin-detail-card">
                            <div class="admin-detail-card-head">
                                <div class="section-icon-sm admin-section-icon-purple">
                                    <i class="ri-parent-line"></i>
                                </div>
                                <h3>أولياء الأمور</h3>
                            </div>
                            <div class="admin-table-wrap">
                                <div class="table-responsive">
                                    <table class="admin-data-table">
                                        <thead>
                                            <tr>
                                                <th>الاسم</th>
                                                <th>البريد الإلكتروني</th>
                                                <th>الهاتف</th>
                                                <th>العلاقة</th>
                                                <th>النوع</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($student->parents as $parent)
                                                <tr>
                                                    <td><strong>{{ $parent->user->name }}</strong></td>
                                                    <td>
                                                        @if ($parent->user->email)
                                                            <div class="admin-email-cell">
                                                                <a href="mailto:{{ $parent->user->email }}" class="admin-email-link">{{ $parent->user->email }}</a>
                                                                <button type="button" class="admin-copy-btn" data-copy-email="{{ $parent->user->email }}">
                                                                    <i class="ri-file-copy-line"></i>
                                                                </button>
                                                            </div>
                                                        @else
                                                            —
                                                        @endif
                                                    </td>
                                                    <td>{{ $parent->user->phone ?? '—' }}</td>
                                                    <td>
                                                        @if ($parent->pivot->relationship_type === 'father')
                                                            أب
                                                        @elseif ($parent->pivot->relationship_type === 'mother')
                                                            أم
                                                        @else
                                                            ولي أمر
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if ($parent->pivot->is_primary)
                                                            <span class="admin-badge admin-badge-role">أساسي</span>
                                                        @else
                                                            <span class="admin-badge admin-badge-muted">ثانوي</span>
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

                    {{-- سجل الحضور --}}
                    @if ($student->attendances->count() > 0)
                        <div class="admin-page-card admin-detail-card">
                            <div class="admin-detail-card-head">
                                <div class="section-icon-sm admin-section-icon-amber">
                                    <i class="ri-calendar-check-line"></i>
                                </div>
                                <h3>سجل الحضور الأخير</h3>
                            </div>
                            <div class="admin-table-wrap">
                                <div class="table-responsive">
                                    <table class="admin-data-table">
                                        <thead>
                                            <tr>
                                                <th>التاريخ</th>
                                                <th>الحالة</th>
                                                <th>وقت الحضور</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($student->attendances->take(10) as $attendance)
                                                <tr>
                                                    <td>{{ $attendance->date->format('Y-m-d') }}</td>
                                                    <td>
                                                        @php
                                                            $badgeClass = match($attendance->status) {
                                                                'present' => 'admin-badge-success',
                                                                'absent' => 'admin-badge-danger',
                                                                'late' => 'admin-badge-warning',
                                                                'excused' => 'admin-badge-role',
                                                                default => 'admin-badge-muted',
                                                            };
                                                        @endphp
                                                        <span class="admin-badge {{ $badgeClass }}">{{ $attendance->status_label }}</span>
                                                    </td>
                                                    <td>{{ $attendance->check_in_time ? \Carbon\Carbon::parse($attendance->check_in_time)->format('H:i') : '—' }}</td>
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

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (window.AdminTables) AdminTables.initCopyButtons(document);
    });
</script>
@endpush
