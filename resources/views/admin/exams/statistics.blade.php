@extends('admin.layouts.master')

@section('title', 'إحصائيات الاختبار: ' . $exam->title)

@section('content')
<div class="page-wrapper">
    <div class="content-wrapper">
        <!-- Page Header -->
        <div class="page-header">
            <div class="page-title">
                <h1>إحصائيات الاختبار: {{ $exam->title }}</h1>
                <a href="{{ route('admin.exams.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i>
                    رجوع
                </a>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="statistics-grid">
            <!-- Total Students -->
            <div class="stat-card stat-primary">
                <div class="stat-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value">{{ $totalStudents }}</div>
                    <div class="stat-label">إجمالي عدد الطلاب</div>
                </div>
            </div>

            <!-- Completed Students -->
            <div class="stat-card stat-success">
                <div class="stat-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value">{{ $completedStudents }}</div>
                    <div class="stat-label">أدوا الاختبار</div>
                </div>
            </div>

            <!-- Passed Students -->
            <div class="stat-card stat-success">
                <div class="stat-icon">
                    <i class="fas fa-thumbs-up"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value">{{ $passedStudents }}</div>
                    <div class="stat-label">ناجحون</div>
                </div>
            </div>

            <!-- Failed Students -->
            <div class="stat-card stat-danger">
                <div class="stat-icon">
                    <i class="fas fa-thumbs-down"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value">{{ $failedStudents }}</div>
                    <div class="stat-label">راسبون</div>
                </div>
            </div>

            <!-- Absent Students -->
            <div class="stat-card stat-warning">
                <div class="stat-icon">
                    <i class="fas fa-user-clock"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value">{{ $absentStudents }}</div>
                    <div class="stat-label">غائبون</div>
                </div>
            </div>

            <!-- Average Score -->
            <div class="stat-card stat-info">
                <div class="stat-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value">{{ number_format($averageScore, 2) }}%</div>
                    <div class="stat-label">متوسط الدرجات</div>
                </div>
            </div>

            <!-- Highest Score -->
            <div class="stat-card stat-success">
                <div class="stat-icon">
                    <i class="fas fa-trophy"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value">{{ number_format($highestScore, 2) }}%</div>
                    <div class="stat-label">أعلى درجة</div>
                </div>
            </div>

            <!-- Lowest Score -->
            <div class="stat-card stat-danger">
                <div class="stat-icon">
                    <i class="fas fa-arrow-down"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value">{{ number_format($lowestScore, 2) }}%</div>
                    <div class="stat-label">أدنى درجة</div>
                </div>
            </div>

            <!-- Average Time -->
            <div class="stat-card stat-info">
                <div class="stat-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value">{{ gmdate('H:i:s', $averageTime) }}</div>
                    <div class="stat-label">متوسط الوقت</div>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="actions">
            <a href="{{ route('admin.exams.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i>
                رجوع للقائمة
            </a>
            <a href="{{ route('admin.exam-results.index') }}" class="btn btn-primary">
                <i class="fas fa-list"></i>
                عرض النتائج التفصيلية
            </a>
            <a href="{{ route('admin.exams.edit', $exam) }}" class="btn btn-info">
                <i class="fas fa-edit"></i>
                تعديل الاختبار
            </a>
        </div>
    </div>
</div>
@endsection
