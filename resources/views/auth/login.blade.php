<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>تسجيل الدخول - {{ config('app.name', 'نظام التعليم') }}</title>
    <link rel="icon" href="{{ asset('assets/images/brand-logos/favicon.ico') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('assets/css/icons.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/login.css') }}">
</head>
<body>
    <div class="login-page">
        {{-- لوحة العرض الجانبية --}}
        <aside class="login-hero" aria-hidden="true">
            <div class="login-hero-mesh"></div>
            <div class="login-hero-bubble login-hero-bubble-1"></div>
            <div class="login-hero-bubble login-hero-bubble-2"></div>
            <div class="login-hero-bubble login-hero-bubble-3"></div>

            <div class="login-hero-content">
                <div class="login-brand">
                    <div class="login-brand-icon">
                        <i class="ri-graduation-cap-line"></i>
                    </div>
                    <div class="login-brand-text">
                        <h1>{{ config('app.name', 'نظام التعليم') }}</h1>
                        <p>منصة إدارة تعليمية متكاملة</p>
                    </div>
                </div>

                <h2 class="login-hero-title">مرحباً بك في بوابة الإدارة الذكية</h2>
                <p class="login-hero-subtitle">
                    سجّل دخولك للوصول إلى لوحة التحكم، إدارة الطلاب، الحضور، الفواتير، والمحاضرات الإلكترونية — كل شيء في مكان واحد.
                </p>

                <div class="login-features">
                    <div class="login-feature">
                        <span class="login-feature-icon"><i class="ri-group-line"></i></span>
                        <span>إدارة الطلاب والمعلمين</span>
                    </div>
                    <div class="login-feature">
                        <span class="login-feature-icon"><i class="ri-calendar-check-line"></i></span>
                        <span>الحضور والغياب</span>
                    </div>
                    <div class="login-feature">
                        <span class="login-feature-icon"><i class="ri-bill-line"></i></span>
                        <span>الفواتير والمدفوعات</span>
                    </div>
                    <div class="login-feature">
                        <span class="login-feature-icon"><i class="ri-live-line"></i></span>
                        <span>المحاضرات الإلكترونية</span>
                    </div>
                </div>
            </div>
        </aside>

        {{-- نموذج تسجيل الدخول --}}
        <main class="login-form-panel">
            <div class="login-form-card">
                <div class="login-form-header">
                    <div class="login-avatar">
                        <span class="login-avatar-ring"></span>
                        <i class="ri-user-smile-line"></i>
                    </div>
                    <h2>تسجيل الدخول</h2>
                    <p>أدخل بياناتك للوصول إلى حسابك</p>
                </div>

                @if (session('status'))
                    <div class="login-alert">
                        <i class="ri-checkbox-circle-line"></i>
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" id="login-form">
                    @csrf

                    <div class="login-field">
                        <label for="email">
                            <i class="ri-mail-line"></i>
                            البريد الإلكتروني
                        </label>
                        <div class="login-input-wrap">
                            <input
                                id="email"
                                class="login-input"
                                type="email"
                                name="email"
                                value="{{ old('email') }}"
                                required
                                autofocus
                                autocomplete="username"
                                placeholder="example@school.com"
                            />
                            <i class="ri-mail-line login-input-icon"></i>
                        </div>
                        @error('email')
                            <span class="login-error">
                                <i class="ri-error-warning-line"></i>
                                {{ $message }}
                            </span>
                        @enderror
                    </div>

                    <div class="login-field">
                        <label for="password">
                            <i class="ri-lock-password-line"></i>
                            كلمة المرور
                        </label>
                        <div class="login-input-wrap">
                            <button type="button" class="login-toggle-pw" id="toggle-password" aria-label="إظهار كلمة المرور">
                                <i class="ri-eye-off-line" id="pw-icon"></i>
                            </button>
                            <input
                                id="password"
                                class="login-input"
                                type="password"
                                name="password"
                                required
                                autocomplete="current-password"
                                placeholder="••••••••"
                                style="padding-inline-start: 2.75rem;"
                            />
                            <i class="ri-lock-line login-input-icon"></i>
                        </div>
                        @error('password')
                            <span class="login-error">
                                <i class="ri-error-warning-line"></i>
                                {{ $message }}
                            </span>
                        @enderror
                    </div>

                    <div class="login-options">
                        <label class="login-remember">
                            <input id="remember_me" type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                            <span class="login-remember-box"><i class="ri-check-line"></i></span>
                            <span>تذكرني</span>
                        </label>

                        @if (Route::has('password.request'))
                            <a class="login-forgot" href="{{ route('password.request') }}">
                                <i class="ri-question-line"></i>
                                نسيت كلمة المرور؟
                            </a>
                        @endif
                    </div>

                    <button type="submit" class="login-submit" id="login-submit">
                        <span class="login-spinner"></span>
                        <i class="ri-login-box-line login-submit-icon"></i>
                        <span class="login-submit-text">تسجيل الدخول</span>
                    </button>
                </form>
            </div>
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const passwordInput = document.getElementById('password');
            const toggleBtn = document.getElementById('toggle-password');
            const pwIcon = document.getElementById('pw-icon');
            const form = document.getElementById('login-form');
            const submitBtn = document.getElementById('login-submit');

            toggleBtn.addEventListener('click', function () {
                const isPassword = passwordInput.type === 'password';
                passwordInput.type = isPassword ? 'text' : 'password';
                pwIcon.className = isPassword ? 'ri-eye-line' : 'ri-eye-off-line';
            });

            submitBtn.addEventListener('click', function (e) {
                const rect = submitBtn.getBoundingClientRect();
                const size = Math.max(rect.width, rect.height);
                const ripple = document.createElement('span');
                ripple.className = 'login-ripple';
                ripple.style.width = ripple.style.height = size + 'px';
                ripple.style.left = (e.clientX - rect.left - size / 2) + 'px';
                ripple.style.top = (e.clientY - rect.top - size / 2) + 'px';
                submitBtn.appendChild(ripple);
                ripple.addEventListener('animationend', function () { ripple.remove(); });
            });

            form.addEventListener('submit', function () {
                submitBtn.classList.add('loading');
            });

            document.querySelectorAll('.login-input').forEach(function (input) {
                input.addEventListener('focus', function () {
                    this.closest('.login-field')?.classList.add('is-focused');
                });
                input.addEventListener('blur', function () {
                    this.closest('.login-field')?.classList.remove('is-focused');
                });
            });
        });
    </script>
</body>
</html>
