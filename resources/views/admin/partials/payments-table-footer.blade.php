<div class="admin-table-footer">
    <div class="admin-table-meta" id="payments-table-meta">
        @if ($payments->total() > 0)
            عرض {{ $payments->firstItem() }} إلى {{ $payments->lastItem() }} من {{ $payments->total() }} نتيجة
        @else
            لا توجد نتائج
        @endif
    </div>
    <div class="admin-pagination" id="payments-pagination">
        {{ $payments->withQueryString()->links() }}
    </div>
</div>
