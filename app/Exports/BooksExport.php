<?php

namespace App\Exports;

use App\Models\Book;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class BooksExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle, ShouldAutoSize
{
    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $query = Book::with(['category']);

        if (isset($this->filters['category_id'])) {
            $query->where('category_id', $this->filters['category_id']);
        }

        return $query->orderBy('title')->get();
    }

    public function headings(): array
    {
        return [
            'ISBN',
            'عنوان الكتاب',
            'الاسم بالإنجليزية',
            'المؤلف',
            'الناشر',
            'سنة النشر',
            'اللغة',
            'التصنيف',
            'إجمالي النسخ',
            'النسخ المتاحة',
            'السعر',
            'عدد الصفحات',
            'الطبعة',
            'الموقع',
            'الحالة',
            'الوصف',
        ];
    }

    public function map($book): array
    {
        return [
            $book->isbn ?? '',
            $book->title,
            $book->title_en ?? '',
            $book->author ?? '',
            $book->publisher ?? '',
            $book->publication_year ?? '',
            $book->language ?? '',
            $book->category->name ?? '',
            $book->total_copies,
            $book->available_copies,
            $book->price ? number_format($book->price, 2) : '0.00',
            $book->pages ?? 0,
            $book->edition ?? '',
            $book->location ?? '',
            $book->is_active ? 'نشط' : 'غير نشط',
            $book->description ?? '',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:P1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 12, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '4472C4']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']]],
        ]);

        $sheet->getStyle('A2:P' . ($sheet->getHighestRow()))->applyFromArray([
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'CCCCCC']]],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
        ]);

        $sheet->getRowDimension(1)->setRowHeight(25);
        return $sheet;
    }

    public function title(): string
    {
        return 'الكتب';
    }
}

