@extends('layouts.app')

@section('page-title')
    Upload RAB ke Agenda
@endsection

@section('title')
    Form Input Rencana Anggaran Biaya (RAB) - Format Biaya Rapat
@endsection

@section('content')
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-[#1e3a8a] mb-2"><i class="fas fa-file-invoice-dollar"></i> Rencana Anggaran Biaya (RAB)</h1>
            <p class="text-gray-600">Upload RAB untuk agenda: <strong>{{ $agenda->judul }}</strong></p>
        </div>

        <form action="{{ route('rab.store-for-agenda', $agenda) }}" method="POST" id="rabForm" class="space-y-6">
            @csrf

            <!-- Section: Header Rapat -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="bg-[#1e3a8a] text-white p-4">
                    <h2 class="text-lg font-bold m-0"><i class="fas fa-briefing"></i> Informasi Rapat</h2>
                </div>
                <div class="p-6 grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Judul Rapat -->
                    <div class="lg:col-span-2">
                        <label for="judul_rab" class="block text-sm font-bold text-gray-700 mb-2">
                            Judul Rapat <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            name="judul_rab" 
                            id="judul_rab"
                            class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-[#3b82f6] focus:border-transparent @error('judul_rab') border-red-500 @enderror"
                            placeholder="Contoh: Rapat Pemantauan Ketersediaan Alat dan Obat Kontrasepsi"
                            value="{{ old('judul_rab') }}"
                            required>
                        @error('judul_rab')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Tanggal Rapat -->
                    <div>
                        <label for="tanggal_rab" class="block text-sm font-bold text-gray-700 mb-2">
                            Tanggal Rapat <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="date" 
                            name="tanggal_rab" 
                            id="tanggal_rab"
                            class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-[#3b82f6] focus:border-transparent @error('tanggal_rab') border-red-500 @enderror"
                            value="{{ old('tanggal_rab', date('Y-m-d')) }}"
                            required>
                        @error('tanggal_rab')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Waktu Mulai -->
                    <div>
                        <label for="waktu_mulai" class="block text-sm font-bold text-gray-700 mb-2">
                            Waktu Pelaksanaan (Mulai)
                        </label>
                        <input 
                            type="time" 
                            name="waktu_mulai" 
                            id="waktu_mulai"
                            class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-[#3b82f6] focus:border-transparent"
                            value="{{ old('waktu_mulai') }}">
                    </div>

                    <!-- Waktu Selesai -->
                    <div>
                        <label for="waktu_selesai" class="block text-sm font-bold text-gray-700 mb-2">
                            Waktu Pelaksanaan (Selesai)
                        </label>
                        <input 
                            type="time" 
                            name="waktu_selesai" 
                            id="waktu_selesai"
                            class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-[#3b82f6] focus:border-transparent"
                            value="{{ old('waktu_selesai') }}">
                    </div>

                    <!-- Tempat Pelaksanaan -->
                    <div class="lg:col-span-2">
                        <label for="tempat_pelaksanaan" class="block text-sm font-bold text-gray-700 mb-2">
                            Tempat Pelaksanaan
                        </label>
                        <input 
                            type="text" 
                            name="tempat_pelaksanaan" 
                            id="tempat_pelaksanaan"
                            class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-[#3b82f6] focus:border-transparent"
                            placeholder="Contoh: Ruang Rapat Ditsesyan"
                            value="{{ old('tempat_pelaksanaan') }}">
                    </div>

                    <!-- Sumber Kegiatan -->
                    <div class="lg:col-span-2">
                        <label for="sumber_kegiatan" class="block text-sm font-bold text-gray-700 mb-2">
                            Sumber Kegiatan / DIPA
                        </label>
                        <textarea 
                            name="sumber_kegiatan" 
                            id="sumber_kegiatan"
                            rows="2"
                            class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-[#3b82f6] focus:border-transparent"
                            placeholder="Contoh: Pemantauan dan Evaluasi Pembinaan Akses Pelayanan KB (089 B)">{{ old('sumber_kegiatan') }}</textarea>
                    </div>

                    <!-- Sumber Anggaran -->
                    <div>
                        <label for="sumber_anggaran" class="block text-sm font-bold text-gray-700 mb-2">
                            Sumber Anggaran
                        </label>
                        <input 
                            type="text" 
                            name="sumber_anggaran" 
                            id="sumber_anggaran"
                            class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-[#3b82f6] focus:border-transparent"
                            placeholder="Masukkan sumber anggaran"
                            value="{{ old('sumber_anggaran') }}">
                    </div>

                    <!-- Akun yang Digunakan -->
                    <div>
                        <label for="akun_yang_digunakan" class="block text-sm font-bold text-gray-700 mb-2">
                            Akun yang Digunakan
                        </label>
                        <input 
                            type="text" 
                            name="akun_yang_digunakan" 
                            id="akun_yang_digunakan"
                            class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-[#3b82f6] focus:border-transparent"
                            placeholder="Contoh: 068.01.649505.3316.FAE.001.089.B.521211"
                            value="{{ old('akun_yang_digunakan') }}">
                    </div>

                    <!-- Tahun Anggaran -->
                    <div>
                        <label for="tahun_anggaran" class="block text-sm font-bold text-gray-700 mb-2">
                            Tahun Anggaran <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="number" 
                            name="tahun_anggaran" 
                            id="tahun_anggaran"
                            class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-[#3b82f6] focus:border-transparent @error('tahun_anggaran') border-red-500 @enderror"
                            placeholder="2026"
                            value="{{ old('tahun_anggaran', date('Y')) }}"
                            min="2020"
                            max="2099"
                            required>
                        @error('tahun_anggaran')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Keterangan -->
                    <div class="lg:col-span-2">
                        <label for="keterangan_rab" class="block text-sm font-bold text-gray-700 mb-2">
                            Keterangan Tambahan
                        </label>
                        <textarea 
                            name="keterangan_rab" 
                            id="keterangan_rab"
                            rows="2"
                            class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-[#3b82f6] focus:border-transparent">{{ old('keterangan_rab') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Section: Detail Item RAB -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="bg-[#1e3a8a] text-white p-4 flex justify-between items-center">
                    <h2 class="text-lg font-bold m-0"><i class="fas fa-table"></i> Detail Kebutuhan & SPJ</h2>
                    <button type="button" id="addRowBtn" class="bg-[#f59e0b] hover:bg-[#d97706] px-4 py-2 rounded text-sm font-semibold transition">
                        <i class="fas fa-plus"></i> Tambah Item
                    </button>
                </div>
                <div class="p-6 overflow-x-auto">
                    <table class="w-full border-collapse text-sm" id="rabTable">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="border border-gray-300 px-2 py-2 text-center font-bold text-gray-700" style="width: 3%">NO</th>
                                <th class="border border-gray-300 px-2 py-2 text-left font-bold text-gray-700" style="width: 18%">Akun / Uraian Kebutuhan</th>
                                <th class="border border-gray-300 px-2 py-2 text-center font-bold text-gray-700" style="width: 6%">Volume</th>
                                <th class="border border-gray-300 px-2 py-2 text-center font-bold text-gray-700" style="width: 6%">Biaya Satuan</th>
                                <th class="border border-gray-300 px-2 py-2 text-center font-bold text-gray-700" style="width: 8%">Jumlah Kebutuhan</th>
                                <th class="border border-gray-300 px-2 py-2 text-center font-bold text-gray-700" style="width: 7%">Potongan 50%</th>
                                <th class="border border-gray-300 px-2 py-2 text-center font-bold text-gray-700" style="width: 6%">Pajak</th>
                                <th class="border border-gray-300 px-2 py-2 text-center font-bold text-gray-700" style="width: 9%">Jmlh Pasca Pajak</th>
                                <th colspan="6" class="border border-gray-300 px-2 py-2 text-center font-bold text-gray-700 bg-orange-100">Kelengkapan Berkas SPJ</th>
                                <th class="border border-gray-300 px-2 py-2 text-center font-bold text-gray-700" style="width: 4%">Aksi</th>
                            </tr>
                            <tr class="bg-orange-50">
                                <th colspan="8" class="border border-gray-300 px-2 py-1 text-center text-xs font-semibold"></th>
                                <th class="border border-gray-300 px-1 py-1 text-center text-xs font-semibold">Surat</th>
                                <th class="border border-gray-300 px-1 py-1 text-center text-xs font-semibold">KAK</th>
                                <th class="border border-gray-300 px-1 py-1 text-center text-xs font-semibold">Materi</th>
                                <th class="border border-gray-300 px-1 py-1 text-center text-xs font-semibold">Notulen</th>
                                <th class="border border-gray-300 px-1 py-1 text-center text-xs font-semibold">Absen</th>
                                <th class="border border-gray-300 px-1 py-1 text-center text-xs font-semibold">Kuitansi</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="rabTableBody">
                            <tr class="rab-item-row" data-row="0">
                                <td class="border border-gray-300 px-2 py-2 text-center row-number">1</td>
                                <td class="border border-gray-300 px-2 py-2">
                                    <input type="text" name="items[0][uraian]" class="w-full px-1 py-1 border border-gray-300 rounded text-xs focus:ring-2 focus:ring-[#3b82f6]" placeholder="Uraian" required>
                                </td>
                                <td class="border border-gray-300 px-2 py-2">
                                    <input type="number" name="items[0][volume]" class="w-full px-1 py-1 border border-gray-300 rounded text-xs text-center volume-input" placeholder="0" step="0.01" required>
                                </td>
                                <td class="border border-gray-300 px-2 py-2">
                                    <input type="number" name="items[0][harga_satuan]" class="w-full px-1 py-1 border border-gray-300 rounded text-xs text-right harga-input" placeholder="0" step="0.01" required>
                                </td>
                                <td class="border border-gray-300 px-2 py-2">
                                    <input type="number" name="items[0][jumlah]" class="w-full px-1 py-1 border border-gray-300 rounded text-xs text-right bg-gray-50 jumlah-input" placeholder="0" readonly>
                                </td>
                                <td class="border border-gray-300 px-2 py-2">
                                    <input type="number" name="items[0][potongan_50_persen]" class="w-full px-1 py-1 border border-gray-300 rounded text-xs text-right potongan-input" placeholder="0" step="0.01">
                                </td>
                                <td class="border border-gray-300 px-2 py-2">
                                    <input type="number" name="items[0][pajak]" class="w-full px-1 py-1 border border-gray-300 rounded text-xs text-right pajak-input" placeholder="0" step="0.01">
                                </td>
                                <td class="border border-gray-300 px-2 py-2">
                                    <input type="number" name="items[0][jumlah_pasca_pajak]" class="w-full px-1 py-1 border border-gray-300 rounded text-xs text-right bg-yellow-50 pasca-pajak-input" placeholder="0" readonly>
                                </td>
                                <td class="border border-gray-300 px-2 py-2 text-center">
                                    <input type="checkbox" name="items[0][surat_undangan]" class="surat-checkbox" value="1">
                                </td>
                                <td class="border border-gray-300 px-2 py-2 text-center">
                                    <input type="checkbox" name="items[0][kak]" class="kak-checkbox" value="1">
                                </td>
                                <td class="border border-gray-300 px-2 py-2 text-center">
                                    <input type="checkbox" name="items[0][materi]" class="materi-checkbox" value="1">
                                </td>
                                <td class="border border-gray-300 px-2 py-2 text-center">
                                    <input type="checkbox" name="items[0][notulen]" class="notulen-checkbox" value="1">
                                </td>
                                <td class="border border-gray-300 px-2 py-2 text-center">
                                    <input type="checkbox" name="items[0][absen]" class="absen-checkbox" value="1">
                                </td>
                                <td class="border border-gray-300 px-2 py-2 text-center">
                                    <input type="checkbox" name="items[0][kuitansi]" class="kuitansi-checkbox" value="1">
                                </td>
                                <td class="border border-gray-300 px-2 py-2 text-center">
                                    <button type="button" class="delete-row-btn bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded text-xs">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr class="bg-gray-100 font-bold">
                                <td colspan="4" class="border border-gray-300 px-2 py-2 text-right">TOTAL:</td>
                                <td class="border border-gray-300 px-2 py-2">
                                    <input type="number" class="w-full px-1 py-1 border border-gray-300 rounded text-xs text-right total-jumlah" readonly>
                                </td>
                                <td class="border border-gray-300 px-2 py-2">
                                    <input type="number" class="w-full px-1 py-1 border border-gray-300 rounded text-xs text-right total-potongan" readonly>
                                </td>
                                <td class="border border-gray-300 px-2 py-2">
                                    <input type="number" class="w-full px-1 py-1 border border-gray-300 rounded text-xs text-right total-pajak" readonly>
                                </td>
                                <td class="border border-gray-300 px-2 py-2">
                                    <input type="number" class="w-full px-1 py-1 border border-gray-300 rounded text-xs text-right bg-yellow-100 total-pasca-pajak" readonly>
                                </td>
                                <td colspan="6" class="border border-gray-300 px-2 py-2"></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <!-- Section: Tanda Tangan & Persetujuan -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="bg-[#1e3a8a] text-white p-4">
                    <h2 class="text-lg font-bold m-0"><i class="fas fa-pen-fancy"></i> Tanda Tangan & Persetujuan</h2>
                </div>
                <div class="p-6 grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Pemoton -->
                    <div>
                        <label for="nama_pemoton" class="block text-sm font-bold text-gray-700 mb-2">
                            Nama Pemoton / Ketua Tim Kerja
                        </label>
                        <textarea 
                            name="nama_pemoton" 
                            id="nama_pemoton"
                            rows="4"
                            class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-[#3b82f6] focus:border-transparent text-xs"
                            placeholder="Nama dan jabatan pemoton/ketua tim kerja">{{ old('nama_pemoton') }}</textarea>
                    </div>

                    <!-- Direktur -->
                    <div>
                        <label for="nama_direktur" class="block text-sm font-bold text-gray-700 mb-2">
                            Nama Direktur / Mengetahui
                        </label>
                        <textarea 
                            name="nama_direktur" 
                            id="nama_direktur"
                            rows="4"
                            class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-[#3b82f6] focus:border-transparent text-xs"
                            placeholder="Nama dan jabatan direktur/pengetahui">{{ old('nama_direktur') }}</textarea>
                    </div>

                    <!-- Pejabat Pembuat Komitmen -->
                    <div>
                        <label for="nama_pejabat" class="block text-sm font-bold text-gray-700 mb-2">
                            Nama Pejabat Pembuat Komitmen
                        </label>
                        <textarea 
                            name="nama_pejabat" 
                            id="nama_pejabat"
                            rows="4"
                            class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-[#3b82f6] focus:border-transparent text-xs"
                            placeholder="Nama dan jabatan pejabat pembuat komitmen">{{ old('nama_pejabat') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Submission Buttons -->
            <div class="flex gap-3">
                <input type="hidden" name="submit_action" id="submit_action" value="draft">
                <button type="submit" onclick="document.getElementById('submit_action').value='draft'" class="flex-1 bg-gray-500 hover:bg-gray-600 text-white font-bold py-3 rounded-lg transition transform hover:scale-105">
                    <i class="fas fa-save"></i> Simpan Draft
                </button>
                <button type="submit" onclick="document.getElementById('submit_action').value='submit'" class="flex-1 bg-gradient-to-r from-[#10b981] to-[#059669] hover:from-[#059669] hover:to-[#047857] text-white font-bold py-3 rounded-lg transition transform hover:scale-105">
                    <i class="fas fa-paper-plane"></i> Ajukan RAB ke Agenda
                </button>
                <a href="{{ route('agenda.show', $agenda) }}" class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-3 rounded-lg text-center transition">
                    <i class="fas fa-times"></i> Batal
                </a>
            </div>
        </form>
    </div>

    <script>
        const tableBody = document.getElementById('rabTableBody');
        const addRowBtn = document.getElementById('addRowBtn');
        const rabForm = document.getElementById('rabForm');
        let rowCount = 1;

        // Function untuk menambah baris baru
        function addNewRow() {
            const newRow = document.createElement('tr');
            newRow.className = 'rab-item-row';
            newRow.dataset.row = rowCount;
            newRow.innerHTML = `
                <td class="border border-gray-300 px-2 py-2 text-center row-number">${rowCount + 1}</td>
                <td class="border border-gray-300 px-2 py-2">
                    <input type="text" name="items[${rowCount}][uraian]" class="w-full px-1 py-1 border border-gray-300 rounded text-xs focus:ring-2 focus:ring-[#3b82f6]" placeholder="Uraian" required>
                </td>
                <td class="border border-gray-300 px-2 py-2">
                    <input type="number" name="items[${rowCount}][volume]" class="w-full px-1 py-1 border border-gray-300 rounded text-xs text-center volume-input" placeholder="0" step="0.01" required>
                </td>
                <td class="border border-gray-300 px-2 py-2">
                    <input type="number" name="items[${rowCount}][harga_satuan]" class="w-full px-1 py-1 border border-gray-300 rounded text-xs text-right harga-input" placeholder="0" step="0.01" required>
                </td>
                <td class="border border-gray-300 px-2 py-2">
                    <input type="number" name="items[${rowCount}][jumlah]" class="w-full px-1 py-1 border border-gray-300 rounded text-xs text-right bg-gray-50 jumlah-input" placeholder="0" readonly>
                </td>
                <td class="border border-gray-300 px-2 py-2">
                    <input type="number" name="items[${rowCount}][potongan_50_persen]" class="w-full px-1 py-1 border border-gray-300 rounded text-xs text-right potongan-input" placeholder="0" step="0.01">
                </td>
                <td class="border border-gray-300 px-2 py-2">
                    <input type="number" name="items[${rowCount}][pajak]" class="w-full px-1 py-1 border border-gray-300 rounded text-xs text-right pajak-input" placeholder="0" step="0.01">
                </td>
                <td class="border border-gray-300 px-2 py-2">
                    <input type="number" name="items[${rowCount}][jumlah_pasca_pajak]" class="w-full px-1 py-1 border border-gray-300 rounded text-xs text-right bg-yellow-50 pasca-pajak-input" placeholder="0" readonly>
                </td>
                <td class="border border-gray-300 px-2 py-2 text-center">
                    <input type="checkbox" name="items[${rowCount}][surat_undangan]" class="surat-checkbox" value="1">
                </td>
                <td class="border border-gray-300 px-2 py-2 text-center">
                    <input type="checkbox" name="items[${rowCount}][kak]" class="kak-checkbox" value="1">
                </td>
                <td class="border border-gray-300 px-2 py-2 text-center">
                    <input type="checkbox" name="items[${rowCount}][materi]" class="materi-checkbox" value="1">
                </td>
                <td class="border border-gray-300 px-2 py-2 text-center">
                    <input type="checkbox" name="items[${rowCount}][notulen]" class="notulen-checkbox" value="1">
                </td>
                <td class="border border-gray-300 px-2 py-2 text-center">
                    <input type="checkbox" name="items[${rowCount}][absen]" class="absen-checkbox" value="1">
                </td>
                <td class="border border-gray-300 px-2 py-2 text-center">
                    <input type="checkbox" name="items[${rowCount}][kuitansi]" class="kuitansi-checkbox" value="1">
                </td>
                <td class="border border-gray-300 px-2 py-2 text-center">
                    <button type="button" class="delete-row-btn bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded text-xs">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            `;
            
            tableBody.appendChild(newRow);
            attachRowListeners(newRow);
            rowCount++;
            updateRowNumbers();
        }

        // Function untuk attach listeners ke baris
        function attachRowListeners(row) {
            const volumeInput = row.querySelector('.volume-input');
            const hargaInput = row.querySelector('.harga-input');
            const jumlahInput = row.querySelector('.jumlah-input');
            const potonganInput = row.querySelector('.potongan-input');
            const pajakInput = row.querySelector('.pajak-input');
            const pascaPajakInput = row.querySelector('.pasca-pajak-input');
            const deleteBtn = row.querySelector('.delete-row-btn');

            // Hitung jumlah saat volume atau harga berubah
            [volumeInput, hargaInput].forEach(input => {
                input.addEventListener('change', calculateRow);
                input.addEventListener('input', calculateRow);
            });

            // Hitung pajak dan potongan
            [potonganInput, pajakInput].forEach(input => {
                input.addEventListener('change', calculateRow);
                input.addEventListener('input', calculateRow);
            });

            function calculateRow() {
                const volume = parseFloat(volumeInput.value) || 0;
                const harga = parseFloat(hargaInput.value) || 0;
                const jumlah = volume * harga;
                const potongan = parseFloat(potonganInput.value) || 0;
                const pajak = parseFloat(pajakInput.value) || 0;
                const pascaPajak = jumlah - potongan + pajak;

                jumlahInput.value = jumlah.toFixed(2);
                pascaPajakInput.value = pascaPajak.toFixed(2);
                updateTotal();
            }

            // Hapus baris
            deleteBtn.addEventListener('click', () => {
                if (tableBody.querySelectorAll('tr').length > 1) {
                    row.remove();
                    updateRowNumbers();
                    updateTotal();
                } else {
                    alert('Minimal harus ada 1 item RAB!');
                }
            });
        }

        // Update nomor urut baris
        function updateRowNumbers() {
            tableBody.querySelectorAll('tr').forEach((row, index) => {
                row.querySelector('.row-number').textContent = index + 1;
            });
        }

        // Update total
        function updateTotal() {
            let totalJumlah = 0;
            let totalPotongan = 0;
            let totalPajak = 0;
            let totalPascaPajak = 0;

            tableBody.querySelectorAll('tr').forEach(row => {
                const jumlah = parseFloat(row.querySelector('.jumlah-input').value) || 0;
                const potongan = parseFloat(row.querySelector('.potongan-input').value) || 0;
                const pajak = parseFloat(row.querySelector('.pajak-input').value) || 0;
                const pascaPajak = parseFloat(row.querySelector('.pasca-pajak-input').value) || 0;

                totalJumlah += jumlah;
                totalPotongan += potongan;
                totalPajak += pajak;
                totalPascaPajak += pascaPajak;
            });

            document.querySelector('.total-jumlah').value = totalJumlah.toFixed(2);
            document.querySelector('.total-potongan').value = totalPotongan.toFixed(2);
            document.querySelector('.total-pajak').value = totalPajak.toFixed(2);
            document.querySelector('.total-pasca-pajak').value = totalPascaPajak.toFixed(2);
        }

        // Event listeners
        addRowBtn.addEventListener('click', addNewRow);

        // Attach listeners ke baris pertama
        document.querySelectorAll('.rab-item-row').forEach(row => {
            attachRowListeners(row);
        });

        // Form validation dan submit
        rabForm.addEventListener('submit', function(e) {
            const items = tableBody.querySelectorAll('tr');
            if (items.length === 0) {
                e.preventDefault();
                alert('Minimal harus ada 1 item RAB!');
                return false;
            }
        });

        // Initial calculation
        updateTotal();
    </script>
@endsection
