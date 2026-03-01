<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BookCategory;

class BookCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'علوم', 'name_en' => 'Science', 'code' => 'SCI', 'description' => 'كتب العلوم والفيزياء والكيمياء'],
            ['name' => 'رياضيات', 'name_en' => 'Mathematics', 'code' => 'MATH', 'description' => 'كتب الرياضيات والهندسة'],
            ['name' => 'لغة عربية', 'name_en' => 'Arabic', 'code' => 'ARB', 'description' => 'كتب اللغة العربية والأدب'],
            ['name' => 'لغة إنجليزية', 'name_en' => 'English', 'code' => 'ENG', 'description' => 'كتب اللغة الإنجليزية'],
            ['name' => 'تاريخ', 'name_en' => 'History', 'code' => 'HIS', 'description' => 'كتب التاريخ والجغرافيا'],
            ['name' => 'دين', 'name_en' => 'Religion', 'code' => 'REL', 'description' => 'كتب التربية الإسلامية'],
            ['name' => 'أدب', 'name_en' => 'Literature', 'code' => 'LIT', 'description' => 'كتب الأدب والشعر'],
            ['name' => 'قصص', 'name_en' => 'Stories', 'code' => 'STO', 'description' => 'كتب القصص والروايات'],
        ];

        foreach ($categories as $category) {
            BookCategory::firstOrCreate(
                ['code' => $category['code']],
                $category
            );
        }
    }
}
