@php
    $selected = $selectedPermissions ?? old('permissions', []);
    $groups = $permissionGroups ?? [];
    $cssPath = public_path('assets/css/role-permissions.css');
    $cssVersion = file_exists($cssPath) ? filemtime($cssPath) : time();
@endphp

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/role-permissions.css') }}?v={{ $cssVersion }}">
@endpush

<div id="rolePermissionsPicker" class="role-perms" data-permission-picker>
    <div class="role-perms__toolbar">
        <div class="search-input-wrap role-perms__search">
            <i class="ri-search-line"></i>
            <input type="text" class="form-control" placeholder="بحث في الصلاحيات..."
                   data-permission-search autocomplete="off">
        </div>
        <button type="button" class="admin-btn admin-btn-secondary" data-permission-expand-all>
            <i class="ri-arrow-down-double-line"></i>
            فتح الكل
        </button>
        <button type="button" class="admin-btn admin-btn-secondary" data-permission-collapse-all>
            <i class="ri-arrow-up-double-line"></i>
            طي الكل
        </button>
        <button type="button" class="admin-btn admin-btn-secondary" data-permission-select-all>
            <i class="ri-checkbox-multiple-line"></i>
            تحديد الظاهر
        </button>
        <button type="button" class="admin-btn admin-btn-secondary" data-permission-deselect-all>
            <i class="ri-close-circle-line"></i>
            إلغاء الظاهر
        </button>
    </div>

    @if (empty($groups))
        <p class="role-perms__empty">
            لا توجد صلاحيات في النظام. شغّل <code>php artisan db:seed --class=PermissionSeeder</code>
        </p>
    @else
        <div class="role-perms__groups">
            @foreach ($groups as $index => $group)
                @php
                    $selectedInGroup = collect($group['permissions'])->whereIn('name', $selected)->count();
                    $totalInGroup = count($group['permissions']);
                @endphp
                <details class="role-perms__group" data-permission-group @if($index === 0) open @endif>
                    <summary class="role-perms__summary">
                        <span class="role-perms__summary-title">
                            <i class="ri-folder-shield-line role-perms__summary-icon"></i>
                            <span class="role-perms__group-title">{{ $group['label'] }}</span>
                        </span>
                        <span class="role-perms__summary-meta">
                            <span class="role-perms__count">{{ $totalInGroup }}</span>
                            <span class="role-perms__badge" data-group-selected>{{ $selectedInGroup }}/{{ $totalInGroup }}</span>
                            <i class="ri-arrow-down-s-line role-perms__chevron"></i>
                        </span>
                    </summary>
                    <div class="role-perms__body">
                        <div class="role-perms__actions">
                            <button type="button" class="admin-btn admin-btn-secondary admin-btn-sm"
                                    data-permission-group-select>
                                <i class="ri-checkbox-line"></i>
                                تحديد الكل
                            </button>
                            <button type="button" class="admin-btn admin-btn-secondary admin-btn-sm"
                                    data-permission-group-deselect>
                                <i class="ri-checkbox-blank-line"></i>
                                إلغاء الكل
                            </button>
                        </div>
                        <div class="role-perms__list">
                            @foreach ($group['permissions'] as $permission)
                                @php
                                    $permId = 'perm-' . preg_replace('/[^a-zA-Z0-9_-]/', '-', $group['key'] . '-' . $permission['name']);
                                @endphp
                                <label class="role-perms__item"
                                       data-permission-item
                                       data-name="{{ $permission['name'] }}"
                                       data-label="{{ $permission['label'] }}">
                                    <input class="role-perms__checkbox"
                                           type="checkbox"
                                           name="permissions[]"
                                           value="{{ $permission['name'] }}"
                                           id="{{ $permId }}"
                                           {{ in_array($permission['name'], $selected) ? 'checked' : '' }}>
                                    <span class="role-perms__text">
                                        <span class="role-perms__name">{{ $permission['label'] }}</span>
                                        <span class="role-perms__key">{{ $permission['name'] }}</span>
                                    </span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </details>
            @endforeach
        </div>
    @endif

    <p class="role-perms__empty" data-permission-empty style="display: none;">
        لا توجد صلاحيات مطابقة للبحث
    </p>
</div>
