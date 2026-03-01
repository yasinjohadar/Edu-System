<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Student;
use App\Models\ParentModel;
use App\Models\ClassModel;
use App\Models\Section;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $studentRole = Role::firstOrCreate(['name' => 'student']);
        $classes = ClassModel::where('is_active', true)->with('sections')->get();
        $parents = ParentModel::all();

        if ($classes->isEmpty()) {
            $this->command->warn('لا توجد صفوف متاحة. يرجى تشغيل ClassSeeder و SectionSeeder أولاً.');
            return;
        }

        $students = [
            [
                'name' => 'أحمد محمد السعيد',
                'email' => 'ahmed.student1@school.com',
                'phone' => '0502222221',
                'student_code' => 'S001',
                'gender' => 'male',
                'date_of_birth' => '2010-05-15',
                'enrollment_date' => '2023-09-01',
                'status' => 'active',
                'parent_guardian' => 'محمد عبدالله السعيد',
            ],
            [
                'name' => 'فاطمة محمد السعيد',
                'email' => 'fatima.student1@school.com',
                'phone' => '0502222222',
                'student_code' => 'S002',
                'gender' => 'female',
                'date_of_birth' => '2011-03-20',
                'enrollment_date' => '2023-09-01',
                'status' => 'active',
                'parent_guardian' => 'محمد عبدالله السعيد',
            ],
            [
                'name' => 'خالد أحمد محمد',
                'email' => 'khalid.student1@school.com',
                'phone' => '0502222223',
                'student_code' => 'S003',
                'gender' => 'male',
                'date_of_birth' => '2009-07-10',
                'enrollment_date' => '2022-09-01',
                'status' => 'active',
                'parent_guardian' => 'فاطمة أحمد محمد',
            ],
            [
                'name' => 'نورا خالد حسن',
                'email' => 'nora.student1@school.com',
                'phone' => '0502222224',
                'student_code' => 'S004',
                'gender' => 'female',
                'date_of_birth' => '2012-11-25',
                'enrollment_date' => '2023-09-01',
                'status' => 'active',
                'parent_guardian' => 'خالد حسن علي',
            ],
            [
                'name' => 'سعد عبدالرحمن الدوسري',
                'email' => 'saad.student1@school.com',
                'phone' => '0502222225',
                'student_code' => 'S005',
                'gender' => 'male',
                'date_of_birth' => '2010-01-30',
                'enrollment_date' => '2023-09-01',
                'status' => 'active',
                'parent_guardian' => 'عبدالرحمن سعد الدوسري',
            ],
            [
                'name' => 'مريم يوسف محمد',
                'email' => 'mariam.student2@school.com',
                'phone' => '0502222226',
                'student_code' => 'S006',
                'gender' => 'female',
                'date_of_birth' => '2011-08-15',
                'enrollment_date' => '2023-09-01',
                'status' => 'active',
                'parent_guardian' => 'مريم علي حسن',
            ],
            [
                'name' => 'يوسف يوسف محمد',
                'email' => 'youssef.student1@school.com',
                'phone' => '0502222227',
                'student_code' => 'S007',
                'gender' => 'male',
                'date_of_birth' => '2009-12-05',
                'enrollment_date' => '2022-09-01',
                'status' => 'active',
                'parent_guardian' => 'يوسف محمد خالد',
            ],
            [
                'name' => 'لينا أحمد فهد',
                'email' => 'lina.student1@school.com',
                'phone' => '0502222228',
                'student_code' => 'S008',
                'gender' => 'female',
                'date_of_birth' => '2010-04-18',
                'enrollment_date' => '2023-09-01',
                'status' => 'active',
                'parent_guardian' => 'لينا أحمد فهد',
            ],
        ];

        $classIndex = 0;
        $sectionIndex = 0;

        foreach ($students as $studentData) {
            $user = User::firstOrCreate(
                ['email' => $studentData['email']],
                [
                    'name' => $studentData['name'],
                    'phone' => $studentData['phone'],
                    'password' => Hash::make('123456789'),
                    'status' => 'active',
                    'is_active' => true,
                    'email_verified_at' => now(),
                ]
            );

            if (!$user->hasRole('student')) {
                $user->assignRole($studentRole);
            }

            // اختيار صف وفصل بشكل دوري
            $class = $classes[$classIndex % $classes->count()];
            $sections = $class->sections->where('is_active', true);
            if ($sections->isEmpty()) {
                $section = null;
            } else {
                $section = $sections[$sectionIndex % $sections->count()];
            }

            $student = Student::firstOrCreate(
                ['student_code' => $studentData['student_code']],
                [
                    'user_id' => $user->id,
                    'student_code' => $studentData['student_code'],
                    'gender' => $studentData['gender'],
                    'date_of_birth' => $studentData['date_of_birth'],
                    'enrollment_date' => $studentData['enrollment_date'],
                    'status' => $studentData['status'],
                    'class_id' => $class->id,
                    'section_id' => $section ? $section->id : null,
                    'parent_guardian' => $studentData['parent_guardian'],
                ]
            );

            // ربط الطالب بولي أمر (اختيار عشوائي)
            if ($parents->isNotEmpty()) {
                $parent = $parents->random();
                if (!$student->parents()->where('parents.id', $parent->id)->exists()) {
                    $student->parents()->attach($parent->id, [
                        'relationship_type' => $parent->relationship,
                        'is_primary' => true,
                    ]);
                }
            }

            $classIndex++;
            $sectionIndex++;
        }
    }
}
