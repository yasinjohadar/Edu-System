<?php

namespace Database\Seeders;

use App\Models\SmtpSetting;
use Illuminate\Database\Seeder;

class SmtpSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // إعدادات SMTP الافتراضية لـ Gmail
        SmtpSetting::create([
            'name' => 'Gmail SMTP',
            'host' => 'smtp.gmail.com',
            'port' => 587,
            'username' => 'your-email@gmail.com',
            'password' => encrypt('your-app-password'),
            'encryption' => 'tls',
            'from_address' => 'noreply@school.com',
            'from_name' => 'نظام إدارة التعليم',
            'is_default' => true,
            'is_active' => false,
        ]);

        // إعدادات SMTP لـ Outlook
        SmtpSetting::create([
            'name' => 'Outlook SMTP',
            'host' => 'smtp.office365.com',
            'port' => 587,
            'username' => 'your-email@outlook.com',
            'password' => encrypt('your-password'),
            'encryption' => 'tls',
            'from_address' => 'noreply@school.com',
            'from_name' => 'نظام إدارة التعليم',
            'is_default' => false,
            'is_active' => false,
        ]);

        // إعدادات SMTP لـ SendGrid
        SmtpSetting::create([
            'name' => 'SendGrid SMTP',
            'host' => 'smtp.sendgrid.net',
            'port' => 587,
            'username' => 'apikey',
            'password' => encrypt('your-sendgrid-api-key'),
            'encryption' => 'tls',
            'from_address' => 'noreply@school.com',
            'from_name' => 'نظام إدارة التعليم',
            'is_default' => false,
            'is_active' => false,
        ]);
    }
}
