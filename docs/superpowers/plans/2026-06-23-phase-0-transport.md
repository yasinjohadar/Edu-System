# Phase 0 + Transport Module Implementation Plan

> **For agentic workers:** نفّذ المهام بالترتيب؛ حدّث `docs/INTEGRATION_PROGRESS.md` بعد كل مهمة.

**Goal:** إغلاق المرحلة 0 (تثبيت الأساس) ثم إكمال وحدة النقل بالكامل — CRUD فعلي + views + seeder.

**Architecture:** نمط `FeeTypeController` / `admin-form` للنماذج؛ validation داخل المتحكم؛ حذف عبر `delete-modal`؛ منع الحذف عند وجود علاقات.

**Tech Stack:** Laravel 12, Blade, Spatie Permission (لاحقاً في المرحلة 2), admin-pages.css

---

## Task 1: المرحلة 0 — تثبيت الأساس

**Files:**
- Modify: `routes/admin.php` — `fee-types` except show
- Modify: `app/Http/Controllers/Admin/FeeTypeController.php` — إزالة show
- Modify: `README.md` — قسم Edu-System
- Modify: `docs/INTEGRATION_PROGRESS.md`

- [ ] إزالة `show` من fee-types (مسار + controller)
- [ ] إضافة قسم تشغيل المشروع في README
- [ ] تشغيل `php artisan migrate:status` للتحقق

---

## Task 2: BusRoute — Controller

**Files:**
- Modify: `app/Http/Controllers/Admin/BusRouteController.php`

**Validation rules:**
- `route_name` required max:255
- `route_number` required unique
- `start_time`, `end_time` required (H:i)
- `end_time` after start_time
- `fee` numeric min:0
- `is_active` boolean via select

**destroy:** رفض إذا `studentTransports()->exists()`

---

## Task 3: BusRoute — Views

**Files:**
- Create: `resources/views/admin/pages/transport/bus-routes/create.blade.php`
- Create: `resources/views/admin/pages/transport/bus-routes/edit.blade.php`
- Create: `resources/views/admin/pages/transport/bus-routes/show.blade.php`
- Modify: `resources/views/admin/pages/transport/bus-routes/index.blade.php` — flash + delete

---

## Task 4: BusStop — Controller + Views

**Files:**
- Modify: `app/Http/Controllers/Admin/BusStopController.php`
- Create: create/edit/show blades
- Modify: index — flash + delete

**Validation:** `route_id` exists; `stop_name` required; `order` integer min:0

---

## Task 5: Driver — Controller + Views

**Files:**
- Modify: `app/Http/Controllers/Admin/DriverController.php`
- Create: create/edit/show blades
- Modify: index

**Validation:** `driver_code`, `license_number` unique; `status` in active,inactive,on_leave

**destroy:** رفض إذا `studentTransports()->exists()`

---

## Task 6: Supervisor — Controller + Views

**Files:**
- Modify: `app/Http/Controllers/Admin/SupervisorController.php`
- Create: create/edit/show blades
- Modify: index

---

## Task 7: StudentTransport — Controller + Views

**Files:**
- Modify: `app/Http/Controllers/Admin/StudentTransportController.php`
- Create: create/edit/show blades
- Modify: index — eager load `stop`

**Validation:**
- `student_id`, `route_id`, `start_date` required
- `stop_id` nullable but must belong to `route_id`
- `end_date` after_or_equal start_date

**create/edit:** JS لفلترة المحطات حسب المسار

---

## Task 8: TransportSeeder

**Files:**
- Create: `database/seeders/TransportSeeder.php`
- Modify: `database/seeders/DatabaseSeeder.php`

---

## Task 9: التحقق

- [ ] `php -l` على كل المتحكمات المعدّلة
- [ ] تحديث `INTEGRATION_PROGRESS.md`

---

## معايير القبول

- [ ] لا `// Implementation` في متحكمات النقل
- [ ] إنشاء مسار → محطة → سائق → مشرف → ربط طالب يعمل من الواجهة
- [ ] المرحلة 0 مغلقة في ملف التتبع
