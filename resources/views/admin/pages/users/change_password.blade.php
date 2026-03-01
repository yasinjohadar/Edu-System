<!-- Change Password Modal -->
<div class="modal fade" id="change_password{{$user->id}}" tabindex="-1" aria-labelledby="changePasswordLabel{{$user->id}}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 10px; border: none; box-shadow: 0 10px 40px rgba(0,0,0,0.2);">
            <div class="modal-body p-5">
                <!-- Icon -->
                <div class="mb-4 text-center">
                    <div style="width: 80px; height: 80px; margin: 0 auto; background: linear-gradient(135deg, #3498db 0%, #2980b9 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: 0 8px 20px rgba(52, 152, 219, 0.3);">
                        <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                            <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                        </svg>
                    </div>
                </div>
                
                <!-- Title -->
                <h4 class="mb-4 text-center" style="color: #2c3e50; font-weight: 600; font-family: 'Cairo', sans-serif;">
                    تعديل كلمة المرور
                </h4>
                
                <!-- Form -->
                <form method="POST" action="{{ route('users.update-password', $user->id) }}" id="changePasswordForm{{$user->id}}">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label for="password{{$user->id}}" class="form-label" style="color: #34495e; font-weight: 600; font-family: 'Cairo', sans-serif; margin-bottom: 8px;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-left: 5px; vertical-align: middle;">
                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                                <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                            </svg>
                            كلمة المرور الجديدة
                        </label>
                        <div class="position-relative">
                            <input type="password" name="password" id="password{{$user->id}}" class="form-control" 
                                   placeholder="أدخل كلمة المرور الجديدة" required
                                   style="padding: 12px 45px 12px 15px; border-radius: 8px; border: 2px solid #e0e0e0; font-family: 'Cairo', sans-serif; transition: all 0.3s;"
                                   onfocus="this.style.borderColor='#3498db'; this.style.boxShadow='0 0 0 3px rgba(52, 152, 219, 0.1)'"
                                   onblur="this.style.borderColor='#e0e0e0'; this.style.boxShadow='none'">
                            <button type="button" class="btn btn-link position-absolute" 
                                    style="right: 10px; top: 50%; transform: translateY(-50%); padding: 0; border: none; background: none; color: #7f8c8d;"
                                    onclick="togglePasswordVisibility('password{{$user->id}}', this)">
                                <svg id="eye-icon-password{{$user->id}}" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                    <circle cx="12" cy="12" r="3"></circle>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="password_confirmation{{$user->id}}" class="form-label" style="color: #34495e; font-weight: 600; font-family: 'Cairo', sans-serif; margin-bottom: 8px;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-left: 5px; vertical-align: middle;">
                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                                <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                            </svg>
                            تأكيد كلمة المرور
                        </label>
                        <div class="position-relative">
                            <input type="password" name="password_confirmation" id="password_confirmation{{$user->id}}" class="form-control" 
                                   placeholder="أعد إدخال كلمة المرور" required
                                   style="padding: 12px 45px 12px 15px; border-radius: 8px; border: 2px solid #e0e0e0; font-family: 'Cairo', sans-serif; transition: all 0.3s;"
                                   onfocus="this.style.borderColor='#3498db'; this.style.boxShadow='0 0 0 3px rgba(52, 152, 219, 0.1)'"
                                   onblur="this.style.borderColor='#e0e0e0'; this.style.boxShadow='none'">
                            <button type="button" class="btn btn-link position-absolute" 
                                    style="right: 10px; top: 50%; transform: translateY(-50%); padding: 0; border: none; background: none; color: #7f8c8d;"
                                    onclick="togglePasswordVisibility('password_confirmation{{$user->id}}', this)">
                                <svg id="eye-icon-password_confirmation{{$user->id}}" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                    <circle cx="12" cy="12" r="3"></circle>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="d-flex gap-3 justify-content-center mt-4">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal" 
                                style="min-width: 120px; padding: 10px 20px; border-radius: 8px; font-weight: 600; font-family: 'Cairo', sans-serif; border: 2px solid #e0e0e0; transition: all 0.3s;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-left: 5px; vertical-align: middle;">
                                <line x1="18" y1="6" x2="6" y2="18"></line>
                                <line x1="6" y1="6" x2="18" y2="18"></line>
                            </svg>
                            إغلاق
                        </button>
                        <button type="submit" class="btn btn-primary" 
                                style="min-width: 150px; padding: 10px 20px; border-radius: 8px; font-weight: 600; font-family: 'Cairo', sans-serif; background: linear-gradient(135deg, #3498db 0%, #2980b9 100%); border: none; box-shadow: 0 4px 15px rgba(52, 152, 219, 0.4); transition: all 0.3s;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="margin-left: 5px; vertical-align: middle;">
                                <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                                <polyline points="17 21 17 13 7 13 7 21"></polyline>
                                <polyline points="7 3 7 8 15 8"></polyline>
                            </svg>
                            تعديل كلمة المرور
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function togglePasswordVisibility(inputId, button) {
    const input = document.getElementById(inputId);
    const eyeIcon = button.querySelector('svg');
    
    if (input.type === 'password') {
        input.type = 'text';
        eyeIcon.innerHTML = `
            <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
            <line x1="1" y1="1" x2="23" y2="23"></line>
        `;
    } else {
        input.type = 'password';
        eyeIcon.innerHTML = `
            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
            <circle cx="12" cy="12" r="3"></circle>
        `;
    }
}
</script>
