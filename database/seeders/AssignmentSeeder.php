<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Assignment;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\Section;
use App\Models\User;
use Carbon\Carbon;

class AssignmentSeeder extends Seeder
{
    public function run(): void
    {
        $subjects = Subject::all();
        $teachers = Teacher::all();
        $sections = Section::all();
        $admin = User::where('email', 'admin@gmail.com')->first();

        if ($subjects->isEmpty() || $teachers->isEmpty() || $sections->isEmpty()) {
            $this->command->warn('لا توجد مواد أو معلمين أو فصول. يرجى تشغيل Seeders الأساسية أولاً.');
            return;
        }

        $assignments = [
            [
                'title' => 'واجب الرياضيات - الفصل الأول',
                'description' => 'واجب شامل على الدوال والجبر',
                'instructions' => 'يرجى حل جميع الأسئلة مع عرض الخطوات بشكل واضح',
                'total_marks' => 100,
                'due_date' => Carbon::now()->addDays(7),
                'due_time' => '23:59',
                'max_attempts' => 3,
                'allow_resubmission' => true,
                'submission_types' => ['file', 'text'],
            ],
            [
                'title' => 'مشروع العلوم - التجارب العلمية',
                'description' => 'مشروع عن التجارب العلمية الأساسية',
                'instructions' => 'قم بإجراء تجربة علمية بسيطة واكتب تقريراً عنها',
                'total_marks' => 50,
                'due_date' => Carbon::now()->addDays(14),
                'due_time' => '23:59',
                'max_attempts' => 2,
                'allow_resubmission' => true,
                'submission_types' => ['file', 'link'],
            ],
            [
                'title' => 'واجب اللغة العربية - النحو',
                'description' => 'تمارين على قواعد النحو',
                'instructions' => 'حل التمارين المرفقة في الملف',
                'total_marks' => 30,
                'due_date' => Carbon::now()->addDays(5),
                'due_time' => '23:59',
                'max_attempts' => null,
                'allow_resubmission' => false,
                'submission_types' => ['text', 'file'],
            ],
            [
                'title' => 'بحث التاريخ - الحضارات القديمة',
                'description' => 'بحث عن الحضارات القديمة',
                'instructions' => 'اكتب بحثاً عن إحدى الحضارات القديمة مع المراجع',
                'total_marks' => 40,
                'due_date' => Carbon::now()->addDays(10),
                'due_time' => '23:59',
                'max_attempts' => 1,
                'allow_resubmission' => false,
                'submission_types' => ['file', 'text', 'link'],
            ],
            [
                'title' => 'واجب الفيزياء - الحركة',
                'description' => 'تمارين على الحركة والسرعة',
                'instructions' => 'حل المسائل المرفقة مع عرض الحلول',
                'total_marks' => 25,
                'due_date' => Carbon::now()->addDays(3),
                'due_time' => '23:59',
                'max_attempts' => 2,
                'allow_resubmission' => true,
                'submission_types' => ['file'],
            ],
        ];

        foreach ($assignments as $index => $assignmentData) {
            $subject = $subjects->random();
            $teacher = $teachers->random();
            $section = $sections->random();

            Assignment::create([
                'assignment_number' => Assignment::generateAssignmentNumber(),
                'subject_id' => $subject->id,
                'teacher_id' => $teacher->id,
                'section_id' => $section->id,
                'title' => $assignmentData['title'],
                'description' => $assignmentData['description'],
                'instructions' => $assignmentData['instructions'],
                'total_marks' => $assignmentData['total_marks'],
                'due_date' => $assignmentData['due_date'],
                'due_time' => $assignmentData['due_time'],
                'allow_late_submission' => true,
                'late_penalty_per_day' => 2.5,
                'max_late_days' => 5,
                'max_attempts' => $assignmentData['max_attempts'],
                'allow_resubmission' => $assignmentData['allow_resubmission'],
                'resubmission_deadline' => $assignmentData['allow_resubmission'] ? $assignmentData['due_date']->copy()->addDays(7) : null,
                'submission_types' => json_encode($assignmentData['submission_types']),
                'status' => 'published',
                'is_active' => true,
                'created_by' => $admin?->id,
            ]);
        }

        $this->command->info('تم إنشاء ' . count($assignments) . ' واجب بنجاح.');
    }
}
