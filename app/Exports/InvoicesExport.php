<?php

namespace App\Exports;

use App\Models\Invoice;
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

class InvoicesExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle, ShouldAutoSize
{
    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $query = Invoice::with(['student.user', 'student.class.grade', 'items']);

        if (isset($this->filters['student_id'])) {
            $query->where('student_id', $this->filters['student_id']);
        }

        if (isset($this->filters['status'])) {
            $query->where('status', $this->filters['status']);
        }

        if (isset($this->filters['date_from'])) {
            $query->whereDate('invoice_date', '>=', $this->filters['date_from']);
        }

        if (isset($this->filters['date_to'])) {
            $query->whereDate('invoice_date', '<=', $this->filters['date_to']);
        }

        return $query->orderBy('invoice_date', 'desc')->get();
    }

    public function headings(): array
    {
        return [
            'رقم الفاتورة',
            'اسم الطالب',
            'رقم القيد',
            'المرحلة',
            'الصف',
            'تاريخ الفاتورة',
            'تاريخ الاستحقاق',
            'المجموع الفرعي',
            'الخصم',
            'الضريبة',
            'المبلغ الإجمالي',
            'المدفوع',
            'المتبقي',
            'الحالة',
            'تاريخ الدفع',
            'ملاحظات',
        ];
    }

    public function map($invoice): array
    {
        return [
            $invoice->invoice_number,
            $invoice->student->user->name ?? '',
            $invoice->student->student_code ?? '',
            $invoice->student->class && $invoice->student->class->grade ? $invoice->student->class->grade->name : '',
            $invoice->student->class ? $invoice->student->class->name : '',
            $invoice->invoice_date->format('Y-m-d'),
            $invoice->due_date->format('Y-m-d'),
            number_format($invoice->subtotal, 2),
            number_format($invoice->discount_amount, 2),
            number_format($invoice->tax_amount, 2),
            number_format($invoice->total_amount, 2),
            number_format($invoice->paid_amount, 2),
            number_format($invoice->remaining_amount, 2),
            $invoice->status_name,
            $invoice->paid_at ? $invoice->paid_at->format('Y-m-d H:i') : '',
            $invoice->notes ?? '',
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
        return 'الفواتير';
    }
}

