<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Attendance;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Carbon\Carbon;

class AttendanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $students = Student::with('section', 'user')
            ->where('status', 'active')
            ->whereNotNull('section_id')
            ->get();

        if ($students->isEmpty()) {
            $this->command->warn('لا توجد طلاب نشطين مع فصول. يرجى تشغيل StudentSeeder أولاً.');
            return;
        }

        // الحصول على معلمين (لـ marked_by)
        $teachers = Teacher::with('user')->where('status', 'active')->get();
        $adminUser = User::where('email', 'admin@gmail.com')->first();

        if ($teachers->isEmpty() && !$adminUser) {
            $this->command->warn('لا توجد معلمين أو مستخدم admin. سيتم استخدام null لـ marked_by.');
        }

        // إنشاء سجلات حضور لآخر 14 يوم (أسبوعين)
        $startDate = Carbon::now()->subDays(14);
        $endDate = Carbon::now();
        $currentDate = $startDate->copy();

        $statuses = ['present', 'present', 'present', 'present', 'present', 'present', 'present', 'present', 'absent', 'late', 'excused'];
        $checkInTimes = ['07:30', '07:45', '08:00', '08:15', '08:30', '08:45', '09:00'];
        $lateTimes = ['08:30', '08:45', '09:00', '09:15', '09:30'];

        $attendanceCount = 0;

        while ($currentDate <= $endDate) {
            // تخطي عطلات نهاية الأسبوع (الجمعة = 5، السبت = 6)
            $dayOfWeek = $currentDate->dayOfWeek;
            if ($dayOfWeek == 5 || $dayOfWeek == 6) {
                $currentDate->addDay();
                continue;
            }

            foreach ($students as $student) {
                if (!$student->section_id) {
                    continue;
                }

                // تجنب إنشاء سجلات مكررة
                $existingAttendance = Attendance::where('student_id', $student->id)
                    ->where('section_id', $student->section_id)
                    ->whereDate('date', $currentDate->format('Y-m-d'))
                    ->first();

                if ($existingAttendance) {
                    continue;
                }

                // اختيار حالة الحضور بشكل عشوائي
                $status = $statuses[array_rand($statuses)];
                
                // تحديد وقت الحضور حسب الحالة
                $checkInTime = null;
                if ($status === 'present') {
                    $checkInTime = $checkInTimes[array_rand($checkInTimes)];
                } elseif ($status === 'late') {
                    $checkInTime = $lateTimes[array_rand($lateTimes)];
                }

                // اختيار معلم عشوائي أو admin
                $markedBy = null;
                if ($teachers->isNotEmpty() && rand(0, 1)) {
                    $markedBy = $teachers->random()->user_id;
                } elseif ($adminUser) {
                    $markedBy = $adminUser->id;
                }

                // ملاحظات عشوائية
                $notes = null;
                if ($status === 'absent' && rand(0, 3) === 0) {
                    $notesOptions = [
                        'غياب بعذر',
                        'غياب بدون عذر',
                        'مرض',
                        'سفر',
                    ];
                    $notes = $notesOptions[array_rand($notesOptions)];
                } elseif ($status === 'late' && rand(0, 2) === 0) {
                    $notesOptions = [
                        'تأخر بسبب المواصلات',
                        'تأخر بسبب ظروف عائلية',
                    ];
                    $notes = $notesOptions[array_rand($notesOptions)];
                }

                Attendance::create([
                    'student_id' => $student->id,
                    'section_id' => $student->section_id,
                    'date' => $currentDate->format('Y-m-d'),
                    'status' => $status,
                    'check_in_time' => $checkInTime,
                    'check_out_time' => null,
                    'notes' => $notes,
                    'marked_by' => $markedBy,
                ]);

                $attendanceCount++;
            }

            $currentDate->addDay();
        }

        $this->command->info("تم إنشاء {$attendanceCount} سجل حضور بنجاح!");
    }
}
