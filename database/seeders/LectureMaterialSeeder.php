<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LectureMaterial;
use App\Models\OnlineLecture;

class LectureMaterialSeeder extends Seeder
{
    public function run(): void
    {
        $lectures = OnlineLecture::where('is_published', true)->get();

        if ($lectures->isEmpty()) {
            $this->command->warn('لا توجد محاضرات منشورة.');
            return;
        }

        $materialTypes = ['file', 'link', 'video', 'audio'];
        $materialTitles = [
            'ملخص المحاضرة',
            'الواجب المنزلي',
            'الشرائح التقديمية',
            'المراجع الإضافية',
            'الفيديو التوضيحي',
        ];

        foreach ($lectures->take(10) as $lecture) {
            for ($i = 0; $i < rand(2, 5); $i++) {
                $type = $materialTypes[array_rand($materialTypes)];
                
                LectureMaterial::create([
                    'lecture_id' => $lecture->id,
                    'title' => $materialTitles[array_rand($materialTitles)] . ' ' . ($i + 1),
                    'description' => 'وصف المادة التعليمية',
                    'type' => $type,
                    'external_url' => $type === 'link' ? 'https://example.com/material/' . rand(1000, 9999) : null,
                    'file_name' => $type === 'file' ? 'material_' . rand(1000, 9999) . '.pdf' : null,
                    'file_size' => $type === 'file' ? rand(1000000, 10000000) : null,
                    'mime_type' => $type === 'file' ? 'application/pdf' : null,
                    'sort_order' => $i,
                    'is_active' => true,
                    'download_count' => rand(0, 100),
                ]);
            }
        }
    }
}
