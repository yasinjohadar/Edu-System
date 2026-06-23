<?php

namespace Database\Seeders;

use App\Models\Alumni;
use App\Models\AlumniDonation;
use App\Models\AlumniEvent;
use App\Models\JobPosting;
use App\Models\Student;
use Illuminate\Database\Seeder;

class AlumniSeeder extends Seeder
{
    public function run(): void
    {
        $student = Student::with('user')->where('status', 'active')->orderBy('id')->first();

        $alumnus = Alumni::updateOrCreate(
            ['email' => 'alumni.demo@school.test'],
            [
                'student_id' => $student?->id,
                'name' => $student?->user?->name ?? 'خريج تجريبي',
                'phone' => '0501234567',
                'graduation_date' => now()->subYears(2),
                'degree' => 'ثانوية عامة',
                'major' => 'علوم',
                'current_job' => 'مهندس برمجيات',
                'company' => 'شركة تقنية',
                'is_active' => true,
            ]
        );

        AlumniEvent::updateOrCreate(
            ['title' => 'لقاء خريجي السنة'],
            [
                'description' => 'لقاء سنوي للخريجين في حرم المدرسة',
                'event_date' => now()->addMonths(2),
                'event_time' => '18:00',
                'location' => 'قاعة المؤتمرات',
                'type' => 'reunion',
                'max_attendees' => 200,
                'fee' => 0,
                'is_active' => true,
            ]
        );

        JobPosting::updateOrCreate(
            ['title' => 'مطور ويب', 'company' => 'شركة التقنية المتقدمة'],
            [
                'description' => 'مطلوب مطور ويب بخبرة في Laravel و Vue.',
                'location' => 'الرياض',
                'salary_range' => '10000-15000',
                'employment_type' => 'full_time',
                'application_deadline' => now()->addMonth(),
                'contact_email' => 'hr@company.test',
                'is_active' => true,
            ]
        );

        AlumniDonation::updateOrCreate(
            ['reference_number' => 'DON-DEMO-0001'],
            [
                'alumni_id' => $alumnus->id,
                'amount' => 5000.00,
                'payment_method' => 'bank_transfer',
                'donation_date' => now(),
                'purpose' => 'دعم المكتبة',
                'status' => 'completed',
                'notes' => 'تبرع تجريبي',
            ]
        );
    }
}
