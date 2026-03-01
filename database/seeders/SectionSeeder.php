<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ClassModel;
use App\Models\Section;

class SectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $classes = ClassModel::where('is_active', true)->get();

        foreach ($classes as $class) {
            $sections = [
                ['name' => 'أ', 'name_en' => 'A', 'capacity' => 30],
                ['name' => 'ب', 'name_en' => 'B', 'capacity' => 30],
                ['name' => 'ج', 'name_en' => 'C', 'capacity' => 30],
            ];

            foreach ($sections as $section) {
                Section::firstOrCreate(
                    ['name' => $section['name'], 'class_id' => $class->id],
                    array_merge($section, [
                        'class_id' => $class->id,
                        'is_active' => true,
                        'current_students' => 0,
                    ])
                );
            }
        }
    }
}
