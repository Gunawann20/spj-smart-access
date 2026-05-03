<?php

namespace App\Exports;

use App\Models\Rab;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class RabExport implements FromCollection, WithHeadings, WithColumnWidths, WithStyles
{
    protected $rab;

    public function __construct(Rab $rab)
    {
        $this->rab = $rab;
    }

    public function collection()
    {
        $items = $this->rab->items;
        $data = [];

        // Add header information
        $data[] = ['RENCANA ANGGARAN BIAYA (RAB)'];
        $data[] = [''];
        $data[] = ['Judul RAB', $this->rab->judul_rab];
        $data[] = ['Nomor RAB', $this->rab->nomor_rab];
        $data[] = ['Tanggal RAB', $this->rab->tanggal_rab->format('d-m-Y')];
        $data[] = ['Waktu Mulai', $this->rab->waktu_mulai ?? '-'];
        $data[] = ['Waktu Selesai', $this->rab->waktu_selesai ?? '-'];
        $data[] = ['Tempat Pelaksanaan', $this->rab->tempat_pelaksanaan ?? '-'];
        $data[] = ['Sumber Kegiatan (DIPA)', $this->rab->sumber_kegiatan ?? '-'];
        $data[] = ['Jenis Kegiatan', $this->rab->jenis_kegiatan ?? '-'];
        $data[] = ['Akun yang Digunakan', $this->rab->akun_yang_digunakan ?? '-'];
        $data[] = ['Tahun Anggaran', $this->rab->tahun_anggaran];
        $data[] = ['Keterangan', $this->rab->keterangan_rab ?? '-'];
        $data[] = ['Pemoton/Ketua Tim Kerja', $this->rab->nama_pemoton ?? '-'];
        $data[] = ['Direktur/Mengetahui', $this->rab->nama_direktur ?? '-'];
        $data[] = ['Pejabat Pembuat Komitmen', $this->rab->nama_pejabat ?? '-'];
        $data[] = [''];

        // Add detail items header
        $data[] = ['NO', 'URAIAN KEBUTUHAN', 'VOLUME', 'BIAYA SATUAN', 'JUMLAH KEBUTUHAN', 'POTONGAN 50%', 'PAJAK', 'JUMLAH PASCA PAJAK', 'SURAT UNDANGAN', 'KAK', 'MATERI', 'NOTULEN', 'ABSEN', 'KUITANSI'];

        // Add items data
        foreach ($items as $index => $item) {
            $data[] = [
                $index + 1,
                $item->uraian,
                $item->volume,
                $item->harga_satuan,
                $item->jumlah,
                $item->potongan_50_persen,
                $item->pajak,
                $item->jumlah_pasca_pajak,
                $item->surat_undangan ? 'Ya' : 'Tidak',
                $item->kak ? 'Ya' : 'Tidak',
                $item->materi ? 'Ya' : 'Tidak',
                $item->notulen ? 'Ya' : 'Tidak',
                $item->absen ? 'Ya' : 'Tidak',
                $item->kuitansi ? 'Ya' : 'Tidak',
            ];
        }

        // Add total row
        $totalJumlah = $items->sum('jumlah');
        $totalPotongan = $items->sum('potongan_50_persen');
        $totalPajak = $items->sum('pajak');
        $totalPascaPajak = $items->sum('jumlah_pasca_pajak');

        $data[] = ['', 'TOTAL', '', '', $totalJumlah, $totalPotongan, $totalPajak, $totalPascaPajak, '', '', '', '', '', ''];

        return collect($data);
    }

    public function headings(): array
    {
        return [];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 5,
            'B' => 30,
            'C' => 12,
            'D' => 15,
            'E' => 18,
            'F' => 15,
            'G' => 12,
            'H' => 18,
            'I' => 15,
            'J' => 12,
            'K' => 12,
            'L' => 12,
            'M' => 12,
            'N' => 12,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style header
            1 => [
                'font' => ['bold' => true, 'size' => 14],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            ],
            // Style info rows
            3 => ['font' => ['bold' => true]],
            4 => ['font' => ['bold' => true]],
            5 => ['font' => ['bold' => true]],
            6 => ['font' => ['bold' => true]],
            7 => ['font' => ['bold' => true]],
            8 => ['font' => ['bold' => true]],
            9 => ['font' => ['bold' => true]],
            10 => ['font' => ['bold' => true]],
            11 => ['font' => ['bold' => true]],
            12 => ['font' => ['bold' => true]],
            13 => ['font' => ['bold' => true]],
            14 => ['font' => ['bold' => true]],
            15 => ['font' => ['bold' => true]],
            16 => ['font' => ['bold' => true]],
            17 => ['font' => ['bold' => true]],
            18 => ['font' => ['bold' => true]],
        ];
    }
}
