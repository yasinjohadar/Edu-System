<div class="admin-table-footer">
    <div class="admin-table-meta" id="attendances-table-meta">
        @if ($attendances->total() > 0)
            عرض {{ $attendances->firstItem() }} إلى {{ $attendances->lastItem() }} من {{ $attendances->total() }} نتيجة
        @else
            لا توجد نتائج
        @endif
    </div>
    <div class="admin-pagination" id="attendances-pagination">
        {{ $attendances->withQueryString()->links() }}
    </div>
</div>
