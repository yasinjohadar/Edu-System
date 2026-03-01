<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Teacher;
use App\Models\Subject;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class TeacherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $teacherRole = Role::firstOrCreate(['name' => 'teacher']);
        $subjects = Subject::where('is_active', true)->get();

        $teachers = [
            [
                'name' => 'أحمد محمد علي',
                'email' => 'ahmed.teacher@school.com',
                'phone' => '0501234567',
                'teacher_code' => 'T001',
                'specialization' => 'اللغة العربية',
                'qualification' => 'بكالوريوس في اللغة العربية',
                'experience_years' => '10',
                'gender' => 'male',
                'date_of_birth' => '1985-05-15',
                'hire_date' => '2015-09-01',
                'salary' => 8000.00,
                'status' => 'active',
            ],
            [
                'name' => 'فاطمة أحمد حسن',
                'email' => 'fatima.teacher@school.com',
                'phone' => '0501234568',
                'teacher_code' => 'T002',
                'specialization' => 'الرياضيات',
                'qualification' => 'بكالوريوس في الرياضيات',
                'experience_years' => '8',
                'gender' => 'female',
                'date_of_birth' => '1988-03-20',
                'hire_date' => '2016-09-01',
                'salary' => 7500.00,
                'status' => 'active',
            ],
            [
                'name' => 'محمد خالد إبراهيم',
                'email' => 'mohammed.teacher@school.com',
                'phone' => '0501234569',
                'teacher_code' => 'T003',
                'specialization' => 'العلوم',
                'qualification' => 'بكالوريوس في العلوم',
                'experience_years' => '12',
                'gender' => 'male',
                'date_of_birth' => '1983-07-10',
                'hire_date' => '2014-09-01',
                'salary' => 8500.00,
                'status' => 'active',
            ],
            [
                'name' => 'سارة علي محمود',
                'email' => 'sara.teacher@school.com',
                'phone' => '0501234570',
                'teacher_code' => 'T004',
                'specialization' => 'اللغة الإنجليزية',
                'qualification' => 'بكالوريوس في اللغة الإنجليزية',
                'experience_years' => '7',
                'gender' => 'female',
                'date_of_birth' => '1990-11-25',
                'hire_date' => '2017-09-01',
                'salary' => 7200.00,
                'status' => 'active',
            ],
            [
                'name' => 'علي حسن محمد',
                'email' => 'ali.teacher@school.com',
                'phone' => '0501234571',
                'teacher_code' => 'T005',
                'specialization' => 'التربية الإسلامية',
                'qualification' => 'بكالوريوس في الشريعة',
                'experience_years' => '15',
                'gender' => 'male',
                'date_of_birth' => '1980-01-30',
                'hire_date' => '2012-09-01',
                'salary' => 9000.00,
                'status' => 'active',
            ],
        ];

        foreach ($teachers as $teacherData) {
            $user = User::firstOrCreate(
                ['email' => $teacherData['email']],
                [
                    'name' => $teacherData['name'],
                    'phone' => $teacherData['phone'],
                    'password' => Hash::make('123456789'),
                    'status' => 'active',
                    'is_active' => true,
                    'email_verified_at' => now(),
                ]
            );

            if (!$user->hasRole('teacher')) {
                $user->assignRole($teacherRole);
            }

            $teacher = Teacher::firstOrCreate(
                ['teacher_code' => $teacherData['teacher_code']],
                [
                    'user_id' => $user->id,
                    'teacher_code' => $teacherData['teacher_code'],
                    'specialization' => $teacherData['specialization'],
                    'qualification' => $teacherData['qualification'],
                    'experience_years' => $teacherData['experience_years'],
                    'gender' => $teacherData['gender'],
                    'date_of_birth' => $teacherData['date_of_birth'],
                    'hire_date' => $teacherData['hire_date'],
                    'salary' => $teacherData['salary'],
                    'status' => $teacherData['status'],
                ]
            );

            // ربط المعلم بالمواد المناسبة
            if ($subjects->count() > 0) {
                $subjectToAttach = $subjects->where('name', 'like', '%' . explode(' ', $teacherData['specialization'])[0] . '%')->first();
                if (!$subjectToAttach) {
                    $subjectToAttach = $subjects->random();
                }
                if (!$teacher->subjects()->where('subjects.id', $subjectToAttach->id)->exists()) {
                    $teacher->subjects()->attach($subjectToAttach->id);
                }
            }
        }
    }
}
