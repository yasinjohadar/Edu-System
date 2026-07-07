<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>نسيت كلمة المرور - {{ config('app.name', 'نظام التعليم') }}</title>
    <link rel="icon" href="{{ asset('assets/images/brand-logos/favicon.ico') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('assets/css/icons.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/login.css') }}">
</head>
<body>
    <div class="login-page">
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

                <h2 class="login-hero-title">استعادة الوصول إلى حسابك</h2>
                <p class="login-hero-subtitle">
                    لا تقلق، سنرسل لك رابطاً آمناً لإعادة تعيين كلمة المرور. تأكد من إدخال البريد الإلكتروني المسجّل في النظام.
                </p>

                <div class="login-features">
                    <div class="login-feature">
                        <span class="login-feature-icon"><i class="ri-shield-keyhole-line"></i></span>
                        <span>رابط آمن ومؤقت</span>
                    </div>
                    <div class="login-feature">
                        <span class="login-feature-icon"><i class="ri-mail-send-line"></i></span>
                        <span>إرسال فوري عبر البريد</span>
                    </div>
                    <div class="login-feature">
                        <span class="login-feature-icon"><i class="ri-lock-unlock-line"></i></span>
                        <span>إعادة تعيين سهلة</span>
                    </div>
                    <div class="login-feature">
                        <span class="login-feature-icon"><i class="ri-customer-service-2-line"></i></span>
                        <span>دعم فني متاح</span>
                    </div>
                </div>
            </div>
        </aside>

        <main class="login-form-panel">
            <div class="login-form-card">
                <div class="login-form-header">
                    <div class="login-avatar">
                        <span class="login-avatar-ring"></span>
                        <i class="ri-key-2-line"></i>
                    </div>
                    <h2>نسيت كلمة المرور؟</h2>
                    <p>لا تقلق، سنرسل لك رابط إعادة التعيين</p>
                </div>

                <div class="login-info-box">
                    <i class="ri-information-line"></i>
                    <span>أدخل بريدك الإلكتروني وسنرسل لك رابط إعادة تعيين كلمة المرور عبر البريد الإلكتروني.</span>
                </div>

                @if (session('status'))
                    <div class="login-alert">
                        <i class="ri-checkbox-circle-line"></i>
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}" id="forgot-form">
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

                    <a class="login-back-link" href="{{ route('login') }}">
                        <i class="ri-arrow-right-line"></i>
                        العودة إلى تسجيل الدخول
                    </a>

                    <button type="submit" class="login-submit" id="forgot-submit">
                        <span class="login-spinner"></span>
                        <i class="ri-mail-send-line login-submit-icon"></i>
                        <span class="login-submit-text">إرسال رابط إعادة التعيين</span>
                    </button>
                </form>
            </div>
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('forgot-form');
            const submitBtn = document.getElementById('forgot-submit');

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
        });
    </script>
</body>
</html>
