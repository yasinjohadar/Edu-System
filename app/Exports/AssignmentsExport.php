<?php

namespace App\Exports;

use App\Models\Assignment;
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

class AssignmentsExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle, ShouldAutoSize
{
    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $query = Assignment::with(['subject', 'section.class.grade', 'teacher.user']);

        if (isset($this->filters['subject_id'])) {
            $query->where('subject_id', $this->filters['subject_id']);
        }

        if (isset($this->filters['section_id'])) {
            $query->where('section_id', $this->filters['section_id']);
        }

        if (isset($this->filters['status'])) {
            $query->where('status', $this->filters['status']);
        }

        return $query->orderBy('created_at', 'desc')->get();
    }

    public function headings(): array
    {
        return [
            'رقم الواجب',
            'عنوان الواجب',
            'المادة',
            'المرحلة',
            'الصف',
            'الفصل',
            'المعلم',
            'الدرجة الكاملة',
            'الموعد النهائي',
            'وقت الانتهاء',
            'السماح بالتأخير',
            'عقوبة التأخير/يوم',
            'الحد الأقصى للتأخير',
            'الحد الأقصى للمحاولات',
            'السماح بإعادة التسليم',
            'أنواع التسليم',
            'الحالة',
            'نشط',
            'الوصف',
        ];
    }

    public function map($assignment): array
    {
        $submissionTypes = is_array($assignment->submission_types) 
            ? implode(', ', $assignment->submission_types) 
            : $assignment->submission_types;

        return [
            $assignment->assignment_number ?? '',
            $assignment->title,
            $assignment->subject->name ?? '',
            $assignment->section && $assignment->section->class && $assignment->section->class->grade ? $assignment->section->class->grade->name : '',
            $assignment->section && $assignment->section->class ? $assignment->section->class->name : '',
            $assignment->section->name ?? '',
            $assignment->teacher && $assignment->teacher->user ? $assignment->teacher->user->name : '',
            number_format($assignment->total_marks, 2),
            $assignment->due_date ? $assignment->due_date->format('Y-m-d') : '',
            $assignment->due_time ?? '',
            $assignment->allow_late_submission ? 'نعم' : 'لا',
            number_format($assignment->late_penalty_per_day, 2),
            $assignment->max_late_days ?? 0,
            $assignment->max_attempts ?? 1,
            $assignment->allow_resubmission ? 'نعم' : 'لا',
            $submissionTypes,
            $this->getStatusName($assignment->status),
            $assignment->is_active ? 'نعم' : 'لا',
            $assignment->description ?? '',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:S1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 12, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '4472C4']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']]],
        ]);

        $sheet->getStyle('A2:S' . ($sheet->getHighestRow()))->applyFromArray([
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'CCCCCC']]],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
        ]);

        $sheet->getRowDimension(1)->setRowHeight(25);
        return $sheet;
    }

    public function title(): string
    {
        return 'الواجبات';
    }

    private function getStatusName($status)
    {
        $statuses = [
            'draft' => 'مسودة',
            'published' => 'منشور',
            'closed' => 'مغلق',
        ];

        return $statuses[$status] ?? $status;
    }
}

