<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BookBorrowing;
use App\Models\Book;
use App\Models\Student;
use Carbon\Carbon;

class BookBorrowingSeeder extends Seeder
{
    public function run(): void
    {
        $books = Book::where('available_copies', '>', 0)->get();
        $students = Student::where('status', 'active')->get();

        if ($books->isEmpty() || $students->isEmpty()) {
            $this->command->warn('لا توجد كتب أو طلاب متاحين.');
            return;
        }

        // إنشاء استعارات نشطة
        for ($i = 0; $i < 15; $i++) {
            $book = $books->random();
            $student = $students->random();
            
            $borrowDate = Carbon::now()->subDays(rand(1, 30));
            $dueDate = $borrowDate->copy()->addDays(14);

            BookBorrowing::create([
                'book_id' => $book->id,
                'student_id' => $student->id,
                'borrowing_number' => BookBorrowing::generateBorrowingNumber(),
                'borrow_date' => $borrowDate,
                'due_date' => $dueDate,
                'status' => rand(0, 1) ? 'borrowed' : 'overdue',
                'borrowed_by' => 1, // admin user
            ]);

            $book->updateAvailableCopies();
        }

        // إنشاء استعارات تم إرجاعها
        for ($i = 0; $i < 20; $i++) {
            $book = $books->random();
            $student = $students->random();
            
            $borrowDate = Carbon::now()->subDays(rand(30, 90));
            $dueDate = $borrowDate->copy()->addDays(14);
            $returnDate = $borrowDate->copy()->addDays(rand(5, 20));

            BookBorrowing::create([
                'book_id' => $book->id,
                'student_id' => $student->id,
                'borrowing_number' => BookBorrowing::generateBorrowingNumber(),
                'borrow_date' => $borrowDate,
                'due_date' => $dueDate,
                'return_date' => $returnDate,
                'status' => 'returned',
                'borrowed_by' => 1,
                'returned_by' => 1,
            ]);
        }
    }
}
