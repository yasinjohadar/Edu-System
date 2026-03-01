<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Schedule;
use App\Models\Section;
use App\Models\Subject;
use App\Models\Teacher;

class ScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // الحصول على جميع الفصول النشطة
        $sections = Section::with('class')->where('is_active', true)->get();
        
        // الحصول على جميع المواد النشطة
        $subjects = Subject::where('is_active', true)->get();
        
        // الحصول على جميع المعلمين
        $teachers = Teacher::with('user', 'subjects')->get();

        if ($sections->isEmpty() || $subjects->isEmpty() || $teachers->isEmpty()) {
            $this->command->warn('لا توجد فصول أو مواد أو معلمين. يرجى تشغيل seeders أخرى أولاً.');
            return;
        }

        // أيام الأسبوع
        $days = ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday'];
        
        // الأوقات النموذجية (من 8:00 صباحاً إلى 2:00 ظهراً)
        $timeSlots = [
            ['start' => '08:00', 'end' => '08:45', 'order' => 1], // الحصة الأولى
            ['start' => '08:45', 'end' => '09:30', 'order' => 2], // الحصة الثانية
            ['start' => '09:30', 'end' => '10:15', 'order' => 3], // الحصة الثالثة
            ['start' => '10:15', 'end' => '10:30', 'order' => 0], // استراحة
            ['start' => '10:30', 'end' => '11:15', 'order' => 4], // الحصة الرابعة
            ['start' => '11:15', 'end' => '12:00', 'order' => 5], // الحصة الخامسة
            ['start' => '12:00', 'end' => '12:15', 'order' => 0], // استراحة
            ['start' => '12:15', 'end' => '13:00', 'order' => 6], // الحصة السادسة
            ['start' => '13:00', 'end' => '13:45', 'order' => 7], // الحصة السابعة
        ];

        $schedulesCreated = 0;

        foreach ($sections as $section) {
            // الحصول على المواد المرتبطة بصف هذا الفصل
            $classSubjects = $section->class->subjects()->where('subjects.is_active', true)->get();
            
            if ($classSubjects->isEmpty()) {
                continue;
            }

            // توزيع المواد على أيام الأسبوع
            $subjectSchedule = [];
            $subjectIndex = 0;
            
            foreach ($days as $day) {
                $daySubjects = [];
                $periodsPerDay = 6; // 6 حصص يومياً (تخطي الاستراحات)
                
                for ($period = 0; $period < $periodsPerDay; $period++) {
                    if ($subjectIndex >= $classSubjects->count()) {
                        $subjectIndex = 0; // إعادة الدور
                    }
                    
                    $subject = $classSubjects[$subjectIndex];
                    $daySubjects[] = $subject;
                    $subjectIndex++;
                }
                
                $subjectSchedule[$day] = $daySubjects;
            }

            // إنشاء الجداول الدراسية
            foreach ($days as $day) {
                $periodCount = 0;
                
                foreach ($timeSlots as $slot) {
                    // تخطي الاستراحات
                    if ($slot['order'] == 0) {
                        continue;
                    }
                    
                    if ($periodCount >= count($subjectSchedule[$day])) {
                        break;
                    }
                    
                    $subject = $subjectSchedule[$day][$periodCount];
                    
                    // البحث عن معلم يدرس هذه المادة
                    $teacher = $teachers->filter(function ($t) use ($subject) {
                        return $t->subjects->contains($subject->id);
                    })->first();
                    
                    // إذا لم يوجد معلم لهذه المادة، اختر معلم عشوائي
                    if (!$teacher) {
                        $teacher = $teachers->random();
                    }
                    
                    // التحقق من عدم وجود تعارض في الوقت لنفس الفصل
                    $conflict = Schedule::where('section_id', $section->id)
                        ->where('day_of_week', $day)
                        ->where(function ($query) use ($slot) {
                            $query->whereBetween('start_time', [$slot['start'], $slot['end']])
                                ->orWhereBetween('end_time', [$slot['start'], $slot['end']])
                                ->orWhere(function ($q) use ($slot) {
                                    $q->where('start_time', '<=', $slot['start'])
                                        ->where('end_time', '>=', $slot['end']);
                                });
                        })
                        ->where('is_active', true)
                        ->exists();
                    
                    if (!$conflict) {
                        // التحقق من عدم وجود تعارض في الوقت لنفس المعلم
                        $teacherConflict = Schedule::where('teacher_id', $teacher->id)
                            ->where('day_of_week', $day)
                            ->where(function ($query) use ($slot) {
                                $query->whereBetween('start_time', [$slot['start'], $slot['end']])
                                    ->orWhereBetween('end_time', [$slot['start'], $slot['end']])
                                    ->orWhere(function ($q) use ($slot) {
                                        $q->where('start_time', '<=', $slot['start'])
                                            ->where('end_time', '>=', $slot['end']);
                                    });
                            })
                            ->where('is_active', true)
                            ->exists();
                        
                        if (!$teacherConflict) {
                            Schedule::firstOrCreate(
                                [
                                    'section_id' => $section->id,
                                    'subject_id' => $subject->id,
                                    'teacher_id' => $teacher->id,
                                    'day_of_week' => $day,
                                    'start_time' => $slot['start'],
                                ],
                                [
                                    'end_time' => $slot['end'],
                                    'order' => $slot['order'],
                                    'room' => 'قاعة ' . $section->class->name . '-' . $section->name,
                                    'is_active' => true,
                                    'notes' => null,
                                ]
                            );
                            
                            $schedulesCreated++;
                        }
                    }
                    
                    $periodCount++;
                }
            }
        }

        $this->command->info("تم إنشاء {$schedulesCreated} جدول دراسي بنجاح!");
    }
}
