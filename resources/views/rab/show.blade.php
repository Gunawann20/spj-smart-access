@extends('layouts.app')

@section('page-title')
    {{ $rab->judul_rab }}
@endsection

@section('title')
    Detail RAB: {{ $rab->judul_rab }}
@endsection

@section('content')
    <div class="max-w-7xl mx-auto">
        <!-- Header Info -->
        <div class="mb-6">
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="bg-[#1e3a8a] text-white p-6">
                    <h1 class="text-3xl font-bold mb-2">{{ $rab->judul_rab }}</h1>
                    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 text-sm mt-4">
                        <div>
                            <p class="opacity-75">Nomor RAB</p>
                            <p class="font-bold">{{ $rab->nomor_rab }}</p>
                        </div>
                        <div>
                            <p class="opacity-75">Tanggal</p>
                            <p class="font-bold">{{ $rab->tanggal_rab->format('d M Y') }}</p>
                        </div>
                        <div>
                            <p class="opacity-75">Tahun Anggaran</p>
                            <p class="font-bold">{{ $rab->tahun_anggaran }}</p>
                        </div>
                        <div>
                            <p class="opacity-75">Pembuat</p>
                            <p class="font-bold"><i class="fas fa-user"></i> {{ $rab->user->name }}</p>
                        </div>
                    </div>
                </div>
                
                <div class="p-6 border-b border-gray-200">
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                        <div>
                            <p class="text-sm text-gray-600"><i class="fas fa-file-invoice-dollar"></i> Total Biaya</p>
                            <p class="text-3xl font-bold text-[#1e3a8a]">Rp {{ number_format($rab->total_jumlah, 0, ',', '.') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600"><i class="fas fa-list"></i> Total Item</p>
                            <p class="text-3xl font-bold text-[#f59e0b]">{{ $rab->items->count() }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600"><i class="fas fa-info-circle"></i> Status</p>
                            <div class="mt-2">
                                @if($rab->status === 'draft')
                                    <span class="inline-block px-3 py-1 text-sm bg-gray-200 text-gray-800 rounded font-bold"><i class="fas fa-file"></i> Draft</span>
                                @elseif($rab->status === 'submitted')
                                    <span class="inline-block px-3 py-1 text-sm bg-blue-200 text-blue-800 rounded font-bold"><i class="fas fa-hourglass-half"></i> Menunggu Approval</span>
                                @elseif($rab->status === 'approved')
                                    <span class="inline-block px-3 py-1 text-sm bg-green-200 text-green-800 rounded font-bold"><i class="fas fa-check"></i> Approved</span>
                                @else
                                    <span class="inline-block px-3 py-1 text-sm bg-red-200 text-red-800 rounded font-bold"><i class="fas fa-times"></i> Rejected</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Informasi Rapat -->
                @if($rab->tempat_pelaksanaan || $rab->waktu_mulai || $rab->sumber_kegiatan)
                    <div class="p-6 bg-orange-50 border-l-4 border-orange-500">
                        <h3 class="text-sm font-bold text-gray-700 mb-3"><i class="fas fa-calendar-alt"></i> Informasi Rapat</h3>
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 text-sm">
                            @if($rab->tempat_pelaksanaan)
                                <div>
                                    <p class="text-gray-600">Tempat Pelaksanaan:</p>
                                    <p class="font-semibold text-gray-800">{{ $rab->tempat_pelaksanaan }}</p>
                                </div>
                            @endif
                            @if($rab->waktu_mulai || $rab->waktu_selesai)
                                <div>
                                    <p class="text-gray-600">Waktu Pelaksanaan:</p>
                                    <p class="font-semibold text-gray-800">
                                        @if($rab->waktu_mulai)
                                            {{ $rab->waktu_mulai }}
                                        @endif
                                        @if($rab->waktu_mulai && $rab->waktu_selesai)
                                            s/d
                                        @endif
                                        @if($rab->waktu_selesai)
                                            {{ $rab->waktu_selesai }}
                                        @endif
                                    </p>
                                </div>
                            @endif
                            @if($rab->sumber_anggaran)
                                <div>
                                    <p class="text-gray-600">Sumber Anggaran:</p>
                                    <p class="font-semibold text-gray-800">{{ $rab->sumber_anggaran }}</p>
                                </div>
                            @endif
                            @if($rab->akun_yang_digunakan)
                                <div>
                                    <p class="text-gray-600">Akun yang Digunakan:</p>
                                    <p class="font-semibold text-gray-800">{{ $rab->akun_yang_digunakan }}</p>
                                </div>
                            @endif
                            @if($rab->sumber_kegiatan)
                                <div class="lg:col-span-2">
                                    <p class="text-gray-600">Sumber Kegiatan / DIPA:</p>
                                    <p class="font-semibold text-gray-800">{{ $rab->sumber_kegiatan }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                @if($rab->keterangan_rab)
                    <div class="p-6 bg-blue-50 border-l-4 border-[#3b82f6]">
                        <p class="text-sm font-semibold text-gray-700 mb-2"><i class="fas fa-sticky-note"></i> Keterangan</p>
                        <p class="text-gray-700">{{ $rab->keterangan_rab }}</p>
                    </div>
                @endif

                @if($rab->agenda)
                    <div class="p-6 bg-green-50 border-l-4 border-green-500">
                        <p class="text-sm font-semibold text-gray-700 mb-1"><i class="fas fa-calendar-alt"></i> Terkait dengan Agenda</p>
                        <p class="text-gray-700 font-semibold">{{ $rab->agenda->judul }}</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Detail Items SPJ -->
        <div class="bg-white rounded-lg shadow overflow-hidden mb-6">
            <div class="bg-[#1e3a8a] text-white p-4">
                <h2 class="text-lg font-bold m-0"><i class="fas fa-table"></i> Detail Kebutuhan & Kelengkapan SPJ</h2>
            </div>

            <div class="overflow-x-auto p-0">
                <table class="w-full border-collapse text-xs">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="border border-gray-300 px-2 py-2 text-center font-bold text-gray-700" style="width: 3%">No</th>
                            <th class="border border-gray-300 px-2 py-2 text-left font-bold text-gray-700" style="width: 15%">Uraian</th>
                            <th class="border border-gray-300 px-2 py-2 text-center font-bold text-gray-700" style="width: 7%">Volume</th>
                            <th class="border border-gray-300 px-2 py-2 text-center font-bold text-gray-700" style="width: 8%">Biaya Satuan</th>
                            <th class="border border-gray-300 px-2 py-2 text-center font-bold text-gray-700" style="width: 9%">Jumlah</th>
                            <th colspan="5" class="border border-gray-300 px-2 py-2 text-center font-bold text-gray-700 bg-orange-100">Kelengkapan Berkas SPJ</th>
                            <th colspan="2" class="border border-gray-300 px-2 py-2 text-center font-bold text-gray-700 bg-blue-100">Kelengkapan Berkas DL</th>
                            <th colspan="3" class="border border-gray-300 px-2 py-2 text-center font-bold text-gray-700 bg-green-100">Kelengkapan Berkas Narasumber</th>
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
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($rab->items as $item)
                            <tr class="hover:bg-gray-50">
                                <td class="border border-gray-300 px-2 py-2 text-center font-semibold">{{ $loop->iteration }}</td>
                                <td class="border border-gray-300 px-2 py-2">{{ $item->uraian }}</td>
                                <td class="border border-gray-300 px-2 py-2 text-center">{{ number_format($item->volume, 2) }}</td>
                                <td class="border border-gray-300 px-2 py-2 text-right">Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}</td>
                                <td class="border border-gray-300 px-2 py-2 text-right font-bold text-[#1e3a8a]">Rp {{ number_format($item->jumlah, 0, ',', '.') }}</td>
                                <td class="border border-gray-300 px-2 py-2 text-center">
                                    @if($item->surat_undangan)
                                        <span class="text-green-600 font-bold">✓</span>
                                    @else
                                        <span class="text-red-600">-</span>
                                    @endif
                                </td>
                                <td class="border border-gray-300 px-2 py-2 text-center">
                                    @if($item->kak)
                                        <span class="text-green-600 font-bold">✓</span>
                                    @else
                                        <span class="text-red-600">-</span>
                                    @endif
                                </td>
                                <td class="border border-gray-300 px-2 py-2 text-center">
                                    @if($item->notulen)
                                        <span class="text-green-600 font-bold">✓</span>
                                    @else
                                        <span class="text-red-600">-</span>
                                    @endif
                                </td>
                                <td class="border border-gray-300 px-2 py-2 text-center">
                                    @if($item->absen)
                                        <span class="text-green-600 font-bold">✓</span>
                                    @else
                                        <span class="text-red-600">-</span>
                                    @endif
                                </td>
                                <td class="border border-gray-300 px-2 py-2 text-center">
                                    @if($item->kuitansi)
                                        <span class="text-green-600 font-bold">✓</span>
                                    @else
                                        <span class="text-red-600">-</span>
                                    @endif
                                </td>
                                <td class="border border-gray-300 px-2 py-2 text-center bg-blue-50">
                                    @if($item->surat_tugas)
                                        <span class="text-green-600 font-bold">✓</span>
                                    @else
                                        <span class="text-red-600">-</span>
                                    @endif
                                </td>
                                <td class="border border-gray-300 px-2 py-2 text-center bg-blue-50">
                                    @if($item->laporan)
                                        <span class="text-green-600 font-bold">✓</span>
                                    @else
                                        <span class="text-red-600">-</span>
                                    @endif
                                </td>
                                <td class="border border-gray-300 px-2 py-2 text-center bg-green-50">
                                    @if($item->materi)
                                        <span class="text-green-600 font-bold">✓</span>
                                    @else
                                        <span class="text-red-600">-</span>
                                    @endif
                                </td>
                                <td class="border border-gray-300 px-2 py-2 text-center bg-green-50">
                                    @if($item->ktp)
                                        <span class="text-green-600 font-bold">✓</span>
                                    @else
                                        <span class="text-red-600">-</span>
                                    @endif
                                </td>
                                <td class="border border-gray-300 px-2 py-2 text-center bg-green-50">
                                    @if($item->npwp)
                                        <span class="text-green-600 font-bold">✓</span>
                                    @else
                                        <span class="text-red-600">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="15" class="border border-gray-300 px-3 py-4 text-center text-gray-500">
                                    Tidak ada item dalam RAB ini
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr class="bg-[#1e3a8a] text-white font-bold text-xs">
                            <td colspan="4" class="border border-gray-300 px-2 py-3 text-right">TOTAL:</td>
                            <td class="border border-gray-300 px-2 py-3 text-right">Rp {{ number_format($rab->items->sum('jumlah'), 0, ',', '.') }}</td>
                            <td colspan="10" class="border border-gray-300 px-2 py-3"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <!-- Tanda Tangan & Persetujuan -->
        @if($rab->nama_pemoton || $rab->nama_direktur || $rab->nama_pejabat)
            <div class="bg-white rounded-lg shadow overflow-hidden mb-6">
                <div class="bg-[#1e3a8a] text-white p-4">
                    <h2 class="text-lg font-bold m-0"><i class="fas fa-pen-fancy"></i> Tanda Tangan & Persetujuan</h2>
                </div>
                <div class="p-6 grid grid-cols-1 lg:grid-cols-3 gap-6">
                    @if($rab->nama_pemoton)
                        <div class="border-t pt-4">
                            <p class="text-xs text-gray-600 mb-2">Pemoton / Ketua Tim Kerja</p>
                            <p class="text-xs text-gray-600 mb-12">Fasilitasi Dukungan Manajemen</p>
                            <p class="text-xs font-semibold text-gray-800">{{ $rab->nama_pemoton }}</p>
                        </div>
                    @endif
                    @if($rab->nama_direktur)
                        <div class="border-t pt-4">
                            <p class="text-xs text-gray-600 mb-2">Mengetahui</p>
                            <p class="text-xs text-gray-600 mb-12">Direktur Bina Akses Pelayanan KB</p>
                            <p class="text-xs font-semibold text-gray-800">{{ $rab->nama_direktur }}</p>
                        </div>
                    @endif
                    @if($rab->nama_pejabat)
                        <div class="border-t pt-4">
                            <p class="text-xs text-gray-600 mb-2">Menyetujui</p>
                            <p class="text-xs text-gray-600 mb-12">Pejabat Pembuat Komitmen</p>
                            <p class="text-xs font-semibold text-gray-800">{{ $rab->nama_pejabat }}</p>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        <!-- Actions -->
        <div class="flex gap-3">
            <a href="{{ route('rab.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg font-semibold transition">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
            @if(auth()->user()->role === 'admin' || (auth()->id() === $rab->user_id && in_array($rab->status, ['draft', 'rejected'])))
                <a href="{{ route('rab.edit', $rab) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white px-6 py-3 rounded-lg font-semibold transition">
                    <i class="fas fa-edit"></i> Edit
                </a>
                <form action="{{ route('rab.destroy', $rab) }}" method="POST" class="inline" onsubmit="return confirm('Hapus RAB ini? Tindakan ini tidak dapat dibatalkan.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-6 py-3 rounded-lg font-semibold transition">
                        <i class="fas fa-trash"></i> Hapus
                    </button>
                </form>
            @endif
        </div>
    </div>
@endsection
