<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // تشغيل seeders بالترتيب الصحيح
        $this->call([
            PermissionSeeder::class,
            RoleSeeder::class,
            AdminUserSeeder::class,
            GradeSeeder::class,
            ClassSeeder::class,
            SectionSeeder::class,
            SubjectSeeder::class,
            TeacherSeeder::class,
            ParentSeeder::class,
            StudentSeeder::class,
            AttendanceSeeder::class,
            ScheduleSeeder::class,
            GradeRecordSeeder::class,
            FeeTypeSeeder::class,
            InvoiceSeeder::class,
            PaymentSeeder::class,
            BookCategorySeeder::class,
            BookSeeder::class,
            BookBorrowingSeeder::class,
            OnlineLectureSeeder::class,
            LectureMaterialSeeder::class,
            LectureAttendanceSeeder::class,
            AssignmentSeeder::class,
            AssignmentSubmissionSeeder::class,
            SmtpSettingSeeder::class,
        ]);
    }
}
