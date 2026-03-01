<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Subject;
use App\Models\ClassModel;

class SubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $subjects = [
            [
                'name' => 'اللغة العربية',
                'name_en' => 'Arabic',
                'code' => 'ARB',
                'type' => 'required',
                'weekly_hours' => 5,
                'full_marks' => 100,
                'pass_marks' => 50,
                'is_active' => true,
            ],
            [
                'name' => 'اللغة الإنجليزية',
                'name_en' => 'English',
                'code' => 'ENG',
                'type' => 'required',
                'weekly_hours' => 4,
                'full_marks' => 100,
                'pass_marks' => 50,
                'is_active' => true,
            ],
            [
                'name' => 'الرياضيات',
                'name_en' => 'Mathematics',
                'code' => 'MATH',
                'type' => 'required',
                'weekly_hours' => 5,
                'full_marks' => 100,
                'pass_marks' => 50,
                'is_active' => true,
            ],
            [
                'name' => 'العلوم',
                'name_en' => 'Science',
                'code' => 'SCI',
                'type' => 'required',
                'weekly_hours' => 4,
                'full_marks' => 100,
                'pass_marks' => 50,
                'is_active' => true,
            ],
            [
                'name' => 'التربية الإسلامية',
                'name_en' => 'Islamic Education',
                'code' => 'ISL',
                'type' => 'required',
                'weekly_hours' => 3,
                'full_marks' => 100,
                'pass_marks' => 50,
                'is_active' => true,
            ],
            [
                'name' => 'التربية الفنية',
                'name_en' => 'Art Education',
                'code' => 'ART',
                'type' => 'optional',
                'weekly_hours' => 2,
                'full_marks' => 50,
                'pass_marks' => 25,
                'is_active' => true,
            ],
            [
                'name' => 'التربية البدنية',
                'name_en' => 'Physical Education',
                'code' => 'PE',
                'type' => 'optional',
                'weekly_hours' => 2,
                'full_marks' => 50,
                'pass_marks' => 25,
                'is_active' => true,
            ],
        ];

        foreach ($subjects as $subjectData) {
            $subject = Subject::firstOrCreate(
                ['code' => $subjectData['code']],
                $subjectData
            );

            // ربط المادة بجميع الصفوف النشطة
            $classes = ClassModel::where('is_active', true)->get();
            foreach ($classes as $class) {
                if (!$subject->classes()->where('classes.id', $class->id)->exists()) {
                    $subject->classes()->attach($class->id, [
                        'weekly_hours' => $subjectData['weekly_hours']
                    ]);
                }
            }
        }
    }
}
