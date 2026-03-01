<?php

namespace App\Exports;

use App\Models\Teacher;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class TeachersExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle, ShouldAutoSize, WithColumnWidths
{
    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $query = Teacher::with(['user', 'subjects', 'sections.class.grade']);

        if (isset($this->filters['status'])) {
            $query->where('status', $this->filters['status']);
        }

        return $query->orderBy('created_at', 'desc')->get();
    }

    public function headings(): array
    {
        return [
            'رقم المعلم',
            'اسم المعلم',
            'البريد الإلكتروني',
            'رقم الهاتف',
            'تاريخ الميلاد',
            'الجنس',
            'العنوان',
            'تاريخ التوظيف',
            'المؤهل',
            'التخصص',
            'سنوات الخبرة',
            'الراتب',
            'الحالة',
            'المواد',
            'الفصول',
        ];
    }

    public function map($teacher): array
    {
        $subjects = $teacher->subjects->pluck('name')->implode(', ');
        $sections = $teacher->sections->map(function($section) {
            return $section->name;
        })->implode(', ');

        return [
            $teacher->teacher_code ?? '',
            $teacher->user->name ?? '',
            $teacher->user->email ?? '',
            $teacher->user->phone ?? '',
            $teacher->date_of_birth ? $teacher->date_of_birth->format('Y-m-d') : '',
            $teacher->gender == 'male' ? 'ذكر' : ($teacher->gender == 'female' ? 'أنثى' : ''),
            $teacher->address ?? '',
            $teacher->hire_date ? $teacher->hire_date->format('Y-m-d') : '',
            $teacher->qualification ?? '',
            $teacher->specialization ?? '',
            $teacher->experience_years ?? 0,
            $teacher->salary ? number_format($teacher->salary, 2) : '0.00',
            $this->getStatusName($teacher->status),
            $subjects,
            $sections,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:O1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 12,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4472C4'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ]);

        $sheet->getStyle('A2:O' . ($sheet->getHighestRow()))->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'CCCCCC'],
                ],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        $sheet->getRowDimension(1)->setRowHeight(25);

        return $sheet;
    }

    public function columnWidths(): array
    {
        return [
            'A' => 15,
            'B' => 25,
            'C' => 30,
            'D' => 15,
            'E' => 15,
            'F' => 10,
            'G' => 30,
            'H' => 15,
            'I' => 20,
            'J' => 20,
            'K' => 15,
            'L' => 15,
            'M' => 15,
            'N' => 30,
            'O' => 30,
        ];
    }

    public function title(): string
    {
        return 'المعلمون';
    }

    private function getStatusName($status)
    {
        $statuses = [
            'active' => 'نشط',
            'inactive' => 'غير نشط',
        ];

        return $statuses[$status] ?? $status;
    }
}

