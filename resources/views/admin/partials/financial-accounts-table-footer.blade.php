<div class="admin-table-footer">
    <div class="admin-table-meta" id="financial-accounts-table-meta">
        @if ($accounts->total() > 0)
            عرض {{ $accounts->firstItem() }} إلى {{ $accounts->lastItem() }} من {{ $accounts->total() }} نتيجة
        @else
            لا توجد نتائج
        @endif
    </div>
    <div class="admin-pagination" id="financial-accounts-pagination">
        {{ $accounts->withQueryString()->links() }}
    </div>
</div>
