<?php
// app/Exports/InvoicesExport.php

namespace App\Exports;

use App\Models\Invoice;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class InvoicesExport implements
    FromCollection,
    WithHeadings,
    WithMapping,
    WithStyles,
    ShouldAutoSize
{
    public function __construct(
        private int $userId,
        private int $year,
        private string $status = 'all'
    ) {
    }

    public function collection()
    {
        $query = Invoice::with(['customer'])
            ->where('user_id', $this->userId)
            ->whereYear('issue_date', $this->year)
            ->orderBy('issue_date');

        if ($this->status !== 'all') {
            $query->where('status', $this->status);
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [
            '#',
            'Invoice Number',
            'Customer',
            'Issue Date',
            'Due Date',
            'Status',
            'Total',
        ];
    }

    public function map($invoice): array
    {
        return [
            $invoice->id,
            $invoice->invoice_number,
            $invoice->customer->name,
            $invoice->issue_date->format('Y-m-d'),
            $invoice->due_date?->format('Y-m-d') ?? '—',
            ucfirst($invoice->status),
            '$' . number_format($invoice->total, 2),
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '212529']],
            ],
        ];
    }
}