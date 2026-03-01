<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Book;
use App\Models\BookCategory;

class BookSeeder extends Seeder
{
    public function run(): void
    {
        $categories = BookCategory::all();
        
        if ($categories->isEmpty()) {
            $this->command->warn('لا توجد تصنيفات للكتب. يرجى تشغيل BookCategorySeeder أولاً.');
            return;
        }

        $books = [
            [
                'title' => 'مبادئ الفيزياء',
                'title_en' => 'Physics Principles',
                'author' => 'أحمد محمد',
                'publisher' => 'دار النشر العلمي',
                'publication_year' => 2020,
                'isbn' => '978-1234567890',
                'total_copies' => 10,
                'price' => 50.00,
                'pages' => 300,
                'edition' => 'الطبعة الأولى',
            ],
            [
                'title' => 'الرياضيات المتقدمة',
                'title_en' => 'Advanced Mathematics',
                'author' => 'سارة علي',
                'publisher' => 'مكتبة المعرفة',
                'publication_year' => 2021,
                'isbn' => '978-1234567891',
                'total_copies' => 8,
                'price' => 60.00,
                'pages' => 400,
                'edition' => 'الطبعة الثانية',
            ],
            [
                'title' => 'قواعد اللغة العربية',
                'title_en' => 'Arabic Grammar',
                'author' => 'محمد حسن',
                'publisher' => 'دار الثقافة',
                'publication_year' => 2019,
                'isbn' => '978-1234567892',
                'total_copies' => 15,
                'price' => 40.00,
                'pages' => 250,
                'edition' => 'الطبعة الثالثة',
            ],
            [
                'title' => 'English Grammar',
                'title_en' => 'English Grammar',
                'author' => 'John Smith',
                'publisher' => 'Education Press',
                'publication_year' => 2022,
                'isbn' => '978-1234567893',
                'total_copies' => 12,
                'price' => 55.00,
                'pages' => 350,
                'edition' => 'First Edition',
            ],
            [
                'title' => 'تاريخ العالم',
                'title_en' => 'World History',
                'author' => 'فاطمة أحمد',
                'publisher' => 'دار التاريخ',
                'publication_year' => 2020,
                'isbn' => '978-1234567894',
                'total_copies' => 6,
                'price' => 45.00,
                'pages' => 500,
                'edition' => 'الطبعة الأولى',
            ],
        ];

        foreach ($books as $index => $bookData) {
            $category = $categories[$index % $categories->count()];
            
            Book::firstOrCreate(
                ['isbn' => $bookData['isbn']],
                array_merge($bookData, [
                    'category_id' => $category->id,
                    'available_copies' => $bookData['total_copies'],
                    'language' => $index < 3 ? 'ar' : 'en',
                    'is_active' => true,
                ])
            );
        }

        // إضافة المزيد من الكتب
        for ($i = 0; $i < 20; $i++) {
            $category = $categories->random();
            Book::firstOrCreate(
                ['isbn' => '978-' . str_pad(1234567900 + $i, 10, '0', STR_PAD_LEFT)],
                [
                'category_id' => $category->id,
                'title' => 'كتاب ' . ($i + 1),
                'title_en' => 'Book ' . ($i + 1),
                'author' => 'مؤلف ' . ($i + 1),
                'publisher' => 'دار النشر ' . ($i + 1),
                'publication_year' => rand(2018, 2023),
                'isbn' => '978-' . str_pad(1234567900 + $i, 10, '0', STR_PAD_LEFT),
                'total_copies' => rand(5, 15),
                'available_copies' => rand(2, 12),
                'price' => rand(30, 100),
                'pages' => rand(200, 600),
                'edition' => 'الطبعة الأولى',
                'language' => rand(0, 1) ? 'ar' : 'en',
                'is_active' => true,
                ]
            );
        }
    }
}
