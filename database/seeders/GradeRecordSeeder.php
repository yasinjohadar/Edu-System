<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\GradeRecord;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;
use Carbon\Carbon;

class GradeRecordSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // الحصول على جميع الطلاب النشطين
        $students = Student::with('class.subjects', 'section')->where('status', 'active')->get();
        
        // الحصول على جميع المعلمين
        $teachers = Teacher::with('user', 'subjects')->get();

        if ($students->isEmpty()) {
            $this->command->warn('لا توجد طلاب. يرجى تشغيل StudentSeeder أولاً.');
            return;
        }

        if ($teachers->isEmpty()) {
            $this->command->warn('لا توجد معلمين. يرجى تشغيل TeacherSeeder أولاً.');
            return;
        }

        // أنواع التقييمات
        $examTypes = [
            'quiz' => ['count' => 3, 'weight' => 0.1, 'total_marks' => 10],
            'assignment' => ['count' => 4, 'weight' => 0.15, 'total_marks' => 20],
            'midterm' => ['count' => 1, 'weight' => 0.3, 'total_marks' => 50],
            'final' => ['count' => 1, 'weight' => 0.4, 'total_marks' => 100],
            'project' => ['count' => 1, 'weight' => 0.05, 'total_marks' => 30],
        ];

        // السنوات الدراسية (آخر 3 سنوات)
        $currentYear = date('Y');
        $academicYears = [
            ($currentYear - 2) . '-' . ($currentYear - 1),
            ($currentYear - 1) . '-' . $currentYear,
            $currentYear . '-' . ($currentYear + 1),
        ];

        $semesters = ['first', 'second'];
        
        $gradesCreated = 0;
        $examNames = [
            'quiz' => ['اختبار قصير 1', 'اختبار قصير 2', 'اختبار قصير 3'],
            'assignment' => ['واجب الفصل الأول', 'واجب الفصل الثاني', 'واجب الفصل الثالث', 'واجب الفصل الرابع'],
            'midterm' => ['امتحان نصفي'],
            'final' => ['امتحان نهائي'],
            'project' => ['مشروع التخرج'],
        ];

        foreach ($students as $student) {
            // الحصول على المواد المرتبطة بصف الطالب
            $subjects = $student->class->subjects()->where('is_active', true)->get();
            
            if ($subjects->isEmpty()) {
                continue;
            }

            // تحديد مستوى الطالب (متفوق، متوسط، ضعيف)
            $studentLevel = $this->getStudentLevel($student->id);
            
            foreach ($subjects as $subject) {
                // البحث عن معلم يدرس هذه المادة
                $teacher = $teachers->filter(function ($t) use ($subject) {
                    return $t->subjects->contains($subject->id);
                })->first();
                
                // إذا لم يوجد معلم لهذه المادة، اختر معلم عشوائي
                if (!$teacher) {
                    $teacher = $teachers->random();
                }

                // إنشاء درجات للفصلين الدراسيين
                foreach ($semesters as $semester) {
                    // اختيار سنة دراسية عشوائية
                    $academicYear = $academicYears[array_rand($academicYears)];
                    
                    // إنشاء درجات لكل نوع تقييم
                    foreach ($examTypes as $examType => $examData) {
                        $count = $examData['count'];
                        $totalMarks = $examData['total_marks'];
                        
                        for ($i = 0; $i < $count; $i++) {
                            // حساب الدرجة بناءً على مستوى الطالب
                            $marksObtained = $this->calculateMarks($studentLevel, $totalMarks);
                            $percentage = ($marksObtained / $totalMarks) * 100;
                            
                            // حساب الدرجة الحرفية
                            $grade = $this->calculateGrade($percentage);
                            
                            // تاريخ التقييم (توزيع على أشهر الفصل الدراسي)
                            $examDate = $this->getExamDate($semester, $academicYear, $i, $examType);
                            
                            // اسم التقييم
                            $examName = $examNames[$examType][$i] ?? $examType . ' ' . ($i + 1);
                            
                            // إضافة بعض التباين في الدرجات
                            if (rand(1, 10) <= 2) { // 20% فرصة لدرجة مختلفة قليلاً
                                $marksObtained = max(0, min($totalMarks, $marksObtained + rand(-5, 5)));
                                $percentage = ($marksObtained / $totalMarks) * 100;
                                $grade = $this->calculateGrade($percentage);
                            }

                            GradeRecord::firstOrCreate(
                                [
                                    'student_id' => $student->id,
                                    'subject_id' => $subject->id,
                                    'exam_type' => $examType,
                                    'exam_name' => $examName,
                                    'academic_year' => $academicYear,
                                    'semester' => $semester,
                                ],
                                [
                                    'teacher_id' => $teacher->id,
                                    'marks_obtained' => round($marksObtained, 2),
                                    'total_marks' => $totalMarks,
                                    'percentage' => round($percentage, 2),
                                    'grade' => $grade,
                                    'exam_date' => $examDate,
                                    'notes' => $this->getRandomNote(),
                                    'is_published' => rand(1, 10) <= 8, // 80% منشور
                                ]
                            );
                            
                            $gradesCreated++;
                        }
                    }
                }
            }
        }

        $this->command->info("تم إنشاء {$gradesCreated} درجة بنجاح!");
    }

    /**
     * تحديد مستوى الطالب بناءً على ID (لضمان التنوع)
     */
    private function getStudentLevel(int $studentId): string
    {
        $mod = $studentId % 3;
        if ($mod == 0) return 'excellent'; // متفوق
        if ($mod == 1) return 'average';   // متوسط
        return 'weak';                     // ضعيف
    }

    /**
     * حساب الدرجة بناءً على مستوى الطالب
     */
    private function calculateMarks(string $level, float $totalMarks): float
    {
        switch ($level) {
            case 'excellent':
                // متفوق: 85-100%
                $percentage = rand(85, 100);
                break;
            case 'average':
                // متوسط: 60-84%
                $percentage = rand(60, 84);
                break;
            case 'weak':
                // ضعيف: 30-59%
                $percentage = rand(30, 59);
                break;
            default:
                $percentage = rand(50, 90);
        }
        
        return round(($percentage / 100) * $totalMarks, 2);
    }

    /**
     * حساب الدرجة الحرفية
     */
    private function calculateGrade(float $percentage): string
    {
        if ($percentage >= 90) return 'A+';
        if ($percentage >= 85) return 'A';
        if ($percentage >= 80) return 'B+';
        if ($percentage >= 75) return 'B';
        if ($percentage >= 70) return 'C+';
        if ($percentage >= 65) return 'C';
        if ($percentage >= 60) return 'D+';
        if ($percentage >= 50) return 'D';
        return 'F';
    }

    /**
     * الحصول على تاريخ التقييم
     */
    private function getExamDate(string $semester, string $academicYear, int $index, string $examType): Carbon
    {
        $yearParts = explode('-', $academicYear);
        $startYear = (int)$yearParts[0];
        
        // تحديد شهر البداية حسب الفصل الدراسي
        if ($semester == 'first') {
            $startMonth = 9; // سبتمبر
            $endMonth = 12;  // ديسمبر
        } else {
            $startMonth = 1; // يناير
            $endMonth = 4;   // أبريل
        }
        
        // توزيع التواريخ حسب نوع التقييم
        $month = $startMonth;
        $day = 1;
        
        switch ($examType) {
            case 'quiz':
                $month = $startMonth + ($index % 2);
                $day = 5 + ($index * 10);
                break;
            case 'assignment':
                $month = $startMonth + (int)($index / 2);
                $day = 10 + ($index * 7);
                break;
            case 'midterm':
                $month = $startMonth + 1;
                $day = 15;
                break;
            case 'final':
                $month = $endMonth;
                $day = 20;
                break;
            case 'project':
                $month = $endMonth - 1;
                $day = 25;
                break;
        }
        
        // التأكد من أن التاريخ صحيح
        $day = min($day, 28); // تجنب مشاكل فبراير
        $month = min($month, 12);
        
        try {
            return Carbon::create($startYear, $month, $day);
        } catch (\Exception $e) {
            return Carbon::create($startYear, $startMonth, 1)->addDays($index * 7);
        }
    }

    /**
     * الحصول على ملاحظة عشوائية
     */
    private function getRandomNote(): ?string
    {
        $notes = [
            null,
            null,
            null,
            'أداء جيد',
            'يحتاج إلى مزيد من الجهد',
            'ممتاز',
            'تحسن ملحوظ',
            'يحتاج متابعة',
        ];
        
        return $notes[array_rand($notes)];
    }
}
