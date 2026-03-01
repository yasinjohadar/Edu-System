<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\OnlineLecture;
use App\Models\Subject;
use App\Models\Section;
use App\Models\Teacher;
use Carbon\Carbon;

class OnlineLectureSeeder extends Seeder
{
    public function run(): void
    {
        $subjects = Subject::where('is_active', true)->get();
        $sections = Section::where('is_active', true)->get();
        $teachers = Teacher::all();

        if ($subjects->isEmpty() || $sections->isEmpty() || $teachers->isEmpty()) {
            $this->command->warn('لا توجد مواد أو فصول أو معلمين.');
            return;
        }

        $lectureTypes = ['live', 'recorded', 'material'];
        $titles = [
            'مقدمة في الفيزياء',
            'قواعد اللغة العربية',
            'الرياضيات الأساسية',
            'مبادئ الكيمياء',
            'تاريخ العالم',
            'اللغة الإنجليزية',
            'التربية الإسلامية',
            'العلوم العامة',
        ];

        foreach ($subjects->take(5) as $subject) {
            foreach ($sections->take(3) as $section) {
                $teacher = $teachers->random();
                
                for ($i = 0; $i < 3; $i++) {
                    $type = $lectureTypes[array_rand($lectureTypes)];
                    $scheduledAt = $type === 'live' ? Carbon::now()->addDays(rand(1, 30)) : null;

                    OnlineLecture::create([
                        'subject_id' => $subject->id,
                        'section_id' => $section->id,
                        'teacher_id' => $teacher->id,
                        'title' => $titles[array_rand($titles)] . ' - ' . ($i + 1),
                        'description' => 'وصف المحاضرة ' . ($i + 1),
                        'content' => 'محتوى المحاضرة التعليمي...',
                        'type' => $type,
                        'video_url' => $type === 'recorded' ? 'https://example.com/video/' . rand(1000, 9999) : null,
                        'scheduled_at' => $scheduledAt,
                        'duration' => rand(30, 120),
                        'meeting_link' => $type === 'live' ? 'https://meet.example.com/' . rand(1000, 9999) : null,
                        'is_published' => true,
                        'is_active' => true,
                        'views_count' => rand(0, 500),
                    ]);
                }
            }
        }
    }
}
