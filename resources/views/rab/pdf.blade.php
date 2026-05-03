<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>RAB</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            color: #000;
        }

        .text-center {
            text-align: center;
        }

        .header-title {
            font-weight: bold;
            font-size: 14px;
        }

        .header-subtitle {
            font-size: 11px;
            font-weight: bold;
        }

        .info-table {
            width: 100%;
            margin-top: 10px;
            margin-bottom: 10px;
            font-size: 11px;
        }

        .info-table td {
            padding: 2px 0;
        }

        table.main-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10px;
        }

        table.main-table th,
        table.main-table td {
            border: 1px solid #000;
            padding: 4px;
        }

        table.main-table thead th {
            background-color: #f4dfc8;
            text-align: center;
            font-weight: bold;
        }

        .number {
            text-align: center;
        }

        .currency {
            text-align: right;
        }

        .total-row td {
            font-weight: bold;
        }

        .notes {
            margin-top: 10px;
            font-size: 10px;
        }

        .signature-table {
            width: 100%;
            margin-top: 50px;
            text-align: center;
            font-size: 11px;
        }

        .signature-table td {
            width: 33%;
            vertical-align: top;
        }

        .signature-space {
            height: 60px;
        }

        .signature-name {
            border-top: 1px solid #000;
            margin-top: 5px;
            padding-top: 3px;
            font-weight: bold;
        }
    </style>
</head>

<body>

    <!-- HEADER -->
    <div class="text-center">
        <div class="header-title">RAB RAPAT BIASA</div>
        <div class="header-subtitle">DIREKTORAT BINA AKSES PELAYANAN KB</div>
        <div class="header-subtitle">
            SATUAN KERJA DEPUTI BIDANG BINA KELUARGA BERENCANA DAN KESEHATAN REPRODUKSI
        </div>
    </div>

    <!-- INFO -->
    <table class="info-table">
        <tr>
            <td width="180">Judul Rapat</td>
            <td>: <strong>{{ $rab->judul_rab }}</strong></td>
        </tr>
        <tr>
            <td>Waktu Pelaksanaan</td>
            <td>: {{ \Carbon\Carbon::parse($rab->tanggal_rab)->translatedFormat('l, d F Y') }}</td>
        </tr>
        <tr>
            <td>Tempat Pelaksanaan</td>
            <td>: {{ $rab->tempat_pelaksanaan ?? '-' }}</td>
        </tr>
        <tr>
            <td>Sumber Kegiatan (DIPA)</td>
            <td>: {{ $rab->sumber_kegiatan ?? '-' }}</td>
        </tr>
        <tr>
            <td>Sumber Anggaran</td>
            <td>: {{ $rab->sumber_anggaran ?? '-' }}</td>
        </tr>
        <tr>
            <td>Akun yang digunakan</td>
            <td>: {{ $rab->akun_yang_digunakan ?? '-' }}</td>
        </tr>
    </table>

    <!-- TABLE -->
    <table class="main-table">
        <thead>
            <tr>
                <th rowspan="2" width="30">NO</th>
                <th rowspan="2" width="80">Akun</th>
                <th rowspan="2">Uraian Kebutuhan</th>
                <th rowspan="2" width="60">Volume Kegiatan</th>
                <th rowspan="2" width="70">Biaya Satuan</th>
                <th rowspan="2" width="80">Jumlah Kebutuhan</th>
                <th colspan="5">Kelengkapan Berkas SPJ</th>
                <th colspan="2">Kelengkapan Berkas DL</th>
                <th colspan="3">Kelengkapan Berkas Narasumber</th>
            </tr>
            <tr>
                <th width="35">Surat Undangan</th>
                <th width="35">KAK</th>
                <th width="35">Notulen</th>
                <th width="35">Absen</th>
                <th width="35">Kuitansi</th>
                <th width="35">Surat Tugas</th>
                <th width="35">Laporan</th>
                <th width="35">Materi</th>
                <th width="35">KTP</th>
                <th width="35">NPWP</th>
            </tr>
        </thead>

        <tbody>
            @forelse($rab->items as $index => $item)
                <tr>
                    <td class="number">{{ $index + 1 }}</td>
                    <td class="number">{{ substr($rab->nomor_rab, -6) }}</td>
                    <td>{{ $item->uraian }}</td>
                    <td class="number">{{ $item->volume }}</td>
                    <td class="currency">{{ number_format($item->harga_satuan, 0, ',', '.') }}</td>
                    <td class="currency">{{ number_format($item->jumlah, 0, ',', '.') }}</td>

                    <td class="number">{{ $item->surat_undangan ? 'v' : '' }}</td>
                    <td class="number">{{ $item->kak ? 'v' : '' }}</td>
                    <td class="number">{{ $item->notulen ? 'v' : '' }}</td>
                    <td class="number">{{ $item->absen ? 'v' : '' }}</td>
                    <td class="number">{{ $item->kuitansi ? 'v' : '' }}</td>
                    <td class="number">{{ $item->surat_tugas ? 'v' : '' }}</td>
                    <td class="number">{{ $item->laporan ? 'v' : '' }}</td>
                    <td class="number">{{ $item->materi ? 'v' : '' }}</td>
                    <td class="number">{{ $item->ktp ? 'v' : '' }}</td>
                    <td class="number">{{ $item->npwp ? 'v' : '' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="16" class="text-center">Tidak ada data</td>
                </tr>
            @endforelse

            <tr class="total-row">
                <td colspan="5" class="currency">TOTAL</td>
                <td class="currency">{{ number_format($rab->total_jumlah, 0, ',', '.') }}</td>
                <td colspan="10"></td>
            </tr>
        </tbody>
    </table>

    <!-- NOTES -->
    <div class="notes">
        * dari PTP atau PTM<br>
        ** bentuk SPJ : Kuitansi Konsumsi & Snack (sudah termasuk ongkir)
    </div>

    <!-- SIGNATURE -->
    <table class="signature-table">
        <tr>
            <td>
                Pemohon,<br><br>
                Ketua Tim Kerja Fasilitas Dukungan Manajemen<br>
                Direktorat Bina Akses Pelayanan KB

                <div class="signature-space"></div>
                <div class="signature-name">{{ $rab->nama_pemohon }}</div>
            </td>

            <td>
                Mengetahui,<br><br>
                Direktur Bina Akses Pelayanan KB

                <div class="signature-space"></div>
                <div class="signature-name">{{ $rab->nama_direktur }}</div>
            </td>

            <td>
                Jakarta, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}<br>
                Pejabat Pembuat Komitmen<br>
                Direktorat Bina Akses Pelayanan KB

                <div class="signature-space"></div>
                <div class="signature-name">{{ $rab->nama_pejabat }}</div>
            </td>
        </tr>
    </table>

</body>
</html>