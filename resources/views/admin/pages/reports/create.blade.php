@extends('admin.layouts.master')

@section('page-title')
    إنشاء تقرير جديد
@stop

@section('content')
    <!-- Start::app-content -->
    <div class="main-content app-content">
        <div class="container-fluid">
            <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
                <div class="my-auto">
                    <h5 class="page-title fs-21 mb-1">إنشاء تقرير جديد</h5>
                </div>
                <div>
                    <a href="{{ route('admin.reports.index') }}" class="btn btn-secondary">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-left: 4px; vertical-align: middle;">
                            <line x1="19" y1="12" x2="5" y2="12"></line>
                            <polyline points="12 19 5 12 12 5"></polyline>
                        </svg>
                        رجوع
                    </a>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">اختر نوع التقرير</h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <div class="card border-primary h-100">
                                        <div class="card-body text-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-primary mb-3">
                                                <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                                <circle cx="8.5" cy="7" r="4"></circle>
                                                <line x1="20" y1="8" x2="20" y2="14"></line>
                                                <line x1="23" y1="11" x2="17" y2="11"></line>
                                            </svg>
                                            <h5 class="card-title">أداء الطلاب</h5>
                                            <p class="card-text text-muted">تقرير شامل عن أداء الطلاب ودرجاتهم</p>
                                            <a href="{{ route('admin.reports.student-performance') }}" class="btn btn-primary">إنشاء التقرير</a>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="card border-info h-100">
                                        <div class="card-body text-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-info mb-3">
                                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                                <line x1="16" y1="2" x2="16" y2="6"></line>
                                                <line x1="8" y1="2" x2="8" y2="6"></line>
                                                <line x1="3" y1="10" x2="21" y2="10"></line>
                                            </svg>
                                            <h5 class="card-title">أداء الفصول</h5>
                                            <p class="card-text text-muted">تقرير عن أداء الفصول الدراسية</p>
                                            <a href="{{ route('admin.reports.class-performance') }}" class="btn btn-info">إنشاء التقرير</a>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="card border-success h-100">
                                        <div class="card-body text-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-success mb-3">
                                                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                                <circle cx="9" cy="7" r="4"></circle>
                                                <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                            </svg>
                                            <h5 class="card-title">أداء المعلمين</h5>
                                            <p class="card-text text-muted">تقرير عن أداء المعلمين</p>
                                            <a href="{{ route('admin.reports.teacher-performance') }}" class="btn btn-success">إنشاء التقرير</a>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="card border-warning h-100">
                                        <div class="card-body text-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-warning mb-3">
                                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                                <line x1="16" y1="2" x2="16" y2="6"></line>
                                                <line x1="8" y1="2" x2="8" y2="6"></line>
                                                <line x1="3" y1="10" x2="21" y2="10"></line>
                                                <path d="M8 14h.01M12 14h.01M16 14h.01M8 18h.01M12 18h.01M16 18h.01"></path>
                                            </svg>
                                            <h5 class="card-title">الحضور والغياب</h5>
                                            <p class="card-text text-muted">تقرير شامل عن الحضور والغياب</p>
                                            <a href="{{ route('admin.reports.attendance') }}" class="btn btn-warning">إنشاء التقرير</a>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="card border-danger h-100">
                                        <div class="card-body text-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-danger mb-3">
                                                <line x1="12" y1="1" x2="12" y2="23"></line>
                                                <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                                            </svg>
                                            <h5 class="card-title">التقارير المالية</h5>
                                            <p class="card-text text-muted">تقرير عن الفواتير والمدفوعات</p>
                                            <a href="{{ route('admin.reports.financial') }}" class="btn btn-danger">إنشاء التقرير</a>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="card border-secondary h-100">
                                        <div class="card-body text-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-secondary mb-3">
                                                <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                                                <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                                            </svg>
                                            <h5 class="card-title">المكتبة</h5>
                                            <p class="card-text text-muted">تقرير عن الاستعارات والغرامات</p>
                                            <a href="{{ route('admin.reports.library') }}" class="btn btn-secondary">إنشاء التقرير</a>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="card border-primary h-100">
                                        <div class="card-body text-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-primary mb-3">
                                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                                <polyline points="14 2 14 8 20 8"></polyline>
                                                <line x1="16" y1="13" x2="8" y2="13"></line>
                                                <line x1="16" y1="17" x2="8" y2="17"></line>
                                            </svg>
                                            <h5 class="card-title">الاختبارات</h5>
                                            <p class="card-text text-muted">تقرير عن الاختبارات والنتائج</p>
                                            <a href="{{ route('admin.reports.exams') }}" class="btn btn-primary">إنشاء التقرير</a>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="card border-info h-100">
                                        <div class="card-body text-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-info mb-3">
                                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                                <polyline points="14 2 14 8 20 8"></polyline>
                                                <line x1="16" y1="13" x2="8" y2="13"></line>
                                                <line x1="16" y1="17" x2="8" y2="17"></line>
                                            </svg>
                                            <h5 class="card-title">الواجبات</h5>
                                            <p class="card-text text-muted">تقرير عن الواجبات والتسليمات</p>
                                            <a href="{{ route('admin.reports.assignments') }}" class="btn btn-info">إنشاء التقرير</a>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="card border-success h-100">
                                        <div class="card-body text-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-success mb-3">
                                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm.31-8.86c-1.77-.45-2.34-.94-2.34-1.67 0-.84.79-1.43 2.1-1.43 1.38 0 1.9.66 1.9 1.57h1.6c0-.93-.56-2.26-3.5-2.26-2.34 0-3.7 1.18-3.7 3.04 0 1.8 1.25 2.58 3.15 3.04 1.77.43 2.34.95 2.34 1.67 0 .73-.66 1.33-2.1 1.33-1.6 0-2.1-.59-2.1-1.43H6.04c0 .97.67 2.47 3.96 2.47 2.39 0 3.9-1.18 3.9-3.05 0-1.78-1.27-2.58-3.49-3.04z"></path>
                                            </svg>
                                            <h5 class="card-title">الدرجات</h5>
                                            <p class="card-text text-muted">تقرير شامل عن الدرجات</p>
                                            <a href="{{ route('admin.reports.grades') }}" class="btn btn-success">إنشاء التقرير</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End::app-content -->
@stop

