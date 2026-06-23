<div class="admin-table-footer">
    <div class="admin-table-meta" id="students-table-meta">
        @if ($students->total() > 0)
            عرض {{ $students->firstItem() }} إلى {{ $students->lastItem() }} من {{ $students->total() }} نتيجة
        @else
            لا توجد نتائج
        @endif
    </div>
    <div class="admin-pagination" id="students-pagination">
        {{ $students->withQueryString()->links() }}
    </div>
</div>
