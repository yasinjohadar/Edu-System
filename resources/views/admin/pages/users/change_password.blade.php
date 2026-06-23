<!-- Change Password Modal -->
<div class="modal fade admin-password-modal" id="change_password{{ $user->id }}" tabindex="-1"
     aria-labelledby="changePasswordLabel{{ $user->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content admin-modal-content">
            <div class="modal-body admin-password-modal-body">
                <div class="admin-pw-modal-icon">
                    <i class="ri-lock-password-line"></i>
                </div>

                <h4 class="admin-pw-modal-title" id="changePasswordLabel{{ $user->id }}">تعديل كلمة المرور</h4>
                <p class="admin-pw-modal-subtitle">للمستخدم: <strong>{{ $user->name }}</strong></p>

                <div class="admin-pw-suggestions-wrap">
                    <div class="admin-pw-suggestions-head">
                        <span><i class="ri-magic-line"></i> اقتراحات آمنة</span>
                        <button type="button" class="admin-pw-refresh-btn" data-refresh-pw-suggestions>
                            <i class="ri-refresh-line"></i>
                            توليد جديد
                        </button>
                    </div>
                    <div class="admin-pw-suggestions" data-pw-suggestions></div>
                </div>

                <form method="POST" action="{{ route('users.update-password', $user->id) }}"
                      id="changePasswordForm{{ $user->id }}" class="admin-form">
                    @csrf
                    @method('PUT')

                    <div class="admin-form-field mb-3">
                        <label for="password{{ $user->id }}" class="admin-form-label">كلمة المرور الجديدة</label>
                        <div class="admin-password-wrap">
                            <input type="password" name="password" id="password{{ $user->id }}"
                                   class="form-control" placeholder="أدخل كلمة المرور الجديدة"
                                   data-pw-main required autocomplete="new-password">
                            <button type="button" class="admin-password-toggle admin-pw-copy-btn"
                                    data-copy-password data-copy-target="password{{ $user->id }}"
                                    title="نسخ كلمة المرور">
                                <i class="ri-file-copy-line"></i>
                            </button>
                            <button type="button" class="admin-password-toggle"
                                    data-toggle-password="password{{ $user->id }}"
                                    title="إظهار/إخفاء">
                                <i class="ri-eye-line"></i>
                            </button>
                        </div>
                    </div>

                    <div class="admin-form-field mb-4">
                        <label for="password_confirmation{{ $user->id }}" class="admin-form-label">تأكيد كلمة المرور</label>
                        <div class="admin-password-wrap">
                            <input type="password" name="password_confirmation"
                                   id="password_confirmation{{ $user->id }}"
                                   class="form-control" placeholder="أعد إدخال كلمة المرور"
                                   data-pw-confirm required autocomplete="new-password">
                            <button type="button" class="admin-password-toggle"
                                    data-toggle-password="password_confirmation{{ $user->id }}"
                                    title="إظهار/إخفاء">
                                <i class="ri-eye-line"></i>
                            </button>
                        </div>
                    </div>

                    <div class="admin-pw-modal-actions">
                        <button type="button" class="admin-btn admin-btn-secondary" data-bs-dismiss="modal">
                            <i class="ri-close-line"></i>
                            إغلاق
                        </button>
                        <button type="submit" class="admin-btn admin-btn-primary">
                            <i class="ri-save-line"></i>
                            حفظ كلمة المرور
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
