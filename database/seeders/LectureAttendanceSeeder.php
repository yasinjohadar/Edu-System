<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LectureAttendance;
use App\Models\OnlineLecture;
use App\Models\Student;
use Carbon\Carbon;

class LectureAttendanceSeeder extends Seeder
{
    public function run(): void
    {
        $lectures = OnlineLecture::where('is_published', true)->get();
        $students = Student::where('status', 'active')->get();

        if ($lectures->isEmpty() || $students->isEmpty()) {
            $this->command->warn('لا توجد محاضرات أو طلاب.');
            return;
        }

        $statuses = ['present', 'absent', 'late', 'excused'];

        foreach ($lectures->take(10) as $lecture) {
            $sectionStudents = $students->where('section_id', $lecture->section_id);
            
            foreach ($sectionStudents->take(rand(5, 10)) as $student) {
                $status = $statuses[array_rand($statuses)];
                $joinedAt = $status === 'present' || $status === 'late' ? Carbon::now()->subDays(rand(1, 30)) : null;
                $leftAt = $joinedAt ? $joinedAt->copy()->addMinutes(rand(30, 120)) : null;

                LectureAttendance::firstOrCreate(
                    [
                        'lecture_id' => $lecture->id,
                        'student_id' => $student->id,
                    ],
                    [
                        'status' => $status,
                        'joined_at' => $joinedAt,
                        'left_at' => $leftAt,
                        'duration_minutes' => $joinedAt && $leftAt ? $joinedAt->diffInMinutes($leftAt) : null,
                    ]
                );
            }
        }
    }
}
