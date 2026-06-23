@forelse ($users as $user)
        @php
            $userSessions = $sessions->get($user->id);
            $lastSession = $userSessions ? $userSessions->first() : null;
        @endphp
        <tr>
            <th scope="row" class="row-number">{{ $users->firstItem() + $loop->index }}</th>
            <td>
                <a href="{{ route('users.show', $user->id) }}" class="admin-user-link">{{ $user->name }}</a>
            </td>
            <td>
                @if ($user->email)
                    <div class="admin-email-cell">
                        <a href="mailto:{{ $user->email }}" class="admin-email-link">{{ $user->email }}</a>
                        <button type="button" class="admin-copy-btn" data-copy-email="{{ $user->email }}" title="نسخ البريد">
                            <i class="ri-file-copy-line"></i>
                        </button>
                    </div>
                @else
                    <span class="text-muted">—</span>
                @endif
            </td>
            <td>
                @if ($user->phone)
                    <span class="admin-phone-cell">
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $user->phone) }}" target="_blank" title="واتساب">
                            <i class="fab fa-whatsapp"></i>
                        </a>
                        {{ $user->phone }}
                    </span>
                @else
                    <span class="text-muted">—</span>
                @endif
            </td>
            <td>
                @if ($lastSession)
                    <span class="text-muted">{{ \Carbon\Carbon::createFromTimestamp($lastSession->last_activity)->diffForHumans() }}</span>
                @else
                    <span class="text-muted">لا توجد جلسات</span>
                @endif
            </td>
            <td>
                @forelse ($user->getRoleNames() as $role)
                    <span class="admin-badge admin-badge-role">{{ $role }}</span>
                @empty
                    <span class="text-muted">—</span>
                @endforelse
            </td>
            <td>
                @if ($user->status === 'active')
                    <span class="admin-badge admin-badge-success">مفعل</span>
                @elseif($user->status === 'inactive')
                    <span class="admin-badge admin-badge-warning">موقوف</span>
                @elseif($user->status === 'banned')
                    <span class="admin-badge admin-badge-danger">محظور</span>
                @else
                    <span class="admin-badge admin-badge-muted">غير معروف</span>
                @endif
            </td>
            <td>
                <div class="admin-status-switch">
                    <input class="form-check-input toggle-status" type="checkbox"
                           data-user-id="{{ $user->id }}"
                           {{ $user->is_active ? 'checked' : '' }}>
                    <span class="status-label {{ $user->is_active ? 'is-active' : '' }}">
                        {{ $user->is_active ? 'نشط' : 'غير نشط' }}
                    </span>
                </div>
            </td>
            <td>
                <div class="admin-action-group">
                    <a class="admin-action-btn admin-action-edit" href="{{ route('users.edit', $user->id) }}" title="تعديل">
                        <i class="ri-edit-line"></i>
                    </a>
                    <a class="admin-action-btn admin-action-delete"
                       data-delete-url="{{ route('users.destroy', $user->id) }}"
                       data-delete-message="هل أنت متأكد من رغبتك في حذف المستخدم <strong>{{ $user->name }}</strong>؟"
                       title="حذف">
                        <i class="ri-delete-bin-line"></i>
                    </a>
                    <a href="#" class="admin-action-btn admin-action-key"
                       data-bs-toggle="modal"
                       data-bs-target="#change_password{{ $user->id }}"
                       title="كلمة المرور">
                        <i class="ri-key-2-line"></i>
                    </a>
                </div>
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="9">
                <div class="admin-empty-state">
                    <i class="ri-user-search-line"></i>
                    <div>لا توجد نتائج مطابقة</div>
                </div>
            </td>
        </tr>
    @endforelse
