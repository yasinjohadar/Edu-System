<?php

namespace App\Exports;

use App\Models\Student;
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

class StudentsExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle, ShouldAutoSize, WithColumnWidths
{
    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $query = Student::with(['user', 'class.grade', 'section', 'parents.user']);

        if (isset($this->filters['status'])) {
            $query->where('status', $this->filters['status']);
        }

        if (isset($this->filters['class_id'])) {
            $query->where('class_id', $this->filters['class_id']);
        }

        if (isset($this->filters['section_id'])) {
            $query->where('section_id', $this->filters['section_id']);
        }

        return $query->orderBy('created_at', 'desc')->get();
    }

    public function headings(): array
    {
        return [
            'رقم القيد',
            'اسم الطالب',
            'البريد الإلكتروني',
            'رقم الهاتف',
            'تاريخ الميلاد',
            'الجنس',
            'العنوان',
            'تاريخ التسجيل',
            'الحالة',
            'المرحلة',
            'الصف',
            'الفصل',
            'أولياء الأمور',
            'رقم الطوارئ',
            'ملاحظات طبية',
        ];
    }

    public function map($student): array
    {
        $parents = $student->parents->map(function($parent) {
            return $parent->user->name;
        })->implode(', ');

        return [
            $student->student_code,
            $student->user->name ?? '',
            $student->user->email ?? '',
            $student->user->phone ?? '',
            $student->date_of_birth ? $student->date_of_birth->format('Y-m-d') : '',
            $student->gender == 'male' ? 'ذكر' : ($student->gender == 'female' ? 'أنثى' : ''),
            $student->address ?? '',
            $student->enrollment_date ? $student->enrollment_date->format('Y-m-d') : '',
            $this->getStatusName($student->status),
            $student->class && $student->class->grade ? $student->class->grade->name : '',
            $student->class ? $student->class->name : '',
            $student->section ? $student->section->name : '',
            $parents ?: '',
            $student->emergency_contact ?? '',
            $student->medical_notes ?? '',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // تنسيق رأس الجدول
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

        // تنسيق الخلايا
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

        // تعيين ارتفاع الصف الأول
        $sheet->getRowDimension(1)->setRowHeight(25);

        return $sheet;
    }

    public function columnWidths(): array
    {
        return [
            'A' => 15,  // رقم القيد
            'B' => 25,  // اسم الطالب
            'C' => 30,  // البريد الإلكتروني
            'D' => 15,  // رقم الهاتف
            'E' => 15,  // تاريخ الميلاد
            'F' => 10,  // الجنس
            'G' => 30,  // العنوان
            'H' => 15,  // تاريخ التسجيل
            'I' => 15,  // الحالة
            'J' => 20,  // المرحلة
            'K' => 20,  // الصف
            'L' => 15,  // الفصل
            'M' => 30,  // أولياء الأمور
            'N' => 20,  // رقم الطوارئ
            'O' => 40,  // ملاحظات طبية
        ];
    }

    public function title(): string
    {
        return 'الطلاب';
    }

    private function getStatusName($status)
    {
        $statuses = [
            'active' => 'نشط',
            'graduated' => 'متخرج',
            'transferred' => 'منقول',
            'suspended' => 'معلق',
        ];

        return $statuses[$status] ?? $status;
    }
}

