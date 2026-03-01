<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\Student;
use Carbon\Carbon;

class AssignmentSubmissionSeeder extends Seeder
{
    public function run(): void
    {
        $assignments = Assignment::where('status', 'published')->get();
        $students = Student::where('status', 'active')->get();

        if ($assignments->isEmpty() || $students->isEmpty()) {
            $this->command->warn('لا توجد واجبات منشورة أو طلاب نشطين.');
            return;
        }

        $submissionCount = 0;

        foreach ($assignments as $assignment) {
            // تسليمات عادية
            $submittedStudents = $students->random(rand(3, min(8, $students->count())));
            
            foreach ($submittedStudents as $student) {
                $isLate = rand(0, 10) < 2; // 20% متأخرة
                $dueDateTime = Carbon::parse($assignment->due_date->format('Y-m-d') . ' ' . $assignment->due_time);
                $submittedAt = $isLate 
                    ? $dueDateTime->copy()->addDays(rand(1, 3))
                    : $dueDateTime->copy()->subDays(rand(0, 2))->subHours(rand(0, 12));

                $daysLate = $isLate ? $submittedAt->diffInDays($dueDateTime) : 0;
                $latePenalty = $isLate ? $assignment->late_penalty_per_day * $daysLate : 0;

                $submission = AssignmentSubmission::create([
                    'assignment_id' => $assignment->id,
                    'student_id' => $student->id,
                    'submission_number' => AssignmentSubmission::generateSubmissionNumber(),
                    'attempt_number' => 1,
                    'is_resubmission' => false,
                    'submitted_at' => $submittedAt,
                    'status' => $isLate ? 'late' : 'submitted',
                    'student_notes' => rand(0, 10) < 5 ? 'تم إكمال الواجب حسب التعليمات' : null,
                    'is_late' => $isLate,
                    'days_late' => $daysLate,
                    'late_penalty' => $latePenalty,
                ]);

                // إضافة نصوص عشوائية
                if (in_array('text', json_decode($assignment->submission_types, true) ?? [])) {
                    $submission->texts()->create([
                        'content' => 'هذا هو نص الإجابة على الواجب. تم حل جميع الأسئلة بشكل صحيح.',
                        'sort_order' => 0,
                    ]);
                }

                // إضافة روابط عشوائية
                if (in_array('link', json_decode($assignment->submission_types, true) ?? [])) {
                    if (rand(0, 10) < 4) {
                        $submission->links()->create([
                            'url' => 'https://drive.google.com/file/d/example',
                            'title' => 'ملف الواجب على Google Drive',
                            'link_type' => 'google_drive',
                            'sort_order' => 0,
                        ]);
                    }
                }

                $submissionCount++;

                // بعض التسليمات تحتاج إعادة تسليم
                if ($assignment->allow_resubmission && rand(0, 10) < 3) {
                    $submission->update([
                        'status' => 'returned',
                        'requires_resubmission' => true,
                        'resubmission_reason' => 'يرجى تحسين الإجابة على السؤال الثالث',
                        'marks_obtained' => null,
                    ]);

                    // إعادة تسليم
                    $resubmittedAt = $submittedAt->copy()->addDays(rand(1, 3));
                    $resubmission = AssignmentSubmission::create([
                        'assignment_id' => $assignment->id,
                        'student_id' => $student->id,
                        'submission_number' => AssignmentSubmission::generateSubmissionNumber(),
                        'attempt_number' => 2,
                        'is_resubmission' => true,
                        'previous_submission_id' => $submission->id,
                        'submitted_at' => $resubmittedAt,
                        'status' => 'submitted',
                        'student_notes' => 'تم تحسين الإجابة حسب الملاحظات',
                        'is_late' => false,
                        'days_late' => 0,
                        'late_penalty' => 0,
                    ]);

                    if (in_array('text', json_decode($assignment->submission_types, true) ?? [])) {
                        $resubmission->texts()->create([
                            'content' => 'هذا هو النص المحسّن للإجابة بعد الملاحظات.',
                            'sort_order' => 0,
                        ]);
                    }

                    $submissionCount++;
                } else {
                    // تصحيح بعض التسليمات
                    if (rand(0, 10) < 6) {
                        $marksObtained = rand(60, 100) / 100 * $assignment->total_marks;
                        $submission->update([
                            'status' => 'graded',
                            'marks_obtained' => round($marksObtained, 2),
                            'feedback' => 'عمل جيد، استمر في التقدم',
                            'teacher_notes' => 'الإجابة صحيحة بشكل عام مع بعض الأخطاء البسيطة',
                            'graded_at' => $submittedAt->copy()->addDays(rand(1, 3)),
                            'graded_by' => $assignment->teacher->user_id,
                        ]);
                    }
                }
            }
        }

        $this->command->info('تم إنشاء ' . $submissionCount . ' تسليم بنجاح.');
    }
}
