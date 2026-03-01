<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SmtpSetting extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'host',
        'port',
        'username',
        'password',
        'encryption',
        'from_address',
        'from_name',
        'is_default',
        'is_active',
    ];

    protected $casts = [
        'is_default' => 'boolean',
        'is_active' => 'boolean',
    ];

    /**
     * تعيين هذا الإعداد كإعداد افتراضي
     */
    public function setAsDefault(): void
    {
        SmtpSetting::where('id', '!=', $this->id)->update(['is_default' => false]);
        $this->is_default = true;
        $this->save();
    }

    /**
     * الحصول على الإعداد الافتراضي
     */
    public static function getDefault(): ?self
    {
        return self::where('is_default', true)->where('is_active', true)->first();
    }

    /**
     * اختبار الاتصال
     */
    public function testConnection(): array
    {
        try {
            $transport = new \Swift_SmtpTransport(
                $this->host,
                $this->port,
                $this->encryption
            );

            $transport->setUsername($this->username);
            $transport->setPassword($this->password);

            $mailer = new \Swift_Mailer($transport);

            $message = (new \Swift_Message())
                ->setSubject('اختبار اتصال SMTP')
                ->setFrom([$this->from_address => $this->from_name])
                ->setTo($this->from_address)
                ->setBody('هذا اختبار اتصال SMTP من نظام إدارة التعليم.');

            $result = $mailer->send($message);

            return [
                'success' => true,
                'message' => 'تم الاتصال بنجاح! تم إرسال بريد اختبار.',
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'فشل الاتصال: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * الحصول على اسم التشفير بالعربية
     */
    public function getEncryptionNameAttribute(): string
    {
        return match($this->encryption) {
            'tls' => 'TLS',
            'ssl' => 'SSL',
            'none' => 'بدون تشفير',
            default => $this->encryption,
        };
    }
}
