@extends('admin.layouts.master')

@section('page-title')
    لوحة التحكم
@stop

@section('content')
    <div class="main-content app-content">
        <div class="container-fluid">

            <div class="dashboard-welcome">
                <h4>مرحباً بك، {{ auth()->user()->name }}!</h4>
                <p class="mb-0 text-muted">نظرة عامة على النظام التعليمي</p>
            </div>

            {{-- إحصائيات رئيسية --}}
            <div class="row g-3 mb-4">
                @foreach ($dashboardWidgets as $index => $widget)
                    <div class="col-xl-3 col-lg-6 col-md-6">
                        <a href="{{ route($widget['route']) }}" class="dashboard-stat-link" style="--card-delay: {{ $index * 0.1 }}s">
                            <div class="dashboard-stat-card dashboard-stat-{{ $widget['theme'] }}">
                                <div class="stat-card-shine"></div>
                                <div class="stat-card-mesh"></div>
                                <div class="stat-card-bubble stat-card-bubble-1"></div>
                                <div class="stat-card-bubble stat-card-bubble-2"></div>
                                <div class="stat-card-bubble stat-card-bubble-3"></div>
                                <div class="stat-card-glow"></div>
                                <div class="stat-card-body">
                                    <div class="stat-card-content">
                                        <span class="stat-label">{{ $widget['title'] }}</span>
                                        <span class="stat-value" data-count="{{ $widget['value'] }}">0</span>
                                        <span class="stat-subtext">{{ $widget['subtext'] }}</span>
                                    </div>
                                    <div class="stat-icon-wrap">
                                        <span class="stat-icon-ring"></span>
                                        <span class="stat-icon-circle">
                                            <i class="{{ $widget['icon'] }}"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>

            {{-- اختصارات سريعة --}}
            <div class="shortcuts-section mb-4">
                <div class="shortcuts-section-header">
                    <span class="shortcuts-section-icon"><i class="ri-flashlight-line"></i></span>
                    <h5 class="dashboard-section-title mb-0">اختصارات سريعة</h5>
                </div>
                <div class="row g-3 shortcuts-grid">
                    @foreach ($quickShortcuts as $index => $shortcut)
                        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 col-6">
                            <a href="{{ route($shortcut['route']) }}"
                               class="shortcut-card shortcut-theme-{{ $shortcut['color'] }}"
                               style="--shortcut-delay: {{ $index * 0.05 }}s">
                                <span class="shortcut-shine"></span>
                                <span class="shortcut-accent"></span>
                                <span class="shortcut-icon-wrap">
                                    <span class="shortcut-icon-ring"></span>
                                    <span class="shortcut-icon">
                                        <i class="{{ $shortcut['icon'] }}"></i>
                                    </span>
                                </span>
                                <span class="shortcut-title">{{ $shortcut['title'] }}</span>
                                <span class="shortcut-desc">{{ $shortcut['desc'] }}</span>
                                <span class="shortcut-arrow"><i class="ri-arrow-left-s-line"></i></span>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- الرسوم البيانية وملخص اليوم --}}
            <div class="row g-3 mb-4">
                <div class="col-xl-8 col-lg-7">
                    <div class="card custom-card h-100">
                        <div class="card-header">
                            <h6 class="card-title mb-0">تطور الالتحاقات خلال آخر 6 أشهر</h6>
                        </div>
                        <div class="card-body">
                            <div id="enrollment-chart"></div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-5">
                    <div class="card custom-card h-100">
                        <div class="card-header">
                            <h6 class="card-title mb-0">ملخص اليوم</h6>
                        </div>
                        <div class="card-body pt-2">
                            @foreach ($todaySummary as $item)
                                <div class="today-summary-item">
                                    <div class="d-flex align-items-center">
                                        <span class="summary-icon menu-icon-box menu-icon-{{ $item['color'] }} menu-icon-sm">
                                            <i class="{{ $item['icon'] }}"></i>
                                        </span>
                                        <span class="summary-label">{{ $item['label'] }}</span>
                                    </div>
                                    <span class="summary-value">{{ $item['value'] }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            {{-- الجداول --}}
            <div class="row g-3">
                <div class="col-xl-6 col-lg-12">
                    <div class="dashboard-table-card" style="--table-delay: 0.1s">
                        <div class="dashboard-table-header">
                            <div class="dashboard-table-header-main">
                                <span class="dashboard-table-header-icon icon-students">
                                    <i class="ri-graduation-cap-line"></i>
                                </span>
                                <div>
                                    <h3>آخر الطلاب المسجلين</h3>
                                    <p>أحدث {{ $recentStudents->count() }} طالب مسجل في النظام</p>
                                </div>
                            </div>
                        </div>
                        <div class="admin-table-wrap">
                            <div class="table-responsive">
                                <table class="admin-data-table mb-0">
                                    <thead>
                                        <tr>
                                            <th>الاسم</th>
                                            <th>الصف</th>
                                            <th>الفصل</th>
                                            <th>تاريخ التسجيل</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($recentStudents as $student)
                                            <tr style="--row-index: {{ $loop->index }}">
                                                <td>
                                                    <div class="dashboard-student-cell">
                                                        <span class="dashboard-row-avatar">
                                                            {{ mb_substr($student->user->name ?? '?', 0, 1) }}
                                                        </span>
                                                        <div>
                                                            <h6>{{ $student->user->name ?? 'غير محدد' }}</h6>
                                                            <p class="student-code">{{ $student->student_code }}</p>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>{{ $student->class->name ?? 'غير محدد' }}</td>
                                                <td>{{ $student->section->name ?? 'غير محدد' }}</td>
                                                <td>{{ $student->enrollment_date ? $student->enrollment_date->format('Y-m-d') : 'غير محدد' }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="dashboard-table-empty">
                                                    <i class="ri-user-search-line"></i>
                                                    لا توجد بيانات
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @if($recentStudents->count() > 0)
                            <div class="dashboard-table-footer">
                                <a href="{{ route('admin.students.index') }}" class="dashboard-view-all">
                                    عرض جميع الطلاب
                                    <i class="ri-arrow-left-s-line"></i>
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="col-xl-6 col-lg-12">
                    <div class="dashboard-table-card" style="--table-delay: 0.2s">
                        <div class="dashboard-table-header">
                            <div class="dashboard-table-header-main">
                                <span class="dashboard-table-header-icon icon-invoices">
                                    <i class="ri-bill-line"></i>
                                </span>
                                <div>
                                    <h3>آخر الفواتير</h3>
                                    <p>أحدث {{ $recentInvoices->count() }} فاتورة في النظام</p>
                                </div>
                            </div>
                        </div>
                        <div class="admin-table-wrap">
                            <div class="table-responsive">
                                <table class="admin-data-table mb-0">
                                    <thead>
                                        <tr>
                                            <th>رقم الفاتورة</th>
                                            <th>الطالب</th>
                                            <th>المبلغ</th>
                                            <th>الحالة</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($recentInvoices as $invoice)
                                            <tr style="--row-index: {{ $loop->index }}">
                                                <td>
                                                    <a href="{{ route('admin.invoices.show', $invoice->id) }}" class="dashboard-cell-link">
                                                        {{ $invoice->invoice_number }}
                                                    </a>
                                                </td>
                                                <td>{{ $invoice->student->user->name ?? 'غير محدد' }}</td>
                                                <td class="dashboard-amount">{{ number_format($invoice->total_amount, 2) }} ر.س</td>
                                                <td>
                                                    <span class="admin-badge
                                                        @if($invoice->status == 'paid') admin-badge-success
                                                        @elseif($invoice->status == 'overdue') admin-badge-danger
                                                        @elseif($invoice->status == 'partial') admin-badge-warning
                                                        @else admin-badge-muted
                                                        @endif">
                                                        {{ $invoice->status_name }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="dashboard-table-empty">
                                                    <i class="ri-file-list-3-line"></i>
                                                    لا توجد بيانات
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @if($recentInvoices->count() > 0)
                            <div class="dashboard-table-footer">
                                <a href="{{ route('admin.invoices.index') }}" class="dashboard-view-all">
                                    عرض جميع الفواتير
                                    <i class="ri-arrow-left-s-line"></i>
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-xl-12">
                    <div class="dashboard-table-card" style="--table-delay: 0.3s">
                        <div class="dashboard-table-header">
                            <div class="dashboard-table-header-main">
                                <span class="dashboard-table-header-icon icon-payments">
                                    <i class="ri-money-dollar-circle-line"></i>
                                </span>
                                <div>
                                    <h3>آخر المدفوعات</h3>
                                    <p>أحدث {{ $recentPayments->count() }} دفعة في النظام</p>
                                </div>
                            </div>
                        </div>
                        <div class="admin-table-wrap">
                            <div class="table-responsive">
                                <table class="admin-data-table mb-0">
                                    <thead>
                                        <tr>
                                            <th>رقم الدفعة</th>
                                            <th>الطالب</th>
                                            <th>الفاتورة</th>
                                            <th>المبلغ</th>
                                            <th>طريقة الدفع</th>
                                            <th>التاريخ</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($recentPayments as $payment)
                                            <tr style="--row-index: {{ $loop->index }}">
                                                <td>
                                                    <a href="{{ route('admin.payments.show', $payment->id) }}" class="dashboard-cell-link">
                                                        {{ $payment->payment_number }}
                                                    </a>
                                                </td>
                                                <td>{{ $payment->student->user->name ?? 'غير محدد' }}</td>
                                                <td>
                                                    @if($payment->invoice)
                                                        <a href="{{ route('admin.invoices.show', $payment->invoice->id) }}" class="dashboard-cell-link-info">
                                                            {{ $payment->invoice->invoice_number }}
                                                        </a>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td class="dashboard-amount">{{ number_format($payment->amount, 2) }} ر.س</td>
                                                <td>{{ $payment->payment_method_name }}</td>
                                                <td>{{ $payment->payment_date->format('Y-m-d') }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="dashboard-table-empty">
                                                    <i class="ri-wallet-3-line"></i>
                                                    لا توجد بيانات
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @if($recentPayments->count() > 0)
                            <div class="dashboard-table-footer">
                                <a href="{{ route('admin.payments.index') }}" class="dashboard-view-all">
                                    عرض جميع المدفوعات
                                    <i class="ri-arrow-left-s-line"></i>
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>
@stop

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.stat-value[data-count]').forEach(function (el) {
            const target = parseInt(el.getAttribute('data-count'), 10) || 0;
            const duration = 1400;
            const start = performance.now();

            function easeOutExpo(t) {
                return t === 1 ? 1 : 1 - Math.pow(2, -10 * t);
            }

            function tick(now) {
                const progress = Math.min((now - start) / duration, 1);
                const value = Math.round(easeOutExpo(progress) * target);
                el.textContent = value.toLocaleString('en-US');
                if (progress < 1) {
                    requestAnimationFrame(tick);
                } else {
                    el.classList.add('stat-value-done');
                }
            }

            requestAnimationFrame(tick);
        });

        document.querySelectorAll('.shortcut-card').forEach(function (card) {
            card.addEventListener('click', function (e) {
                const rect = card.getBoundingClientRect();
                const size = Math.max(rect.width, rect.height);
                const ripple = document.createElement('span');
                ripple.className = 'shortcut-ripple';
                ripple.style.width = ripple.style.height = size + 'px';
                ripple.style.left = (e.clientX - rect.left - size / 2) + 'px';
                ripple.style.top = (e.clientY - rect.top - size / 2) + 'px';
                card.appendChild(ripple);
                ripple.addEventListener('animationend', function () { ripple.remove(); });
            });
        });

        const chartEl = document.querySelector('#enrollment-chart');
        if (!chartEl || typeof ApexCharts === 'undefined') return;

        const enrollmentData = @json($enrollmentChart);

        new ApexCharts(chartEl, {
            series: [{
                name: 'الالتحاقات',
                data: enrollmentData.map(item => item.count)
            }],
            chart: {
                type: 'area',
                height: 320,
                toolbar: { show: false },
                fontFamily: 'Cairo, sans-serif'
            },
            colors: ['#4a7dff'],
            dataLabels: { enabled: false },
            stroke: { curve: 'smooth', width: 3 },
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.35,
                    opacityTo: 0.05,
                }
            },
            xaxis: {
                categories: enrollmentData.map(item => item.label),
            },
            yaxis: {
                labels: {
                    formatter: function (val) {
                        return Math.round(val);
                    }
                }
            },
            grid: {
                borderColor: '#f1f5f9',
            },
            tooltip: {
                y: {
                    formatter: function (val) {
                        return val + ' طالب';
                    }
                }
            }
        }).render();
    });
</script>
@endpush
