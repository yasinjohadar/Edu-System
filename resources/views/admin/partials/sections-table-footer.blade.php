<div class="admin-table-footer">
    <div class="admin-table-meta" id="sections-table-meta">
        @if ($sections->total() > 0)
            عرض {{ $sections->firstItem() }} إلى {{ $sections->lastItem() }} من {{ $sections->total() }} نتيجة
        @else
            لا توجد نتائج
        @endif
    </div>
    <div class="admin-pagination" id="sections-pagination">
        {{ $sections->withQueryString()->links() }}
    </div>
</div>
