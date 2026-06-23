<div class="admin-table-footer">
    <div class="admin-table-meta" id="invoices-table-meta">
        @if ($invoices->total() > 0)
            عرض {{ $invoices->firstItem() }} إلى {{ $invoices->lastItem() }} من {{ $invoices->total() }} نتيجة
        @else
            لا توجد نتائج
        @endif
    </div>
    <div class="admin-pagination" id="invoices-pagination">
        {{ $invoices->withQueryString()->links() }}
    </div>
</div>
