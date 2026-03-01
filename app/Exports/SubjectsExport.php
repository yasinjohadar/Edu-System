<?php

namespace App\Exports;

use App\Models\Subject;
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

class SubjectsExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle, ShouldAutoSize
{
    public function collection()
    {
        return Subject::with(['classes.grade', 'teachers.user'])->orderBy('name')->get();
    }

    public function headings(): array
    {
        return [
            'اسم المادة',
            'الاسم بالإنجليزية',
            'رمز المادة',
            'النوع',
            'الساعات الأسبوعية',
            'الدرجة الكاملة',
            'درجة النجاح',
            'الحالة',
            'الصفوف',
            'المعلمون',
            'الوصف',
        ];
    }

    public function map($subject): array
    {
        $classes = $subject->classes->map(function($class) {
            return $class->grade->name . ' - ' . $class->name;
        })->implode(', ');

        $teachers = $subject->teachers->map(function($teacher) {
            return $teacher->user->name;
        })->implode(', ');

        return [
            $subject->name,
            $subject->name_en ?? '',
            $subject->code ?? '',
            $subject->type ?? '',
            $subject->weekly_hours ?? 0,
            $subject->full_marks ?? 0,
            $subject->pass_marks ?? 0,
            $subject->is_active ? 'نشط' : 'غير نشط',
            $classes,
            $teachers,
            $subject->description ?? '',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:K1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 12, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '4472C4']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']]],
        ]);

        $sheet->getStyle('A2:K' . ($sheet->getHighestRow()))->applyFromArray([
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'CCCCCC']]],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
        ]);

        $sheet->getRowDimension(1)->setRowHeight(25);
        return $sheet;
    }

    public function title(): string
    {
        return 'المواد';
    }
}

