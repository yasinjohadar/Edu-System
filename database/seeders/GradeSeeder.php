<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Grade;

class GradeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $grades = [
            [
                'name' => 'الروضة',
                'name_en' => 'Kindergarten',
                'min_age' => 4,
                'max_age' => 6,
                'fees' => 5000.00,
                'order' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'الابتدائي',
                'name_en' => 'Primary',
                'min_age' => 6,
                'max_age' => 12,
                'fees' => 8000.00,
                'order' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'المتوسط',
                'name_en' => 'Intermediate',
                'min_age' => 12,
                'max_age' => 15,
                'fees' => 10000.00,
                'order' => 3,
                'is_active' => true,
            ],
            [
                'name' => 'الثانوي',
                'name_en' => 'Secondary',
                'min_age' => 15,
                'max_age' => 18,
                'fees' => 12000.00,
                'order' => 4,
                'is_active' => true,
            ],
        ];

        foreach ($grades as $grade) {
            Grade::firstOrCreate(
                ['name' => $grade['name']],
                $grade
            );
        }
    }
}
