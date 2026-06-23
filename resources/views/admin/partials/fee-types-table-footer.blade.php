<div class="admin-table-footer">
    <div class="admin-table-meta" id="fee-types-table-meta">
        @if ($feeTypes->total() > 0)
            عرض {{ $feeTypes->firstItem() }} إلى {{ $feeTypes->lastItem() }} من {{ $feeTypes->total() }} نتيجة
        @else
            لا توجد نتائج
        @endif
    </div>
    <div class="admin-pagination" id="fee-types-pagination">
        {{ $feeTypes->withQueryString()->links() }}
    </div>
</div>
