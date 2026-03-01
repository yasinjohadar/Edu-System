<?php

namespace App\Exports;

use App\Models\Payment;
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

class PaymentsExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle, ShouldAutoSize
{
    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $query = Payment::with(['student.user', 'invoice', 'receiver']);

        if (isset($this->filters['student_id'])) {
            $query->where('student_id', $this->filters['student_id']);
        }

        if (isset($this->filters['status'])) {
            $query->where('status', $this->filters['status']);
        }

        if (isset($this->filters['date_from'])) {
            $query->whereDate('payment_date', '>=', $this->filters['date_from']);
        }

        if (isset($this->filters['date_to'])) {
            $query->whereDate('payment_date', '<=', $this->filters['date_to']);
        }

        return $query->orderBy('payment_date', 'desc')->get();
    }

    public function headings(): array
    {
        return [
            'رقم الدفعة',
            'اسم الطالب',
            'رقم القيد',
            'رقم الفاتورة',
            'تاريخ الدفع',
            'المبلغ',
            'طريقة الدفع',
            'رقم المرجع',
            'اسم البنك',
            'الحالة',
            'استلم بواسطة',
            'تاريخ المعالجة',
            'ملاحظات',
        ];
    }

    public function map($payment): array
    {
        return [
            $payment->payment_number ?? '',
            $payment->student->user->name ?? '',
            $payment->student->student_code ?? '',
            $payment->invoice->invoice_number ?? '',
            $payment->payment_date->format('Y-m-d'),
            number_format($payment->amount, 2),
            $payment->payment_method_name,
            $payment->reference_number ?? '',
            $payment->bank_name ?? '',
            $payment->status_name,
            $payment->receiver->name ?? '',
            $payment->processed_at ? $payment->processed_at->format('Y-m-d H:i') : '',
            $payment->notes ?? '',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:M1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 12, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '4472C4']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']]],
        ]);

        $sheet->getStyle('A2:M' . ($sheet->getHighestRow()))->applyFromArray([
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'CCCCCC']]],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
        ]);

        $sheet->getRowDimension(1)->setRowHeight(25);
        return $sheet;
    }

    public function title(): string
    {
        return 'المدفوعات';
    }
}

