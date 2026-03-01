<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Grade;
use App\Models\ClassModel;

class ClassSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $primaryGrade = Grade::where('name', 'الابتدائي')->first();
        $intermediateGrade = Grade::where('name', 'المتوسط')->first();
        $secondaryGrade = Grade::where('name', 'الثانوي')->first();

        if ($primaryGrade) {
            $primaryClasses = [
                ['name' => 'الصف الأول', 'name_en' => 'First Grade', 'order' => 1],
                ['name' => 'الصف الثاني', 'name_en' => 'Second Grade', 'order' => 2],
                ['name' => 'الصف الثالث', 'name_en' => 'Third Grade', 'order' => 3],
                ['name' => 'الصف الرابع', 'name_en' => 'Fourth Grade', 'order' => 4],
                ['name' => 'الصف الخامس', 'name_en' => 'Fifth Grade', 'order' => 5],
                ['name' => 'الصف السادس', 'name_en' => 'Sixth Grade', 'order' => 6],
            ];

            foreach ($primaryClasses as $class) {
                ClassModel::firstOrCreate(
                    ['name' => $class['name'], 'grade_id' => $primaryGrade->id],
                    array_merge($class, ['grade_id' => $primaryGrade->id, 'is_active' => true])
                );
            }
        }

        if ($intermediateGrade) {
            $intermediateClasses = [
                ['name' => 'الصف الأول المتوسط', 'name_en' => 'First Intermediate', 'order' => 1],
                ['name' => 'الصف الثاني المتوسط', 'name_en' => 'Second Intermediate', 'order' => 2],
                ['name' => 'الصف الثالث المتوسط', 'name_en' => 'Third Intermediate', 'order' => 3],
            ];

            foreach ($intermediateClasses as $class) {
                ClassModel::firstOrCreate(
                    ['name' => $class['name'], 'grade_id' => $intermediateGrade->id],
                    array_merge($class, ['grade_id' => $intermediateGrade->id, 'is_active' => true])
                );
            }
        }

        if ($secondaryGrade) {
            $secondaryClasses = [
                ['name' => 'الصف الأول الثانوي', 'name_en' => 'First Secondary', 'order' => 1],
                ['name' => 'الصف الثاني الثانوي', 'name_en' => 'Second Secondary', 'order' => 2],
                ['name' => 'الصف الثالث الثانوي', 'name_en' => 'Third Secondary', 'order' => 3],
            ];

            foreach ($secondaryClasses as $class) {
                ClassModel::firstOrCreate(
                    ['name' => $class['name'], 'grade_id' => $secondaryGrade->id],
                    array_merge($class, ['grade_id' => $secondaryGrade->id, 'is_active' => true])
                );
            }
        }
    }
}
