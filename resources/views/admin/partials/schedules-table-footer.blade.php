<div class="admin-table-footer">
    <div class="admin-table-meta" id="schedules-table-meta">
        @if ($schedules->total() > 0)
            عرض {{ $schedules->firstItem() }} إلى {{ $schedules->lastItem() }} من {{ $schedules->total() }} نتيجة
        @else
            لا توجد نتائج
        @endif
    </div>
    <div class="admin-pagination" id="schedules-pagination">
        {{ $schedules->withQueryString()->links() }}
    </div>
</div>
