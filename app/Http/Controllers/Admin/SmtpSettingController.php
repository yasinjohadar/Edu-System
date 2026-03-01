<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SmtpSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SmtpSettingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * عرض قائمة إعدادات SMTP
     */
    public function index()
    {
        $smtpSettings = SmtpSetting::orderBy('is_default', 'desc')->orderBy('created_at', 'desc')->get();
        return view('admin.smtp-settings.index', compact('smtpSettings'));
    }

    /**
     * عرض نموذج إنشاء إعداد SMTP جديد
     */
    public function create()
    {
        return view('admin.smtp-settings.create');
    }

    /**
     * تخزين إعداد SMTP جديد
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'host' => 'required|string|max:255',
            'port' => 'required|integer|min:1|max:65535',
            'username' => 'required|string|max:255',
            'password' => 'required|string',
            'encryption' => 'required|in:tls,ssl,none',
            'from_address' => 'required|email|max:255',
            'from_name' => 'required|string|max:255',
            'is_default' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
        ], [
            'name.required' => 'حقل الاسم مطلوب',
            'host.required' => 'حقل المضيف مطلوب',
            'port.required' => 'حقل المنفذ مطلوب',
            'port.integer' => 'المنفذ يجب أن يكون رقماً',
            'username.required' => 'حقل اسم المستخدم مطلوب',
            'password.required' => 'حقل كلمة المرور مطلوب',
            'encryption.required' => 'حقل التشفير مطلوب',
            'from_address.required' => 'حقل عنوان المرسل مطلوب',
            'from_address.email' => 'عنوان المرسل يجب أن يكون بريداً إلكترونياً صحيحاً',
            'from_name.required' => 'حقل اسم المرسل مطلوب',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $smtpSetting = SmtpSetting::create([
            'name' => $request->name,
            'host' => $request->host,
            'port' => $request->port,
            'username' => $request->username,
            'password' => encrypt($request->password),
            'encryption' => $request->encryption,
            'from_address' => $request->from_address,
            'from_name' => $request->from_name,
            'is_default' => $request->has('is_default'),
            'is_active' => $request->has('is_active'),
        ]);

        if ($smtpSetting->is_default) {
            $smtpSetting->setAsDefault();
        }

        return redirect()->route('admin.smtp-settings.index')
            ->with('success', 'تم إضافة إعدادات SMTP بنجاح');
    }

    /**
     * عرض نموذج تعديل إعداد SMTP
     */
    public function edit($id)
    {
        $smtpSetting = SmtpSetting::findOrFail($id);
        return view('admin.smtp-settings.edit', compact('smtpSetting'));
    }

    /**
     * تحديث إعداد SMTP
     */
    public function update(Request $request, $id)
    {
        $smtpSetting = SmtpSetting::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'host' => 'required|string|max:255',
            'port' => 'required|integer|min:1|max:65535',
            'username' => 'required|string|max:255',
            'password' => 'nullable|string',
            'encryption' => 'required|in:tls,ssl,none',
            'from_address' => 'required|email|max:255',
            'from_name' => 'required|string|max:255',
            'is_default' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
        ], [
            'name.required' => 'حقل الاسم مطلوب',
            'host.required' => 'حقل المضيف مطلوب',
            'port.required' => 'حقل المنفذ مطلوب',
            'port.integer' => 'المنفذ يجب أن يكون رقماً',
            'username.required' => 'حقل اسم المستخدم مطلوب',
            'encryption.required' => 'حقل التشفير مطلوب',
            'from_address.required' => 'حقل عنوان المرسل مطلوب',
            'from_address.email' => 'عنوان المرسل يجب أن يكون بريداً إلكترونياً صحيحاً',
            'from_name.required' => 'حقل اسم المرسل مطلوب',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $smtpSetting->update([
            'name' => $request->name,
            'host' => $request->host,
            'port' => $request->port,
            'username' => $request->username,
            'encryption' => $request->encryption,
            'from_address' => $request->from_address,
            'from_name' => $request->from_name,
            'is_default' => $request->has('is_default'),
            'is_active' => $request->has('is_active'),
        ]);

        if ($request->filled('password')) {
            $smtpSetting->update(['password' => encrypt($request->password)]);
        }

        if ($smtpSetting->is_default) {
            $smtpSetting->setAsDefault();
        }

        return redirect()->route('admin.smtp-settings.index')
            ->with('success', 'تم تحديث إعدادات SMTP بنجاح');
    }

    /**
     * حذف إعداد SMTP
     */
    public function destroy($id)
    {
        $smtpSetting = SmtpSetting::findOrFail($id);

        if ($smtpSetting->is_default) {
            return redirect()->back()
                ->with('error', 'لا يمكن حذف الإعداد الافتراضي');
        }

        $smtpSetting->delete();

        return redirect()->route('admin.smtp-settings.index')
            ->with('success', 'تم حذف إعدادات SMTP بنجاح');
    }

    /**
     * اختبار اتصال SMTP
     */
    public function testConnection(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'host' => 'required|string',
            'port' => 'required|integer',
            'username' => 'required|string',
            'password' => 'required|string',
            'encryption' => 'required|string',
            'from_address' => 'required|email',
            'from_name' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'بيانات غير صحيحة',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $transport = new \Swift_SmtpTransport(
                $request->host,
                $request->port,
                $request->encryption
            );

            $transport->setUsername($request->username);
            $transport->setPassword($request->password);

            $mailer = new \Swift_Mailer($transport);

            $message = (new \Swift_Message())
                ->setSubject('اختبار اتصال SMTP')
                ->setFrom([$request->from_address => $request->from_name])
                ->setTo($request->from_address)
                ->setBody('هذا اختبار اتصال SMTP من نظام إدارة التعليم.');

            $result = $mailer->send($message);

            return response()->json([
                'success' => true,
                'message' => 'تم الاتصال بنجاح! تم إرسال بريد اختبار إلى ' . $request->from_address,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'فشل الاتصال: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * تعيين إعداد SMTP كافتراضي
     */
    public function setDefault($id)
    {
        $smtpSetting = SmtpSetting::findOrFail($id);
        $smtpSetting->setAsDefault();

        return redirect()->route('admin.smtp-settings.index')
            ->with('success', 'تم تعيين إعدادات SMTP كافتراضية بنجاح');
    }

    /**
     * تبديل حالة النشاط
     */
    public function toggleActive($id)
    {
        $smtpSetting = SmtpSetting::findOrFail($id);
        $smtpSetting->update(['is_active' => !$smtpSetting->is_active]);

        $status = $smtpSetting->is_active ? 'تفعيل' : 'تعطيل';

        return redirect()->route('admin.smtp-settings.index')
            ->with('success', 'تم ' . $status . ' إعدادات SMTP بنجاح');
    }
}
