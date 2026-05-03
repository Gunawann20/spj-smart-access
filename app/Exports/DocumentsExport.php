<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class DocumentsExport implements FromCollection, WithHeadings, WithColumnWidths, WithStyles, WithEvents
{
    protected Collection $documents;

    public function __construct(Collection $documents)
    {
        $this->documents = $documents;
    }

    public function collection(): Collection
    {
        return $this->documents->map(function ($document) {
            return [
                'nama_dokumen' => $document->nama_dokumen,
                'uploader' => $document->user?->name ?? '-',
                'pelaksana' => $document->pelaksana ?? '-',
                'kode_ro' => $document->kode_ro ?? '-',
                'jumlah_anggaran' => $document->jumlah_anggaran !== null ? (float) $document->jumlah_anggaran : null,
                'status' => $document->status,
                'nama_verifikator' => $document->nama_verifikator ?? '-',
                'tanggal_sp2d' => $document->tanggal_sp2d ?? '-',
                'jumlah_anggaran_sp2d' => $document->jumlah_anggaran_sp2d !== null ? (float) $document->jumlah_anggaran_sp2d : null,
                'tanggal_dibuat' => $document->created_at?->format('Y-m-d H:i:s'),
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Nama Dokumen',
            'Uploader',
            'Pelaksana',
            'Kode RO',
            'Jumlah Anggaran',
            'Status',
            'Nama Verifikator',
            'Tanggal SP2D',
            'Jumlah Anggaran SP2D',
            'Tanggal Dibuat',
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 42,
            'B' => 22,
            'C' => 24,
            'D' => 18,
            'E' => 20,
            'F' => 14,
            'G' => 24,
            'H' => 16,
            'I' => 24,
            'J' => 22,
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF'],
                    'size' => 11,
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '1E3A8A'],
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $highestRow = $sheet->getHighestRow();

                // Freeze header and enable filter for better navigation.
                $sheet->freezePane('A2');
                $sheet->setAutoFilter('A1:J1');

                // Global alignment and table border.
                $sheet->getStyle("A1:J{$highestRow}")->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
                $sheet->getStyle("A1:J{$highestRow}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->getColor()->setRGB('D1D5DB');

                // Numeric format for budget columns.
                if ($highestRow >= 2) {
                    $sheet->getStyle("E2:E{$highestRow}")->getNumberFormat()->setFormatCode('#,##0');
                    $sheet->getStyle("I2:I{$highestRow}")->getNumberFormat()->setFormatCode('#,##0');
                }

                // Zebra rows for readability.
                for ($row = 2; $row <= $highestRow; $row++) {
                    if ($row % 2 === 0) {
                        $sheet->getStyle("A{$row}:J{$row}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('F8FAFC');
                    }

                    // Conditional color for status column.
                    $status = strtolower((string) $sheet->getCell("F{$row}")->getValue());
                    if ($status === 'approved') {
                        $sheet->getStyle("F{$row}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('DCFCE7');
                        $sheet->getStyle("F{$row}")->getFont()->getColor()->setRGB('166534');
                    } elseif ($status === 'pending') {
                        $sheet->getStyle("F{$row}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('FEF3C7');
                        $sheet->getStyle("F{$row}")->getFont()->getColor()->setRGB('92400E');
                    } elseif ($status === 'rejected') {
                        $sheet->getStyle("F{$row}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('FEE2E2');
                        $sheet->getStyle("F{$row}")->getFont()->getColor()->setRGB('991B1B');
                    }
                }

                // Slight row-height tuning.
                $sheet->getRowDimension(1)->setRowHeight(24);
                for ($row = 2; $row <= $highestRow; $row++) {
                    $sheet->getRowDimension($row)->setRowHeight(20);
                }
            },
        ];
    }
}
