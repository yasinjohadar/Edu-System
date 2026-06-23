# تتبع تقدم خطة التكامل

> المرجع: [INTEGRATION_ROADMAP_AR.md](./INTEGRATION_ROADMAP_AR.md)  
> **حدّث هذا الملف عند إنجاز كل مهمة.**

---

## المرحلة 0 — تثبيت الأساس

| # | المهمة | الحالة |
|---|--------|--------|
| 0.1 | اعتماد `INTEGRATION_ROADMAP_AR.md` | ✅ |
| 0.2 | إنشاء `INTEGRATION_PROGRESS.md` | ✅ |
| 0.3 | إصلاح `fee-types/show` أو إزالة المسار | ✅ |
| 0.4 | توثيق أوامر التشغيل في README | ✅ |
| 0.5 | التحقق من migrate --seed على بيئة نظيفة | ✅ (migrations ran؛ TransportSeeder يُختبر منفصلاً) |

---

## المرحلة 1 — إصلاح CRUD المكسور

### 1أ — النقل

| المتحكم | store | update | destroy | Seeder | اختبار يدوي |
|---------|-------|--------|---------|--------|-------------|
| BusRouteController | ✅ | ✅ | ✅ | ✅ | ⬜ |
| BusStopController | ✅ | ✅ | ✅ | ✅ | ⬜ |
| DriverController | ✅ | ✅ | ✅ | ✅ | ⬜ |
| SupervisorController | ✅ | ✅ | ✅ | ✅ | ⬜ |
| StudentTransportController | ✅ | ✅ | ✅ | ✅ | ⬜ |

**Views:** index/create/edit/show لجميع وحدات النقل (5 وحدات) — ✅

### 1ب — السكن

| المتحكم | store | update | destroy | Seeder | اختبار يدوي |
|---------|-------|--------|---------|--------|-------------|
| HostelController | ⬜ | ⬜ | ⬜ | ⬜ | ⬜ |
| RoomController | ⬜ | ⬜ | ⬜ | ⬜ | ⬜ |
| StudentAccommodationController | ⬜ | ⬜ | ⬜ | ⬜ | ⬜ |
| VisitorController | ⬜ | ⬜ | ⬜ | ⬜ | ⬜ |

### 1ج — الشهادات

| المتحكم | store | update | destroy | Seeder | اختبار يدوي |
|---------|-------|--------|---------|--------|-------------|
| CertificateTemplateController | ✅ | ✅ | ✅ | ✅ | ⬜ |
| CertificateController | ✅ | ✅ | ✅ | ✅ | ⬜ |

**Views:** index/create/edit/show لقوالب الشهادات والشهادات — ✅

### 1د — الخريجون (الفروع)

| المتحكم | store | update | destroy | Seeder | اختبار يدوي |
|---------|-------|--------|---------|--------|-------------|
| AlumniController | ✅ | ✅ | ✅ | ✅ | ⬜ |
| AlumniEventController | ✅ | ✅ | ✅ | ✅ | ⬜ |
| JobPostingController | ✅ | ✅ | ✅ | ✅ | ⬜ |
| AlumniDonationController | ✅ | ✅ | ✅ | ✅ | ⬜ |

**Views:** index/create/edit/show لجميع وحدات الخريجين (4 وحدات) — ✅

---

## المرحلة 2 — الصلاحيات

| # | المهمة | الحالة |
|---|--------|--------|
| 2.1 | توسيع PermissionSeeder (`config/permissions.php` ~275 صلاحية) | ✅ |
| 2.2 | middleware على كل Admin controller (`AuthorizesAdminResource`) | ✅ |
| 2.3 | @can في القائمة الجانبية (`admin-menu` + `AdminMenu::filterItems`) | ✅ |
| 2.4 | مراجعة RoleSeeder (teacher/accountant/librarian/staff/student/parent) | ✅ |
| 2.5 | صلاحيات بوابة الطالب وولي الأمر (`role_or_permission`) | ✅ |

**ملاحظات:** `grade-*` لسجلات الدرجات؛ `grade-level-*` للمراحل التعليمية. `@can` في views النقل/الشهادات/الخريجين/السكن.

---

## المرحلة 3 — الإشعارات

| # | المهمة | الحالة |
|---|--------|--------|
| 3.1 | notification_preferences | ⬜ |
| 3.2 | جرس إشعارات في layouts | ⬜ |
| 3.3 | ربط 5 أحداث على الأقل | ⬜ |
| 3.4 | ربط SMTP ديناميكي | ⬜ |
| 3.5 | Queue للإرسال | ⬜ |

---

## المرحلة 4 — ولي الأمر

| # | المهمة | الحالة |
|---|--------|--------|
| 4.1 | Dashboard بإحصائيات حقيقية | ⬜ |
| 4.2 | حضور / درجات / جدول | ⬜ |
| 4.3 | فواتير / مكتبة | ⬜ |
| 4.4 | Policy أبناء فقط | ⬜ |

---

## المراحل 5–9

راجع [INTEGRATION_ROADMAP_AR.md](./INTEGRATION_ROADMAP_AR.md) — تُفعَّل checkboxes هنا عند البدء بكل مرحلة.

---

## سجل التغييرات

| التاريخ | ما تم |
|---------|--------|
| 2026-06 | إنشاء الخطة وملف التتبع |
| 2026-06-23 | المرحلة 1د (الخريجون) — CRUD + views + AlumniSeeder |
| 2026-06-23 | المرحلة 2 (الصلاحيات) — config/permissions، middleware، القائمة، الأدوار، بوابات الطالب/ولي الأمر |
