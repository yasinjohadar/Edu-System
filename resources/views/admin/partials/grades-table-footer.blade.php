<div class="admin-table-footer">
    <div class="admin-table-meta" id="grades-table-meta">
        @if ($grades->total() > 0)
            عرض {{ $grades->firstItem() }} إلى {{ $grades->lastItem() }} من {{ $grades->total() }} نتيجة
        @else
            لا توجد نتائج
        @endif
    </div>
    <div class="admin-pagination" id="grades-pagination">
        {{ $grades->withQueryString()->links() }}
    </div>
</div>
