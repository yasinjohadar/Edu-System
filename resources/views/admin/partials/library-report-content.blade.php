<div class="admin-report-stats">
    <button type="button" class="admin-report-stat admin-report-stat-blue {{ request('status') === '' || !request()->has('status') ? 'is-active' : '' }}" data-library-stat-filter="" title="عرض الكل">
        <span class="admin-report-stat-icon"><i class="ri-book-read-line"></i></span>
        <span class="admin-report-stat-label">إجمالي الاستعارات</span>
        <strong class="admin-report-stat-value">{{ $stats['total_borrowings'] }}</strong>
    </button>
    <button type="button" class="admin-report-stat admin-report-stat-cyan {{ request('status') == 'borrowed' ? 'is-active' : '' }}" data-library-stat-filter="borrowed" title="فلترة: مستعار">
        <span class="admin-report-stat-icon"><i class="ri-time-line"></i></span>
        <span class="admin-report-stat-label">نشطة</span>
        <strong class="admin-report-stat-value">{{ $stats['active_borrowings'] }}</strong>
    </button>
    <button type="button" class="admin-report-stat admin-report-stat-green {{ request('status') == 'returned' ? 'is-active' : '' }}" data-library-stat-filter="returned" title="فلترة: مُعاد">
        <span class="admin-report-stat-icon"><i class="ri-checkbox-circle-line"></i></span>
        <span class="admin-report-stat-label">مُعادة</span>
        <strong class="admin-report-stat-value">{{ $stats['returned_borrowings'] }}</strong>
    </button>
    <button type="button" class="admin-report-stat admin-report-stat-red {{ request('status') == 'overdue' ? 'is-active' : '' }}" data-library-stat-filter="overdue" title="فلترة: متأخر">
        <span class="admin-report-stat-icon"><i class="ri-alarm-warning-line"></i></span>
        <span class="admin-report-stat-label">متأخرة</span>
        <strong class="admin-report-stat-value">{{ $stats['overdue_borrowings'] }}</strong>
    </button>
    <div class="admin-report-stat admin-report-stat-amber admin-report-stat-static">
        <span class="admin-report-stat-icon"><i class="ri-money-dollar-circle-line"></i></span>
        <span class="admin-report-stat-label">إجمالي الغرامات</span>
        <strong class="admin-report-stat-value">{{ $fineStats['total_fines'] }}</strong>
        <small>{{ number_format($fineStats['total_amount'], 2) }} ر.س</small>
    </div>
    <div class="admin-report-stat admin-report-stat-green admin-report-stat-static">
        <span class="admin-report-stat-icon"><i class="ri-check-double-line"></i></span>
        <span class="admin-report-stat-label">مدفوعة</span>
        <strong class="admin-report-stat-value">{{ number_format($fineStats['paid_amount'], 2) }}</strong>
        <small>ر.س</small>
    </div>
    <div class="admin-report-stat admin-report-stat-red admin-report-stat-static">
        <span class="admin-report-stat-icon"><i class="ri-close-circle-line"></i></span>
        <span class="admin-report-stat-label">غير مدفوعة</span>
        <strong class="admin-report-stat-value">{{ number_format($fineStats['unpaid_amount'], 2) }}</strong>
        <small>ر.س</small>
    </div>
</div>

<div class="admin-report-section">
    <div class="admin-report-section-head">
        <h3><i class="ri-book-open-line"></i> الاستعارات</h3>
        <span class="admin-badge admin-badge-muted">{{ $borrowings->count() }}</span>
    </div>
    <div class="admin-table-wrap">
        <div class="table-responsive">
            @if ($borrowings->count() > 0)
                <table class="admin-data-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>اسم الطالب</th>
                            <th>اسم الكتاب</th>
                            <th>تاريخ الاستعارة</th>
                            <th>تاريخ الإرجاع المتوقع</th>
                            <th>تاريخ الإرجاع الفعلي</th>
                            <th>الحالة</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($borrowings as $index => $borrowing)
                            <tr>
                                <th scope="row" class="row-number">{{ $index + 1 }}</th>
                                <td>
                                    <strong class="admin-user-link d-block">{{ $borrowing->student->user->name }}</strong>
                                    <small class="text-muted">{{ $borrowing->borrowing_number }}</small>
                                </td>
                                <td>{{ $borrowing->book->title }}</td>
                                <td>{{ $borrowing->borrow_date->format('Y-m-d') }}</td>
                                <td>{{ $borrowing->due_date->format('Y-m-d') }}</td>
                                <td>{{ $borrowing->return_date ? $borrowing->return_date->format('Y-m-d') : '—' }}</td>
                                <td>
                                    @if ($borrowing->status == 'borrowed')
                                        <span class="admin-badge admin-badge-role">مستعار</span>
                                    @elseif ($borrowing->status == 'returned')
                                        <span class="admin-badge admin-badge-success">مُعاد</span>
                                    @elseif ($borrowing->status == 'lost')
                                        <span class="admin-badge admin-badge-muted">مفقود</span>
                                    @else
                                        <span class="admin-badge admin-badge-danger">متأخر</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="admin-empty-state">
                    <i class="ri-book-2-line"></i>
                    لا توجد استعارات متاحة
                </div>
            @endif
        </div>
    </div>
</div>

<div class="admin-report-section">
    <div class="admin-report-section-head">
        <h3><i class="ri-money-dollar-circle-line"></i> الغرامات</h3>
        <span class="admin-badge admin-badge-muted">{{ $fines->count() }}</span>
    </div>
    <div class="admin-table-wrap">
        <div class="table-responsive">
            @if ($fines->count() > 0)
                <table class="admin-data-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>اسم الطالب</th>
                            <th>اسم الكتاب</th>
                            <th>المبلغ</th>
                            <th>تاريخ الإنشاء</th>
                            <th>الحالة</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($fines as $index => $fine)
                            <tr>
                                <th scope="row" class="row-number">{{ $index + 1 }}</th>
                                <td>{{ $fine->borrowing->student->user->name ?? '—' }}</td>
                                <td>{{ $fine->borrowing->book->title ?? '—' }}</td>
                                <td><span class="admin-badge admin-badge-warning">{{ number_format($fine->amount, 2) }} ر.س</span></td>
                                <td>{{ $fine->created_at->format('Y-m-d') }}</td>
                                <td>
                                    @if ($fine->status == 'paid')
                                        <span class="admin-badge admin-badge-success">مدفوعة</span>
                                    @else
                                        <span class="admin-badge admin-badge-danger">غير مدفوعة</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="admin-empty-state">
                    <i class="ri-coins-line"></i>
                    لا توجد غرامات متاحة
                </div>
            @endif
        </div>
    </div>
</div>

@if ($borrowings->count() > 0 || $fines->count() > 0)
    <div class="admin-table-footer">
        <div class="admin-table-meta">تصدير نتائج التقرير</div>
        <form action="{{ route('admin.reports.export') }}" method="POST" class="d-flex flex-wrap gap-2">
            @csrf
            <input type="hidden" name="type" value="library">
            @foreach (request()->except(['type', 'format', '_token']) as $key => $value)
                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
            @endforeach
            <button type="submit" name="format" value="excel" class="admin-btn admin-btn-primary">
                <i class="ri-file-excel-2-line"></i>
                تصدير Excel
            </button>
            <button type="submit" name="format" value="pdf" class="admin-btn admin-btn-danger">
                <i class="ri-file-pdf-line"></i>
                تصدير PDF
            </button>
        </form>
    </div>
@endif
