<div class="admin-table-footer">
    <div class="admin-table-meta" id="teachers-table-meta">
        @if ($teachers->total() > 0)
            عرض {{ $teachers->firstItem() }} إلى {{ $teachers->lastItem() }} من {{ $teachers->total() }} نتيجة
        @else
            لا توجد نتائج
        @endif
    </div>
    <div class="admin-pagination" id="teachers-pagination">
        {{ $teachers->withQueryString()->links() }}
    </div>
</div>
