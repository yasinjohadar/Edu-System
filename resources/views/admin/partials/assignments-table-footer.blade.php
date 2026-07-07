<div class="admin-table-footer">
    <div class="admin-table-meta" id="assignments-table-meta">
        @if ($assignments->total() > 0)
            عرض {{ $assignments->firstItem() }} إلى {{ $assignments->lastItem() }} من {{ $assignments->total() }} نتيجة
        @else
            لا توجد نتائج
        @endif
    </div>
    <div class="admin-pagination" id="assignments-pagination">
        {{ $assignments->withQueryString()->links() }}
    </div>
</div>
