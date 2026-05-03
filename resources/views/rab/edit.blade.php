@extends('layouts.app')

@section('page-title')
    Edit RAB
@endsection

@section('title')
    Edit Rencana Anggaran Biaya (RAB)
@endsection

@section('content')
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-[#1e3a8a] mb-2"><i class="fas fa-file-invoice-dollar"></i> Edit Rencana Anggaran Biaya (RAB)</h1>
            <p class="text-gray-600">Edit dokumen RAB yang sudah ada</p>
        </div>

        <form action="{{ route('rab.update', $rab) }}" method="POST" id="rabForm" class="space-y-6">
            @csrf
            @method('PUT')

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
                            value="{{ old('judul_rab', $rab->judul_rab) }}"
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
                            value="{{ old('tanggal_rab', $rab->tanggal_rab->format('Y-m-d')) }}"
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
                            value="{{ old('waktu_mulai', $rab->waktu_mulai ? \Carbon\Carbon::parse($rab->waktu_mulai)->format('H:i') : '') }}">
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
                            value="{{ old('waktu_selesai', $rab->waktu_selesai ? \Carbon\Carbon::parse($rab->waktu_selesai)->format('H:i') : '') }}">
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
                            value="{{ old('tempat_pelaksanaan', $rab->tempat_pelaksanaan) }}">
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
                            placeholder="Contoh: Pemantauan dan Evaluasi Pembinaan Akses Pelayanan KB (089 B)">{{ old('sumber_kegiatan', $rab->sumber_kegiatan) }}</textarea>
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
                            value="{{ old('sumber_anggaran', $rab->sumber_anggaran) }}">
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
                            value="{{ old('akun_yang_digunakan', $rab->akun_yang_digunakan) }}">
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
                            value="{{ old('tahun_anggaran', $rab->tahun_anggaran) }}"
                            min="2020"
                            max="2099"
                            required>
                        @error('tahun_anggaran')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Agenda Terkait -->
                    <div>
                        <label for="agenda_id" class="block text-sm font-bold text-gray-700 mb-2">
                            Agenda Terkait (Opsional)
                        </label>
                        <select 
                            name="agenda_id" 
                            id="agenda_id"
                            class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-[#3b82f6] focus:border-transparent @error('agenda_id') border-red-500 @enderror">
                            <option value="">-- Pilih Agenda --</option>
                            @foreach($agendas as $agenda)
                                <option value="{{ $agenda->id }}" {{ old('agenda_id', $rab->agenda_id) == $agenda->id ? 'selected' : '' }}>
                                    {{ $agenda->judul }}
                                </option>
                            @endforeach
                        </select>
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
                            class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-[#3b82f6] focus:border-transparent">{{ old('keterangan_rab', $rab->keterangan_rab) }}</textarea>
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
                                <th colspan="5" class="border border-gray-300 px-2 py-2 text-center font-bold text-gray-700 bg-orange-100">Kelengkapan Berkas SPJ</th>
                                <th colspan="2" class="border border-gray-300 px-2 py-2 text-center font-bold text-gray-700 bg-blue-100">Kelengkapan Berkas DL</th>
                                <th colspan="3" class="border border-gray-300 px-2 py-2 text-center font-bold text-gray-700 bg-green-100">Kelengkapan Berkas Narasumber</th>
                                <th class="border border-gray-300 px-2 py-2 text-center font-bold text-gray-700" style="width: 4%">Aksi</th>
                            </tr>
                            <tr class="bg-orange-50">
                                <th colspan="5" class="border border-gray-300 px-2 py-1 text-center text-xs font-semibold"></th>
                                <th class="border border-gray-300 px-1 py-1 text-center text-xs font-semibold bg-orange-50">Surat</th>
                                <th class="border border-gray-300 px-1 py-1 text-center text-xs font-semibold bg-orange-50">KAK</th>
                                <th class="border border-gray-300 px-1 py-1 text-center text-xs font-semibold bg-orange-50">Notulen</th>
                                <th class="border border-gray-300 px-1 py-1 text-center text-xs font-semibold bg-orange-50">Absen</th>
                                <th class="border border-gray-300 px-1 py-1 text-center text-xs font-semibold bg-orange-50">Kuitansi</th>
                                <th class="border border-gray-300 px-1 py-1 text-center text-xs font-semibold bg-blue-50">Surat Tugas</th>
                                <th class="border border-gray-300 px-1 py-1 text-center text-xs font-semibold bg-blue-50">Laporan</th>
                                <th class="border border-gray-300 px-1 py-1 text-center text-xs font-semibold bg-green-50">Materi</th>
                                <th class="border border-gray-300 px-1 py-1 text-center text-xs font-semibold bg-green-50">KTP</th>
                                <th class="border border-gray-300 px-1 py-1 text-center text-xs font-semibold bg-green-50">NPWP</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="rabTableBody">
                            @forelse($rab->items as $index => $item)
                                <tr class="rab-item-row" data-row="{{ $index }}">
                                    <td class="border border-gray-300 px-2 py-2 text-center row-number">{{ $index + 1 }}</td>
                                    <td class="border border-gray-300 px-2 py-2">
                                        <input type="text" name="items[{{ $index }}][uraian]" class="w-full px-1 py-1 border border-gray-300 rounded text-xs focus:ring-2 focus:ring-[#3b82f6]" placeholder="Uraian" value="{{ $item->uraian }}" required>
                                    </td>
                                    <td class="border border-gray-300 px-2 py-2">
                                        <input type="number" name="items[{{ $index }}][volume]" class="w-full px-1 py-1 border border-gray-300 rounded text-xs text-center volume-input" placeholder="0" step="0.01" value="{{ $item->volume }}" required>
                                    </td>
                                    <td class="border border-gray-300 px-2 py-2">
                                        <input type="number" name="items[{{ $index }}][harga_satuan]" class="w-full px-1 py-1 border border-gray-300 rounded text-xs text-right harga-input" placeholder="0" step="0.01" value="{{ $item->harga_satuan }}" required>
                                    </td>
                                    <td class="border border-gray-300 px-2 py-2">
                                        <input type="number" name="items[{{ $index }}][jumlah]" class="w-full px-1 py-1 border border-gray-300 rounded text-xs text-right bg-gray-50 jumlah-input" placeholder="0" value="{{ $item->jumlah }}" readonly>
                                    </td>
                                    <td class="border border-gray-300 px-2 py-2 text-center">
                                        <input type="checkbox" name="items[{{ $index }}][surat_undangan]" class="surat-checkbox" value="1" {{ old('items.'.$index.'.surat_undangan', $item->surat_undangan) ? 'checked' : '' }}>
                                    </td>
                                    <td class="border border-gray-300 px-2 py-2 text-center">
                                        <input type="checkbox" name="items[{{ $index }}][kak]" class="kak-checkbox" value="1" {{ old('items.'.$index.'.kak', $item->kak) ? 'checked' : '' }}>
                                    </td>
                                    <td class="border border-gray-300 px-2 py-2 text-center">
                                        <input type="checkbox" name="items[{{ $index }}][notulen]" class="notulen-checkbox" value="1" {{ old('items.'.$index.'.notulen', $item->notulen) ? 'checked' : '' }}>
                                    </td>
                                    <td class="border border-gray-300 px-2 py-2 text-center">
                                        <input type="checkbox" name="items[{{ $index }}][absen]" class="absen-checkbox" value="1" {{ old('items.'.$index.'.absen', $item->absen) ? 'checked' : '' }}>
                                    </td>
                                    <td class="border border-gray-300 px-2 py-2 text-center">
                                        <input type="checkbox" name="items[{{ $index }}][kuitansi]" class="kuitansi-checkbox" value="1" {{ old('items.'.$index.'.kuitansi', $item->kuitansi) ? 'checked' : '' }}>
                                    </td>
                                    <td class="border border-gray-300 px-2 py-2 text-center bg-blue-50">
                                        <input type="checkbox" name="items[{{ $index }}][surat_tugas]" class="surat-tugas-checkbox" value="1" {{ old('items.'.$index.'.surat_tugas', $item->surat_tugas) ? 'checked' : '' }}>
                                    </td>
                                    <td class="border border-gray-300 px-2 py-2 text-center bg-blue-50">
                                        <input type="checkbox" name="items[{{ $index }}][laporan]" class="laporan-checkbox" value="1" {{ old('items.'.$index.'.laporan', $item->laporan) ? 'checked' : '' }}>
                                    </td>
                                    <td class="border border-gray-300 px-2 py-2 text-center bg-green-50">
                                        <input type="checkbox" name="items[{{ $index }}][materi]" class="materi-checkbox" value="1" {{ old('items.'.$index.'.materi', $item->materi) ? 'checked' : '' }}>
                                    </td>
                                    <td class="border border-gray-300 px-2 py-2 text-center bg-green-50">
                                        <input type="checkbox" name="items[{{ $index }}][ktp]" class="ktp-checkbox" value="1" {{ old('items.'.$index.'.ktp', $item->ktp) ? 'checked' : '' }}>
                                    </td>
                                    <td class="border border-gray-300 px-2 py-2 text-center bg-green-50">
                                        <input type="checkbox" name="items[{{ $index }}][npwp]" class="npwp-checkbox" value="1" {{ old('items.'.$index.'.npwp', $item->npwp) ? 'checked' : '' }}>
                                    </td>
                                    <td class="border border-gray-300 px-2 py-2 text-center">
                                        <button type="button" class="delete-row-btn bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded text-xs">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
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
                                    <td class="border border-gray-300 px-2 py-2 text-center">
                                        <input type="checkbox" name="items[0][surat_undangan]" class="surat-checkbox" value="1">
                                    </td>
                                    <td class="border border-gray-300 px-2 py-2 text-center">
                                        <input type="checkbox" name="items[0][kak]" class="kak-checkbox" value="1">
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
                            @endforelse
                        </tbody>
                        <tfoot>
                            <tr class="bg-gray-100 font-bold">
                                <td colspan="4" class="border border-gray-300 px-2 py-2 text-right">TOTAL:</td>
                                <td class="border border-gray-300 px-2 py-2">
                                    <input type="number" class="w-full px-1 py-1 border border-gray-300 rounded text-xs text-right total-jumlah" readonly>
                                </td>
                                <td colspan="10" class="border border-gray-300 px-2 py-2"></td>
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
                            placeholder="Nama dan jabatan pemoton/ketua tim kerja">{{ old('nama_pemoton', $rab->nama_pemoton) }}</textarea>
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
                            placeholder="Nama dan jabatan direktur/pengetahui">{{ old('nama_direktur', $rab->nama_direktur) }}</textarea>
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
                            placeholder="Nama dan jabatan pejabat pembuat komitmen">{{ old('nama_pejabat', $rab->nama_pejabat) }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Submission Buttons -->
            <div class="flex gap-3">
                <input type="hidden" name="submit_action" id="submit_action" value="draft">
                <button type="submit" onclick="document.getElementById('submit_action').value='draft'" class="flex-1 bg-gray-500 hover:bg-gray-600 text-white font-bold py-3 rounded-lg transition transform hover:scale-105">
                    <i class="fas fa-save"></i> Simpan Draft
                </button>
                <button type="submit" onclick="document.getElementById('submit_action').value='submit'" class="flex-1 bg-gradient-to-r from-[#3b82f6] to-[#1e3a8a] hover:from-[#1e3a8a] hover:to-[#0f172a] text-white font-bold py-3 rounded-lg transition transform hover:scale-105">
                    <i class="fas fa-paper-plane"></i> Ajukan RAB
                </button>
                <a href="{{ route('rab.show', $rab) }}" class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-3 rounded-lg text-center transition">
                    <i class="fas fa-times"></i> Batal
                </a>
            </div>
        </form>
    </div>

    <script>
        const tableBody = document.getElementById('rabTableBody');
        const addRowBtn = document.getElementById('addRowBtn');
        const rabForm = document.getElementById('rabForm');
        let rowCount = {{ $rab->items->count() }};

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
                <td class="border border-gray-300 px-2 py-2 text-center">
                    <input type="checkbox" name="items[${rowCount}][surat_undangan]" class="surat-checkbox" value="1">
                </td>
                <td class="border border-gray-300 px-2 py-2 text-center">
                    <input type="checkbox" name="items[${rowCount}][kak]" class="kak-checkbox" value="1">
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
                <td class="border border-gray-300 px-2 py-2 text-center bg-blue-50">
                    <input type="checkbox" name="items[${rowCount}][surat_tugas]" class="surat-tugas-checkbox" value="1">
                </td>
                <td class="border border-gray-300 px-2 py-2 text-center bg-blue-50">
                    <input type="checkbox" name="items[${rowCount}][laporan]" class="laporan-checkbox" value="1">
                </td>
                <td class="border border-gray-300 px-2 py-2 text-center bg-green-50">
                    <input type="checkbox" name="items[${rowCount}][materi]" class="materi-checkbox" value="1">
                </td>
                <td class="border border-gray-300 px-2 py-2 text-center bg-green-50">
                    <input type="checkbox" name="items[${rowCount}][ktp]" class="ktp-checkbox" value="1">
                </td>
                <td class="border border-gray-300 px-2 py-2 text-center bg-green-50">
                    <input type="checkbox" name="items[${rowCount}][npwp]" class="npwp-checkbox" value="1">
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

        function attachRowListeners(row) {
            const volumeInput = row.querySelector('.volume-input');
            const hargaInput = row.querySelector('.harga-input');
            const jumlahInput = row.querySelector('.jumlah-input');
            const deleteBtn = row.querySelector('.delete-row-btn');

            [volumeInput, hargaInput].forEach(input => {
                input.addEventListener('change', calculateRow);
                input.addEventListener('input', calculateRow);
            });

            function calculateRow() {
                const volume = parseFloat(volumeInput.value) || 0;
                const harga = parseFloat(hargaInput.value) || 0;
                const jumlah = volume * harga;

                jumlahInput.value = jumlah.toFixed(2);
                updateTotal();
            }

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

        function updateRowNumbers() {
            tableBody.querySelectorAll('tr').forEach((row, index) => {
                row.querySelector('.row-number').textContent = index + 1;
            });
        }

        function updateTotal() {
            let totalJumlah = 0;

            tableBody.querySelectorAll('tr').forEach(row => {
                const jumlah = parseFloat(row.querySelector('.jumlah-input').value) || 0;

                totalJumlah += jumlah;
            });

            document.querySelector('.total-jumlah').value = totalJumlah.toFixed(2);
        }

        addRowBtn.addEventListener('click', addNewRow);
        document.querySelectorAll('.rab-item-row').forEach(row => {
            attachRowListeners(row);
        });

        updateTotal();
    </script>
@endsection
