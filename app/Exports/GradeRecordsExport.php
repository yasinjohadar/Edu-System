<?php

namespace App\Exports;

use App\Models\GradeRecord;
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

class GradeRecordsExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle, ShouldAutoSize
{
    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $query = GradeRecord::with(['student.user', 'subject', 'teacher.user']);

        if (isset($this->filters['student_id'])) {
            $query->where('student_id', $this->filters['student_id']);
        }

        if (isset($this->filters['subject_id'])) {
            $query->where('subject_id', $this->filters['subject_id']);
        }

        if (isset($this->filters['date_from'])) {
            $query->whereDate('exam_date', '>=', $this->filters['date_from']);
        }

        if (isset($this->filters['date_to'])) {
            $query->whereDate('exam_date', '<=', $this->filters['date_to']);
        }

        return $query->orderBy('exam_date', 'desc')->get();
    }

    public function headings(): array
    {
        return [
            'اسم الطالب',
            'رقم القيد',
            'المادة',
            'نوع التقييم',
            'اسم التقييم',
            'الدرجة المحصولة',
            'الدرجة الكاملة',
            'النسبة المئوية',
            'الدرجة الحرفية',
            'تاريخ التقييم',
            'السنة الأكاديمية',
            'الفصل الدراسي',
            'المعلم',
            'منشور',
            'ملاحظات',
        ];
    }

    public function map($record): array
    {
        return [
            $record->student->user->name ?? '',
            $record->student->student_code ?? '',
            $record->subject->name ?? '',
            $record->exam_type_name,
            $record->exam_name ?? '',
            number_format($record->marks_obtained, 2),
            number_format($record->total_marks, 2),
            number_format($record->percentage, 2) . '%',
            $record->grade ?? '',
            $record->exam_date ? $record->exam_date->format('Y-m-d') : '',
            $record->academic_year ?? '',
            $record->semester_name,
            $record->teacher && $record->teacher->user ? $record->teacher->user->name : '',
            $record->is_published ? 'نعم' : 'لا',
            $record->notes ?? '',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:O1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 12, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '4472C4']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']]],
        ]);

        $sheet->getStyle('A2:O' . ($sheet->getHighestRow()))->applyFromArray([
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'CCCCCC']]],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
        ]);

        $sheet->getRowDimension(1)->setRowHeight(25);
        return $sheet;
    }

    public function title(): string
    {
        return 'الدرجات';
    }
}

