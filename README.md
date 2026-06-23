# Edu-System — نظام إدارة التعليم

نظام Laravel لإدارة المدارس: الطلاب، المعلمون، الحضور، الدرجات، المالية، المكتبة، الواجبات، الاختبارات، وغيرها.

## المتطلبات

- PHP 8.2+
- Composer
- MySQL / MariaDB
- Node.js (لأصول Vite إن لزم)

## التشغيل المحلي

```bash
composer install
cp .env.example .env
php artisan key:generate
# عدّل إعدادات قاعدة البيانات في .env
php artisan migrate --seed
php artisan serve
```

افتح: `http://127.0.0.1:8000`

## بيانات الدخول الافتراضية (بعد seed)

راجع `database/seeders/AdminUserSeeder.php` — عادةً مستخدم مدير من Seeder.

## خطة التكامل

- **الخارطة:** [docs/INTEGRATION_ROADMAP_AR.md](docs/INTEGRATION_ROADMAP_AR.md)
- **التتبع:** [docs/INTEGRATION_PROGRESS.md](docs/INTEGRATION_PROGRESS.md)
- **خطة التنفيذ الحالية:** [docs/superpowers/plans/2026-06-23-phase-0-transport.md](docs/superpowers/plans/2026-06-23-phase-0-transport.md)

## هيكل المسارات

| البادئة | الجمهور |
|---------|---------|
| `/admin/*` | الإدارة |
| `/student/*` | الطالب |
| `/parent/*` | ولي الأمر |

---

<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>
