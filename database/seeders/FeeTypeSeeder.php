<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\FeeType;

class FeeTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $feeTypes = [
            [
                'name' => 'رسوم التسجيل',
                'name_en' => 'Registration Fee',
                'code' => 'REG-001',
                'description' => 'رسوم تسجيل الطالب في المدرسة',
                'category' => 'registration',
                'default_amount' => 500.00,
                'is_recurring' => false,
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'الرسوم الدراسية - الفصل الأول',
                'name_en' => 'Tuition Fee - First Semester',
                'code' => 'TUI-001',
                'description' => 'الرسوم الدراسية للفصل الدراسي الأول',
                'category' => 'tuition',
                'default_amount' => 3000.00,
                'is_recurring' => true,
                'recurring_period' => 'semester',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'الرسوم الدراسية - الفصل الثاني',
                'name_en' => 'Tuition Fee - Second Semester',
                'code' => 'TUI-002',
                'description' => 'الرسوم الدراسية للفصل الدراسي الثاني',
                'category' => 'tuition',
                'default_amount' => 3000.00,
                'is_recurring' => true,
                'recurring_period' => 'semester',
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'رسوم الكتب والقرطاسية',
                'name_en' => 'Books and Stationery Fee',
                'code' => 'BOOK-001',
                'description' => 'رسوم الكتب المدرسية والقرطاسية',
                'category' => 'book',
                'default_amount' => 500.00,
                'is_recurring' => false,
                'is_active' => true,
                'sort_order' => 4,
            ],
            [
                'name' => 'رسوم الزي المدرسي',
                'name_en' => 'Uniform Fee',
                'code' => 'UNI-001',
                'description' => 'رسوم الزي المدرسي الموحد',
                'category' => 'uniform',
                'default_amount' => 300.00,
                'is_recurring' => false,
                'is_active' => true,
                'sort_order' => 5,
            ],
            [
                'name' => 'رسوم النشاطات',
                'name_en' => 'Activity Fee',
                'code' => 'ACT-001',
                'description' => 'رسوم النشاطات المدرسية والرياضية',
                'category' => 'activity',
                'default_amount' => 200.00,
                'is_recurring' => false,
                'is_active' => true,
                'sort_order' => 6,
            ],
            [
                'name' => 'رسوم المواصلات',
                'name_en' => 'Transportation Fee',
                'code' => 'TRANS-001',
                'description' => 'رسوم النقل المدرسي',
                'category' => 'transport',
                'default_amount' => 800.00,
                'is_recurring' => true,
                'recurring_period' => 'monthly',
                'is_active' => true,
                'sort_order' => 7,
            ],
            [
                'name' => 'رسوم أخرى',
                'name_en' => 'Other Fees',
                'code' => 'OTH-001',
                'description' => 'رسوم إضافية أخرى',
                'category' => 'other',
                'default_amount' => 0.00,
                'is_recurring' => false,
                'is_active' => true,
                'sort_order' => 8,
            ],
        ];

        foreach ($feeTypes as $feeType) {
            FeeType::firstOrCreate(
                ['code' => $feeType['code']],
                $feeType
            );
        }

        $this->command->info('تم إنشاء ' . count($feeTypes) . ' نوع رسوم بنجاح!');
    }
}
