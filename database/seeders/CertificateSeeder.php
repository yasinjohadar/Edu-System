<?php

namespace Database\Seeders;

use App\Models\Certificate;
use App\Models\CertificateTemplate;
use App\Models\Student;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CertificateSeeder extends Seeder
{
    public function run(): void
    {
        $template = CertificateTemplate::updateOrCreate(
            ['name' => 'شهادة إتمام الفصل'],
            [
                'type' => 'completion',
                'html_template' => '<div class="certificate text-center"><h2>شهادة إتمام</h2><p>يُمنح الطالب/ة: {{student_name}}</p><p>بتاريخ: {{issue_date}}</p></div>',
                'fields' => ['student_name', 'issue_date', 'type'],
                'is_active' => true,
            ]
        );

        $student = Student::where('status', 'active')->orderBy('id')->first();

        if (! $student) {
            return;
        }

        Certificate::updateOrCreate(
            ['certificate_number' => 'CERT-DEMO-0001'],
            [
                'template_id' => $template->id,
                'student_id' => $student->id,
                'verification_code' => 'DEMO' . strtoupper(Str::random(8)),
                'type' => 'completion',
                'issue_date' => now(),
                'data' => ['notes' => 'شهادة تجريبية من النظام'],
                'is_verified' => true,
            ]
        );
    }
}
