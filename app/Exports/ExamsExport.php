<?php

namespace App\Exports;

use App\Models\Exam;
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

class ExamsExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle, ShouldAutoSize
{
    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $query = Exam::with(['subject', 'section.class.grade', 'teacher.user']);

        if (isset($this->filters['subject_id'])) {
            $query->where('subject_id', $this->filters['subject_id']);
        }

        if (isset($this->filters['section_id'])) {
            $query->where('section_id', $this->filters['section_id']);
        }

        return $query->orderBy('created_at', 'desc')->get();
    }

    public function headings(): array
    {
        return [
            'رمز الاختبار',
            'عنوان الاختبار',
            'المادة',
            'المرحلة',
            'الصف',
            'الفصل',
            'المعلم',
            'النوع',
            'المدة (دقيقة)',
            'الدرجة الكاملة',
            'درجة النجاح',
            'وقت البدء',
            'وقت الانتهاء',
            'منشور',
            'نشط',
            'الوصف',
        ];
    }

    public function map($exam): array
    {
        return [
            $exam->exam_code ?? '',
            $exam->title,
            $exam->subject->name ?? '',
            $exam->section && $exam->section->class && $exam->section->class->grade ? $exam->section->class->grade->name : '',
            $exam->section && $exam->section->class ? $exam->section->class->name : '',
            $exam->section->name ?? '',
            $exam->teacher && $exam->teacher->user ? $exam->teacher->user->name : '',
            $exam->type ?? '',
            $exam->duration ?? 0,
            $exam->total_marks ?? 0,
            $exam->passing_marks ?? 0,
            $exam->start_time ? $exam->start_time->format('Y-m-d H:i') : '',
            $exam->end_time ? $exam->end_time->format('Y-m-d H:i') : '',
            $exam->is_published ? 'نعم' : 'لا',
            $exam->is_active ? 'نعم' : 'لا',
            $exam->description ?? '',
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
        return 'الاختبارات';
    }
}

