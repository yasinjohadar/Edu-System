<div class="admin-table-footer">
    <div class="admin-table-meta" id="subjects-table-meta">
        @if ($subjects->total() > 0)
            عرض {{ $subjects->firstItem() }} إلى {{ $subjects->lastItem() }} من {{ $subjects->total() }} نتيجة
        @else
            لا توجد نتائج
        @endif
    </div>
    <div class="admin-pagination" id="subjects-pagination">
        {{ $subjects->withQueryString()->links() }}
    </div>
</div>
