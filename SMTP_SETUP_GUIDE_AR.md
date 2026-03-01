# دليل إعداد نظام SMTP

## نظرة عامة

تم إنشاء نظام إدارة SMTP كامل للنظام التعليمي. يتيح هذا النظام للمسؤولين إدارة عدة إعدادات SMTP واختبار الاتصال قبل الاستخدام.

## الملفات المنشأة

### 1. قاعدة البيانات
- **Migration:** [`database/migrations/2025_12_14_160000_create_smtp_settings_table.php`](database/migrations/2025_12_14_160000_create_smtp_settings_table.php:1)
  - جدول `smtp_settings` لتخزين إعدادات SMTP
  - الحقول: name, host, port, username, password, encryption, from_address, from_name, is_default, is_active

- **Model:** [`app/Models/SmtpSetting.php`](app/Models/SmtpSetting.php:1)
  - نموذج Eloquent للتعامل مع إعدادات SMTP
  - طرق مساعدة: `setAsDefault()`, `getDefault()`, `testConnection()`

- **Seeder:** [`database/seeders/SmtpSettingSeeder.php`](database/seeders/SmtpSettingSeeder.php:1)
  - بيانات تجريبية لإعدادات SMTP (Gmail, Outlook, SendGrid)

### 2. المتحكمات
- **Controller:** [`app/Http/Controllers/Admin/SmtpSettingController.php`](app/Http/Controllers/Admin/SmtpSettingController.php:1)
  - إدارة كاملة لإعدادات SMTP
  - الطرق:
    - `index()` - عرض قائمة الإعدادات
    - `create()` - نموذج إنشاء جديد
    - `store()` - حفظ إعدادات جديدة
    - `edit()` - نموذج التعديل
    - `update()` - تحديث الإعدادات
    - `destroy()` - حذف الإعدادات
    - `testConnection()` - اختبار الاتصال (AJAX)
    - `setDefault()` - تعيين كافتراضي
    - `toggleActive()` - تبديل الحالة

### 3. الواجهات (Views)
- **Index:** [`resources/views/admin/smtp-settings/index.blade.php`](resources/views/admin/smtp-settings/index.blade.php:1)
  - قائمة بجميع إعدادات SMTP
  - عرض الحالة والافتراضي
  - أزرار الإجراءات (تعديل، حذف، تعيين افتراضي، تفعيل/تعطيل)

- **Create:** [`resources/views/admin/smtp-settings/create.blade.php`](resources/views/admin/smtp-settings/create.blade.php:1)
  - نموذج إنشاء إعدادات SMTP جديدة
  - جميع الحقول المطلوبة مع التحقق
  - زر "اختبار الاتصال" قبل الحفظ

- **Edit:** [`resources/views/admin/smtp-settings/edit.blade.php`](resources/views/admin/smtp-settings/edit.blade.php:1)
  - نموذج تعديل إعدادات SMTP موجودة
  - الحفاظ على كلمة المرور الحالية إذا لم يتم تغييرها
  - زر "اختبار الاتصال" قبل الحفظ

### 4. المسارات (Routes)
تمت إضافة المسارات التالية في [`routes/admin.php`](routes/admin.php:1):
```php
Route::resource('smtp-settings', SmtpSettingController::class);
Route::post('smtp-settings/test-connection', [SmtpSettingController::class, 'testConnection'])->name('smtp-settings.test-connection');
Route::post('smtp-settings/{id}/set-default', [SmtpSettingController::class, 'setDefault'])->name('smtp-settings.set-default');
Route::post('smtp-settings/{id}/toggle-active', [SmtpSettingController::class, 'toggleActive'])->name('smtp-settings.toggle-active');
```

### 5. القائمة الجانبية
تمت إضافة رابط "إعدادات SMTP" في القائمة الجانبية في [`resources/views/admin/layouts/main-sidebar.blade.php`](resources/views/admin/layouts/main-sidebar.blade.php:1)
- الموقع: تحت قسم "الإعدادات"
- الرابط: `/admin/smtp-settings`

## الميزات

### 1. إدارة إعدادات متعددة
- إنشاء وحذف إعدادات SMTP متعددة
- تعيين إعداد واحد كافتراضي
- تفعيل/تعطيل الإعدادات دون حذفها

### 2. اختبار الاتصال
- زر "اختبار الاتصال" في صفحات الإنشاء والتعديل
- إرسال بريد إلكتروني اختباري للتحقق من الإعدادات
- عرض النتيجة في نافذة منبثقة (Modal)
- دعم AJAX بدون إعادة تحميل الصفحة

### 3. الأمان
- تشفير كلمات المرور باستخدام `encrypt()`
- منع حذف الإعداد الافتراضي
- التحقق من صحة البيانات
- رسائل خطأ باللغة العربية

### 4. واجهة المستخدم
- تصميم متوافق مع نظام التعليم
- جميع النصوص باللغة العربية
- رسائل تنبيه واضحة
- أزرار أيقونات واضحة
- دعم RTL (من اليمين إلى اليسار)

## كيفية الاستخدام

### 1. الوصول إلى الصفحة
- سجل الدخول كمدير
- اذهب إلى القائمة الجانبية
- اختر "الإعدادات" → "إعدادات SMTP"

### 2. إنشاء إعدادات SMTP جديدة
1. انقر على "إضافة إعدادات جديدة"
2. املأ النموذج:
   - **الاسم:** اسم تعريفي (مثل: Gmail SMTP)
   - **المضيف (Host):** عنوان خادم SMTP (مثل: smtp.gmail.com)
   - **المنفذ (Port):** منفذ SMTP (عادة 587 لـ TLS أو 465 لـ SSL)
   - **اسم المستخدم:** اسم مستخدم SMTP (عادة البريد الإلكتروني)
   - **كلمة المرور:** كلمة مرور حساب SMTP
   - **التشفير:** نوع التشفير (TLS، SSL، أو بدون)
   - **عنوان المرسل:** البريد الإلكتروني للمرسل
   - **اسم المرسل:** الاسم الذي سيظهر في الرسائل
   - **إعدادات افتراضية:** عيّن كافتراضي إذا أردت
   - **نشط:** فعّل الاستخدام
3. انقر "اختبار الاتصال" للتحقق من الإعدادات
4. إذا نجح الاختبار، انقر "حفظ الإعدادات"

### 3. تعديل إعدادات SMTP
1. من قائمة الإعدادات، انقر على زر التعديل
2. عدّل الحقول المطلوبة
3. اترك حقل كلمة المرور فارغاً للحفاظ على الحالية
4. انقر "اختبار الاتصال" للتحقق
5. انقر "حفظ التغييرات"

### 4. تعيين إعداد كافتراضي
1. من قائمة الإعدادات، انقر على زر النجمة
2. سيتم تعيين هذا الإعداد كافتراضي
3. سيتم تعطيل الإعداد الافتراضي السابق تلقائياً

### 5. تفعيل/تعطيل إعدادات SMTP
1. من قائمة الإعدادات، انقر على زر التبديل
2. سيتم تغيير الحالة بين نشط وغير نشط
3. يمكن تفعيل/تعطيل الإعدادات دون حذفها

### 6. حذف إعدادات SMTP
1. من قائمة الإعدادات، انقر على زر الحذف
2. تأكد من الحذف في رسالة التأكيد
3. ملاحظة: لا يمكن حذف الإعداد الافتراضي

## أمثلة على إعدادات SMTP الشائعة

### Gmail SMTP
```
المضيف: smtp.gmail.com
المنفذ: 587
التشفير: TLS
اسم المستخدم: your-email@gmail.com
كلمة المرور: كلمة مرور التطبيق (ليست كلمة مرور حساب Gmail)
```

**ملاحظة:** يجب تفعيل "الوصول للتطبيقات الأقل أماناً" في حساب Google وإنشاء كلمة مرور التطبيق.

### Outlook SMTP
```
المضيف: smtp.office365.com
المنفذ: 587
التشفير: TLS
اسم المستخدم: your-email@outlook.com
كلمة المرور: كلمة مرور حساب Outlook
```

### SendGrid SMTP
```
المضيف: smtp.sendgrid.net
المنفذ: 587
التشفير: TLS
اسم المستخدم: apikey
كلمة المرور: مفتاح API الخاص بـ SendGrid
```

### Mailgun SMTP
```
المضيف: smtp.mailgun.org
المنفذ: 587
التشفير: TLS
اسم المستخدم: postmaster@your-domain.com
كلمة المرور: مفتاح API الخاص بـ Mailgun
```

## استكشاف الأخطاء

### مشكلة: فشل الاتصال
**الحلول المحتملة:**
1. تحقق من المضيف والمنفذ
2. تأكد من صحة اسم المستخدم وكلمة المرور
3. تحقق من نوع التشفير
4. تأكد من أن المزود يسمح بالاتصال من عنوان IP الخاص بك
5. تحقق من جدار الحماية (Firewall)

### مشكلة: خطأ المصادقة
**الحلول المحتملة:**
1. تأكد من صحة كلمة المرور
2. بالنسبة لـ Gmail، استخدم كلمة مرور التطبيق وليس كلمة مرور الحساب
3. تحقق من أن حساب البريد الإلكتروني نشط
4. تأكد من تفعيل SMTP في المزود

### مشكلة: مهلة الاتصال
**الحلول المحتملة:**
1. تحقق من اتصال الإنترنت
2. جرب منفذ مختلف (587 أو 465)
3. تحقق من إعدادات جدار الحماية
4. اتصل بمزود الخدمة للتأكد من حالة الخادم

## الخطوات التالية

### 1. دمج مع نظام الإشعارات
يمكنك الآن استخدام إعدادات SMTP في نظام الإشعارات:
```php
$smtp = SmtpSetting::getDefault();
if ($smtp) {
    // تكوين Laravel Mail لاستخدام هذه الإعدادات
    config([
        'mail.mailers.smtp' => [
            'transport' => 'smtp',
            'host' => $smtp->host,
            'port' => $smtp->port,
            'encryption' => $smtp->encryption,
            'username' => $smtp->username,
            'password' => decrypt($smtp->password),
            'from' => [
                'address' => $smtp->from_address,
                'name' => $smtp->from_name,
            ],
        ],
    ]);
}
```

### 2. إضافة المزيد من الميزات
- سجل نشاط إعدادات SMTP (من قام بالتعديل ومتى)
- إمكانية نسخ الإعدادات
- تصدير/استيراد الإعدادات
- إعدادات متعددة حسب البيئة (تطوير، إنتاج)

### 3. تحسينات الأمان
- إضافة 2FA للوصول إلى إعدادات SMTP
- تسجيل جميع محاولات الاتصال
- قيود على عدد محاولات الاختبار

## الخلاصة

تم إنشاء نظام SMTP كامل ومتكامل مع:
- ✅ قاعدة بيانات ونموذج
- ✅ متحكم مع جميع العمليات CRUD
- ✅ واجهات مستخدم باللغة العربية
- ✅ اختبار الاتصال في الوقت الفعلي
- ✅ دعم إعدادات متعددة
- ✅ رابط في القائمة الجانبية
- ✅ بيانات تجريبية
- ✅ رسائل خطأ وتأكيد باللغة العربية

النظام جاهز للاستخدام ويمكن الوصول إليه من:
`/admin/smtp-settings`

---

**تاريخ الإنشاء:** يناير 2026  
**الإصدار:** 1.0
