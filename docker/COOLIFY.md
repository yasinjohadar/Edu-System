# نشر Edu-System على Coolify

دليل مختصر لنشر المشروع باستخدام **Docker Compose** build pack في Coolify.

## المتطلبات

- خادم Coolify مع Docker
- قاعدة بيانات MySQL أو PostgreSQL (خدمة Coolify أو خارجية)
- مستودع Git متصل بـ Coolify

## 1. إنشاء المشروع في Coolify

1. **New Resource** → **Application**
2. اختر المستودع (Git repository)
3. **Build Pack**: `Docker Compose`
4. **Docker Compose Location**: `docker-compose.yml`
5. **Base Directory**: `/` (جذر المشروع)

## 2. الخدمات (Services)

| الخدمة | الدور | المنفذ |
|--------|-------|--------|
| `app` | nginx + PHP-FPM (الويب) | `8080` |
| `queue` | معالج الطوابير | — |
| `scheduler` | جدولة Laravel | — |
| `redis` | Cache / Session / Queue | `6379` (داخلي) |

في Coolify، اربط **النطاق (FQDN)** و SSL بخدمة `app` فقط.

## 3. Persistent Volumes

فعّل التخزين الدائم للـ volumes التالية في Coolify:

| Volume | المسار داخل الحاوية | الغرض |
|--------|---------------------|--------|
| `storage-public` | `/var/www/html/storage/app/public` | ملفات المستخدمين العامة |
| `storage-private` | `/var/www/html/storage/app/private` | ملفات خاصة |
| `redis-data` | `/data` (خدمة redis) | بيانات Redis |

بدون هذه الـ volumes ستُفقد الملفات المرفوعة وبيانات Redis عند كل إعادة نشر.

## 4. متغيرات البيئة الأساسية

> **مهم:** يجب ضبط `APP_KEY` في Coolify **قبل** أول deploy. بدونها يفشل الإقلاع أو يُولَّد مفتاح مؤقت يتغير مع كل إعادة نشر.

ولّد المفتاح محلياً:
```bash
php artisan key:generate --show
```

أضف المتغيرات في **Environment Variables** على مستوى المشروع (تُورث لجميع الخدمات):

```env
APP_NAME="Edu System"
APP_ENV=production
APP_KEY=base64:...          # ولّده بـ php artisan key:generate --show
APP_DEBUG=false
APP_URL=https://your-domain.com

DB_CONNECTION=mysql
DB_HOST=...                 # hostname خدمة DB في Coolify
DB_PORT=3306
DB_DATABASE=...
DB_USERNAME=...
DB_PASSWORD=...

CACHE_STORE=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis
REDIS_HOST=redis
REDIS_CLIENT=phpredis
REDIS_PORT=6379

FILESYSTEM_DISK=public
LOG_CHANNEL=stderr
LOG_LEVEL=info
```

### متغيرات خاصة بالخدمات

| المتغير | الخدمة | القيمة |
|---------|--------|--------|
| `RUN_MIGRATIONS` | `app` | `true` (افتراضي) |
| `RUN_MIGRATIONS` | `queue`, `scheduler` | `false` |
| `AUTORUN_ENABLED` | `queue`, `scheduler` | `false` |

الهجرات تُشغَّل تلقائياً عند إقلاع `app` عبر `migrate --isolated` (آمن مع عدة نسخ).

## 5. SSL و Reverse Proxy

1. في Coolify → خدمة `app` → **Domains**
2. أضف النطاق (مثل `edu.example.com`)
3. فعّل **Let's Encrypt** لشهادة SSL
4. Coolify يوجّه HTTPS إلى المنفذ `8080` داخل الحاوية

تأكد أن `APP_URL` يطابق النطاق مع `https://`.

## 6. قاعدة البيانات

1. أنشئ خدمة MySQL/PostgreSQL في Coolify (أو استخدم قاعدة خارجية)
2. انسخ `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`
3. ضعها في متغيرات البيئة
4. عند أول نشر، `app` يشغّل الهجرات تلقائياً

## 7. البناء المحلي (اختياري)

```bash
docker compose build
docker compose up -d
```

التحقق من الصحة:

```bash
curl http://localhost:8080/up
```

## 8. استكشاف الأخطاء

| المشكلة | الحل |
|---------|------|
| 502 Bad Gateway | انتظر `start_period` (30s)؛ راجع لوجز `app` |
| ملفات مرفوعة تختفي | تأكد من تفعيل volume `storage-public` |
| Queue لا يعمل | راجع لوجز خدمة `queue`؛ تأكد من `REDIS_HOST=redis` |
| Session تُفقد | تأكد من `SESSION_DRIVER=redis` و volume `redis-data` |
| خطأ APP_KEY | ولّد مفتاحاً وضعه في Coolify env |

## البنية

```
المستخدم → Coolify Proxy (HTTPS) → app:8080
app / queue / scheduler → redis
app / queue / scheduler → MySQL (خارجي)
app / queue → storage-public, storage-private (volumes)
```
