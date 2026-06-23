<div class="admin-table-footer">
    <div class="admin-table-meta" id="grade-records-table-meta">
        @if ($gradeRecords->total() > 0)
            عرض {{ $gradeRecords->firstItem() }} إلى {{ $gradeRecords->lastItem() }} من {{ $gradeRecords->total() }} نتيجة
        @else
            لا توجد نتائج
        @endif
    </div>
    <div class="admin-pagination" id="grade-records-pagination">
        {{ $gradeRecords->withQueryString()->links() }}
    </div>
</div>
