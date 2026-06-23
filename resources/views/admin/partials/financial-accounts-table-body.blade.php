@forelse ($accounts as $account)
    <tr>
        <th scope="row" class="row-number">{{ $accounts->firstItem() + $loop->index }}</th>
        <td><span class="admin-badge admin-badge-muted">{{ $account->account_number }}</span></td>
        <td>
            <strong class="admin-user-link d-block">{{ $account->student->user->name ?? 'غير محدد' }}</strong>
            <small class="text-muted">{{ $account->student->student_code }}</small>
        </td>
        <td><span class="admin-badge admin-badge-role">{{ number_format($account->total_invoiced, 2) }} ر.س</span></td>
        <td><span class="admin-badge admin-badge-success">{{ number_format($account->total_paid, 2) }} ر.س</span></td>
        <td><span class="admin-badge admin-badge-danger">{{ number_format($account->total_due, 2) }} ر.س</span></td>
        <td>
            @if ($account->balance >= 0)
                <span class="admin-badge admin-badge-success">{{ number_format($account->balance, 2) }} ر.س</span>
            @else
                <span class="admin-badge admin-badge-danger">{{ number_format($account->balance, 2) }} ر.س</span>
            @endif
        </td>
        <td>
            @if ($account->last_transaction_date)
                {{ $account->last_transaction_date->format('Y-m-d') }}
            @else
                <span class="text-muted">—</span>
            @endif
        </td>
        <td>
            <div class="admin-action-group">
                <a href="{{ route('admin.financial-accounts.show', $account->id) }}" class="admin-action-btn admin-action-view" title="عرض التفاصيل">
                    <i class="ri-eye-line"></i>
                </a>
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="9">
            <div class="admin-empty-state">
                <i class="ri-bank-line"></i>
                لا توجد حسابات مالية
            </div>
        </td>
    </tr>
@endforelse
