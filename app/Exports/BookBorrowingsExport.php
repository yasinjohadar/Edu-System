<?php

namespace App\Exports;

use App\Models\BookBorrowing;
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

class BookBorrowingsExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle, ShouldAutoSize
{
    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $query = BookBorrowing::with(['book.category', 'student.user', 'borrower', 'returner']);

        if (isset($this->filters['student_id'])) {
            $query->where('student_id', $this->filters['student_id']);
        }

        if (isset($this->filters['status'])) {
            $query->where('status', $this->filters['status']);
        }

        if (isset($this->filters['date_from'])) {
            $query->whereDate('borrow_date', '>=', $this->filters['date_from']);
        }

        if (isset($this->filters['date_to'])) {
            $query->whereDate('borrow_date', '<=', $this->filters['date_to']);
        }

        return $query->orderBy('borrow_date', 'desc')->get();
    }

    public function headings(): array
    {
        return [
            'رقم الاستعارة',
            'اسم الطالب',
            'رقم القيد',
            'عنوان الكتاب',
            'ISBN',
            'التصنيف',
            'تاريخ الاستعارة',
            'تاريخ الإرجاع المتوقع',
            'تاريخ الإرجاع الفعلي',
            'الحالة',
            'أيام التأخير',
            'استعار بواسطة',
            'أُعيد بواسطة',
            'ملاحظات',
        ];
    }

    public function map($borrowing): array
    {
        return [
            $borrowing->borrowing_number ?? '',
            $borrowing->student->user->name ?? '',
            $borrowing->student->student_code ?? '',
            $borrowing->book->title ?? '',
            $borrowing->book->isbn ?? '',
            $borrowing->book->category->name ?? '',
            $borrowing->borrow_date->format('Y-m-d'),
            $borrowing->due_date->format('Y-m-d'),
            $borrowing->return_date ? $borrowing->return_date->format('Y-m-d') : '',
            $this->getStatusName($borrowing->status),
            $borrowing->days_overdue,
            $borrowing->borrower->name ?? '',
            $borrowing->returner->name ?? '',
            $borrowing->notes ?? '',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:N1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 12, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '4472C4']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']]],
        ]);

        $sheet->getStyle('A2:N' . ($sheet->getHighestRow()))->applyFromArray([
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'CCCCCC']]],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
        ]);

        $sheet->getRowDimension(1)->setRowHeight(25);
        return $sheet;
    }

    public function title(): string
    {
        return 'الاستعارات';
    }

    private function getStatusName($status)
    {
        $statuses = [
            'borrowed' => 'مستعار',
            'returned' => 'مُعاد',
            'overdue' => 'متأخر',
        ];

        return $statuses[$status] ?? $status;
    }
}

