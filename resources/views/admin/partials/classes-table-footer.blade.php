<div class="admin-table-footer">
    <div class="admin-table-meta" id="classes-table-meta">
        @if ($classes->total() > 0)
            عرض {{ $classes->firstItem() }} إلى {{ $classes->lastItem() }} من {{ $classes->total() }} نتيجة
        @else
            لا توجد نتائج
        @endif
    </div>
    <div class="admin-pagination" id="classes-pagination">
        {{ $classes->withQueryString()->links() }}
    </div>
</div>
