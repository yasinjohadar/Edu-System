# خطة التكامل التدريجي — نظام Edu-System

> **تاريخ الإعداد:** يونيو 2026  
> **الغرض:** خارطة طريق مستقلة للانتقال من «وحدات موجودة جزئياً» إلى «نظام متكامل وقابل للإنتاج».  
> **مبدأ التنفيذ:** مرحلة تلو الأخرى — لا ننتقل للمرحلة التالية قبل إغلاق معايير القبول الحالية.

---

## 1. الوضع الحالي (خط الأساس)

| المحور | التقدير | ملاحظة مختصرة |
|--------|---------|----------------|
| النواة الأكاديمية | ~85% | طلاب، معلمون، حضور، جدول، درجات |
| النظام المالي | ~80% | فواتير/مدفوعات/حسابات — بدون دفع إلكتروني |
| المكتبة / الواجبات / المحاضرات | ~75% | Backend جيد؛ واجهة قديمة |
| الاختبارات وبنك الأسئلة | ~70% | مسارات admin + student موجودة |
| التقارير | ~55% | عرض وفلاتر موجودة؛ تصدير PDF غير فعلي |
| الأحداث والتقويم | ~65% | `EventController` يعمل؛ تقويم أكاديمي موجود |
| الشهادات / النقل / السكن / خريجون | ~25–40% | قوائم وواجهات؛ **حفظ CRUD غير مُنفَّذ** في أغلب المتحكمات |
| بوابة ولي الأمر | ~10% | لوحة واحدة؛ إحصائيات = 0 |
| الإشعارات / المراسلة / API | ~0–5% | غير موجودة |
| واجهة الإدارة الموحّدة (Ajax) | ~27% | 15 من ~56 صفحة index |
| الاختبارات الآلية | ~5% | Breeze auth فقط |

**ملفات توثيق قديمة (لا تعتمد عليها وحدها):**  
`FEATURES_SUMMARY.md`, `FEATURES_ANALYSIS_AR.md`, `QUIZ_SYSTEM_README.md`, `SYSTEM_IMPLEMENTATION_COMPLETE.md` — تحتاج تحديث بعد كل مرحلة.

---

## 2. مبادئ التنفيذ

1. **إصلاح المكسور قبل البناء الجديد** — وحدات بها `// Implementation` أولوية قصوى.
2. **كل مهمة = فرع Git + اختبار يدوي موثّق** — checklist في نهاية كل مرحلة.
3. **الصلاحيات مع كل وحدة** — لا نُضيف شاشة بدون `permission` في Seeder + middleware.
4. **الإشعارات تُبنى مبكراً** — لأنها تربط الواجبات والدرجات والمالية لاحقاً.
5. **توحيد الواجهة تدريجياً** — مع كل وحدة نُحدّث index/create/edit إن لزم.

---

## 3. نظرة عامة على المراحل

```
المرحلة 0 ──► تثبيت الأساس (أسبوع 1)
     │
المرحلة 1 ──► إصلاح الوحدات المكسورة: نقل، سكن، شهادات، خريجون (أسبوع 2–4)
     │
المرحلة 2 ──► الأمان والصلاحيات + إغلاق الثغرات السريعة (أسبوع 4–5)
     │
المرحلة 3 ──► نظام الإشعارات + ربط SMTP (أسبوع 5–7)
     │
المرحلة 4 ──► بوابة ولي الأمر الكاملة (أسبوع 7–10)
     │
المرحلة 5 ──► التقارير: تصدير PDF/Excel فعلي + جدولة (أسبوع 10–12)
     │
المرحلة 6 ──► بوابة المعلم (أسبوع 12–14)
     │
المرحلة 7 ──► توحيد واجهة الإدارة لبقية الوحدات (أسبوع 14–18)
     │
المرحلة 8 ──► API (Sanctum) + دفع إلكتروني (أسبوع 18–22)
     │
المرحلة 9 ──► المراسلة الداخلية + جودة وإنتاج (أسبوع 22–26)
```

**المدة التقديرية الكلية:** 5–6 أشهر بوتيرة جزئية (يمكن ضغط المراحل 0–3 في شهرين إن كان الفريق متفرغاً).

---

## 4. المرحلة 0 — تثبيت الأساس

**الهدف:** بيئة عمل موحّدة وقائمة مهام واضحة قبل أي تطوير كبير.

### المهام

| # | المهمة | الملفات / المخرجات |
|---|--------|---------------------|
| 0.1 | اعتماد هذا الملف كمرجع رسمي | `docs/INTEGRATION_ROADMAP_AR.md` |
| 0.2 | إنشاء ملف تتبع التقدم | `docs/INTEGRATION_PROGRESS.md` (checkbox لكل مهمة) |
| 0.3 | إصلاح `fee-types/show` أو إزالة المسار | `FeeTypeController`, view أو حذف `show` |
| 0.4 | توثيق أوامر التشغيل المحلية في README المشروع | `README.md` (قسم Edu-System فقط) |
| 0.5 | التحقق من `php artisan migrate --seed` على بيئة نظيفة | سجل في `INTEGRATION_PROGRESS.md` |

### معايير القبول

- [ ] المشروع يعمل محلياً بدون أخطاء migration
- [ ] لا مسارات تشير لـ views غير موجودة (فحص سريع)
- [ ] ملف تتبع التقدم موجود ومُحدَّث

**المدة:** 2–3 أيام

---

## 5. المرحلة 1 — إصلاح الوحدات المكسورة (CRUD)

**الهدف:** كل عنصر في القائمة الجانبية يحفظ ويعدّل ويحذف فعلياً.

### 1أ — النقل (`admin/transport/*`)

| المتحكم | الحالة الحالية |
|---------|----------------|
| `BusRouteController` | stub |
| `BusStopController` | stub |
| `DriverController` | stub |
| `SupervisorController` | stub |
| `StudentTransportController` | stub |

**المهام:**
- تنفيذ `store`, `update`, `destroy` مع validation
- ربط `BusStop` بـ `BusRoute` حيث يلزم
- Form Requests منفصلة (اختياري لكن مُفضّل)
- رسائل flash عربية موحّدة
- اختبار يدوي: إنشاء مسار → محطة → سائق → ربط طالب

**المدة:** 5–7 أيام

### 1ب — السكن (`admin/hostel/*`)

| المتحكم | الحالة |
|---------|--------|
| `HostelController` | stub |
| `RoomController` | stub |
| `StudentAccommodationController` | stub |
| `VisitorController` | stub |

**المهام:** نفس نمط 1أ + التحقق من سعة الغرفة عند الإسكان.

**المدة:** 5–7 أيام

### 1ج — الشهادات (`admin/certificates/*`)

| المتحكم | الحالة |
|---------|--------|
| `CertificateController` | stub |
| `CertificateTemplateController` | stub |

**المهام:**
- CRUD كامل للقوالب والشهادات
- **المرحلة 1:** حفظ بيانات الشهادة + عرض HTML للطباعة
- **تأجيل لمرحلة 5:** PDF عبر DomPDF

**المدة:** 4–6 أيام

### 1د — الخريجون (إكمال الفروع)

| المتحكم | الحالة |
|---------|--------|
| `AlumniController` | مكتمل |
| `AlumniEventController` | stub |
| `AlumniDonationController` | stub |
| `JobPostingController` | stub |

**المدة:** 3–5 أيام

### معايير القبول — المرحلة 1

- [ ] لا يوجد `// Implementation` في المتحكمات المذكورة
- [ ] Seeders تجريبية لكل وحدة (على الأقل 3–5 سجلات)
- [ ] كل وحدة: index → create → store → edit → update → destroy يعمل

---

## 6. المرحلة 2 — الأمان والصلاحيات

**الهدف:** كل مدخل في القائمة محمي بصلاحية؛ الأدوار تعكس الواقع.

### المهام

| # | المهمة | التفاصيل |
|---|--------|----------|
| 2.1 | توسيع `PermissionSeeder` | exams, questions, reports, events, certificates, transport, hostel, alumni, teacher-grade-class-section-subject |
| 2.2 | middleware `permission` على كل Admin controller | استبدال `auth` فقط حيث ينطبق |
| 2.3 | `@can` في `config/admin-menu.php` أو partial القائمة | إخفاء الروابط غير المسموحة |
| 2.4 | مراجعة `RoleSeeder` | admin / teacher / accountant / librarian أدوار منطقية |
| 2.5 | صلاحيات بوابة الطالب | التحقق من `role:student` + permissions حيث لزم |

### معايير القبول

- [ ] مستخدم بدون صلاحية لا يرى الرابط ولا يصل للـ URL (403)
- [ ] `PermissionSeeder` يشمل كل الوحدات في `routes/admin.php`

**المدة:** 5–7 أيام

---

## 7. المرحلة 3 — نظام الإشعارات

**الهدف:** إشعارات داخل التطبيق + بريد عبر SMTP الموجود.

### البنية المقترحة

```
app/Notifications/
  AssignmentGradedNotification.php
  AssignmentDeadlineNotification.php
  GradePublishedNotification.php
  InvoiceCreatedNotification.php
  PaymentReceivedNotification.php
  AttendanceAlertNotification.php
  ExamPublishedNotification.php

app/Models/NotificationPreference.php
database/migrations/..._notification_preferences_table.php

resources/views/admin/partials/notification-bell.blade.php
resources/views/student/partials/notification-bell.blade.php
```

### المهام

| # | المهمة |
|---|--------|
| 3.1 | جدول `notification_preferences` + واجهة إعدادات المستخدم |
| 3.2 | جرس إشعارات في layout الإدارة والطالب |
| 3.3 | ربط الأحداث: تصحيح واجب، إنشاء فاتورة، تسجيل دفعة، نشر اختبار |
| 3.4 | استخدام `SmtpSetting` الافتراضي في `Mail` config ديناميكياً (إن أمكن) |
| 3.5 | Queue للإرسال (`database` queue كبداية) |
| 3.6 | إزالة `// TODO: إرسال إشعار` من `AssignmentSubmissionController` |

### معايير القبول

- [ ] إشعار يظهر في الواجهة بعد حدث حقيقي
- [ ] بريد يُرسل عند تفعيل القناة (اختبار SMTP)
- [ ] تفضيلات المستخدم تحترم (إيقاف بريد / إبقاء داخل التطبيق)

**المدة:** 10–14 يوم

---

## 8. المرحلة 4 — بوابة ولي الأمر

**الهدف:** ولي الأمر يرى بيانات أبنائه الفعلية — ليس أصفاراً.

### المسارات المطلوبة (`routes/parent.php`)

```
GET  /parent/dashboard
GET  /parent/children/{student}/attendance
GET  /parent/children/{student}/grades
GET  /parent/children/{student}/schedule
GET  /parent/children/{student}/invoices
GET  /parent/children/{student}/invoices/{invoice}
GET  /parent/children/{student}/library
GET  /parent/children/{student}/assignments
GET  /parent/children/{student}/exams
```

### المهام

| # | المهمة |
|---|--------|
| 4.1 | `Parent\DashboardController` — إحصائيات حقيقية (حضور، معدل، واجبات معلقة) |
| 4.2 | Controllers فرعية لكل قسم (أو controller واحد منظم) |
| 4.3 | Policy: ولي الأمر يرى **أبناءه فقط** (`parent_id` / pivot) |
| 4.4 | Views بنمط student layout أو نمط موحّد جديد |
| 4.5 | ربط الصلاحيات من `PermissionSeeder` (parent-*) |

### معايير القبول

- [ ] ولي أمر مرتبط بطالب يرى حضوره ودرجاته وفواتيره
- [ ] ولي أمر لا يصل لبيانات طالب غير مرتبط (403)
- [ ] الإحصائيات في Dashboard ≠ 0 عند وجود بيانات

**المدة:** 2–3 أسابيع

---

## 9. المرحلة 5 — التقارير والتصدير

**الهدف:** `ReportController::export` ينتج ملفاً حقيقياً.

### المهام

| # | المهمة |
|---|--------|
| 5.1 | إضافة `barryvdh/laravel-dompdf` (أو بديل) |
| 5.2 | `app/Services/ReportExportService.php` |
| 5.3 | قوالب `resources/views/pdf/reports/*.blade.php` |
| 5.4 | ربط `export()` بتوليد PDF/Excel حسب `format` |
| 5.5 | تخزين الملف في `storage/app/reports/` + رابط تنزيل |
| 5.6 | (اختياري) Job مجدول + `reports:send-scheduled` |

### معايير القبول

- [ ] تقرير حضور → تنزيل PDF يفتح ويعرض بيانات صحيحة
- [ ] تقرير مالي → Excel أو PDF حسب الاختيار
- [ ] سجل `reports` يعكس `file_path` فعلي

**المدة:** 10–14 يوم

---

## 10. المرحلة 6 — بوابة المعلم

**الهدف:** المعلم يعمل من مساحة خاصة دون صلاحيات إدارية كاملة.

### النطاق الأولي (MVP)

- لوحة تحكم: صفوفه، حصص اليوم
- حضور صفوفه فقط
- واجباته: إنشاء، تصحيح
- درجات مواده
- محاضراته الإلكترونية

### المهام

| # | المهمة |
|---|--------|
| 6.1 | `routes/teacher.php` + middleware `role:teacher` |
| 6.2 | `app/Http/Controllers/Teacher/*` |
| 6.3 | `resources/views/teacher/layouts/master.blade.php` |
| 6.4 | Scopes على الاستعلامات (`teacher_id`) |
| 6.5 | دور `teacher` في `RoleSeeder` بصلاحيات محدودة |

**المدة:** 2–3 أسابيع

---

## 11. المرحلة 7 — توحيد واجهة الإدارة

**الهدف:** نفس تجربة المستخدمين/الفواتير على بقية الوحدات.

### أولوية التحديث (بالترتيب)

1. **المكتبة** — books, book-categories, book-borrowings, fines  
2. **الواجبات** — assignments + submissions  
3. **المحاضرات** — online-lectures, materials, attendance  
4. **الاختبارات** — exams, questions, results (ملاحظة: views تحت `admin/exams/` — توحيد المسار)  
5. **التقارير** — reports/*  
6. **الأحداث والتقويم** — events, calendar, academic-calendars  
7. **النقل / السكن / الخريجون / الشهادات** — بعد إكمال المرحلة 1  

### النمط المعياري (مرجع)

- `admin-page-header` + `admin-page-card`
- `AdminTables.initAjaxTable` + partials `*-table-body/footer`
- `admin-form` في create/edit
- `@push('scripts')` وليس `@section('script')`

### معايير القبول

- [ ] كل index في القائمة يستخدم Ajax للفلاتر
- [ ] الوضع الليلي متسق عبر `admin-pages.css`

**المدة:** 3–4 أسابيع (يمكن بالتوازي مع مراحل أخرى)

---

## 12. المرحلة 8 — API ودفع إلكتروني

### 8أ — REST API

| # | المهمة |
|---|--------|
| 8.1 | `laravel/sanctum` + `routes/api.php` |
| 8.2 | Auth: login / logout / me |
| 8.3 | موارد: student profile, attendance, grades, schedule, invoices, assignments, exams |
| 8.4 | موارد ولي الأمر: children list + بيانات كل ابن |
| 8.5 | توثيق Postman collection أو Scribe |

### 8ب — الدفع الإلكتروني

| # | المهمة |
|---|--------|
| 8.6 | اختيار بوابة (Moyasar / Stripe حسب السوق) |
| 8.7 | `PaymentGatewayService` + webhook |
| 8.8 | صفحة دفع لولي الأمر/طالب مرتبطة بفاتورة |
| 8.9 | تحديث `Invoice` + `FinancialAccount` بعد الدفع الناجح |

**المدة:** 3–4 أسابيع

---

## 13. المرحلة 9 — المراسلة والإنتاج

### المراسلة

```
messages, message_threads, message_participants
Parent/Teacher/Admin MessageController
```

- مراسلة فردية (ولي أمر ↔ معلم)
- إعلانات إدارية (broadcast)

### الجودة والإنتاج

| # | المهمة |
|---|--------|
| 9.1 | Feature tests للمسارات الحرجة (مالية، حضور، ولي أمر) |
| 9.2 | Seeders للاختبارات والأحداث |
| 9.3 | تحديث `FEATURES_SUMMARY.md` وملفات التحليل القديمة |
| 9.4 | (اختياري) 2FA للإدارة |
| 9.5 | (اختياري) Audit log للعمليات المالية |

**المدة:** 3–4 أسابيع

---

## 14. ترتيب البدء الموصى به (ملخص تنفيذي)

إذا أردت **أقل مخاطرة وأسرع قيمة للمستخدم:**

```
1. المرحلة 0  (تثبيت)
2. المرحلة 1  (إصلاح CRUD المكسور)     ← أولوية قصوى
3. المرحلة 2  (صلاحيات)
4. المرحلة 3  (إشعارات)
5. المرحلة 4  (ولي الأمر)
6. المرحلة 5  (تقارير PDF)
7. المراحل 6–9 حسب احتياج المدرسة
```

يمكن تنفيذ **المرحلة 7 (UI)** بالتوازي مع 3–5 إذا كان هناك مطور واجهات منفصل.

---

## 15. قالب مهمة لكل فرع عمل

عند البدء بأي مهمة، انسخ هذا القالب في `docs/INTEGRATION_PROGRESS.md`:

```markdown
### [معرّف] عنوان المهمة
- **المرحلة:** 
- **الحالة:** ⬜ لم يبدأ | 🔄 جارٍ | ✅ مكتمل
- **الفرع:** `feature/...`
- **الملفات المتوقعة:**
- **معايير القبول:**
  - [ ] ...
- **اختبار يدوي:**
  1. ...
- **ملاحظات:**
```

---

## 16. مؤشرات النجاح (KPIs)

| المؤشر | الهدف بعد المرحلة 4 | الهدف النهائي |
|--------|---------------------|----------------|
| Controllers بدون stub | 100% للوحدات في القائمة | 100% |
| صفحات admin index بـ Ajax | 50% | 100% |
| بوابة ولي الأمر | 6 أقسام فعّالة | كاملة + دفع |
| تغطية اختبارات Feature | — | 30%+ للمسارات الحرجة |
| إشعارات مربوطة بأحداث | 5 أحداث | 10+ أحداث |

---

## 17. المراجع داخل المشروع

| الملف | الاستخدام |
|-------|-----------|
| `config/admin-menu.php` | قائمة الوحدات |
| `routes/admin.php` | مسارات الإدارة |
| `routes/parent.php` | بوابة ولي الأمر (ناقصة) |
| `routes/student.php` | بوابة الطالب (مرجع) |
| `database/seeders/PermissionSeeder.php` | الصلاحيات |
| `public/assets/css/admin-pages.css` | نمط الواجهة الجديد |
| `public/assets/js/admin-tables.js` | Ajax tables |

---

## 18. الخطوة التالية الفورية

**ابدأ بالمرحلة 0 ثم المرحلة 1أ (النقل):**

1. افتح `docs/INTEGRATION_PROGRESS.md` وسجّل البدء.
2. نفّذ `BusRouteController::store` كأول controller stub.
3. اختبر من الواجهة حتى الحفظ يعمل.
4. كرّر على بقية متحكمات النقل.

---

*آخر تحديث: يونيو 2026 — يُراجع بعد إغلاق كل مرحلة.*
