@extends('layouts.app')

@section('page-title')
    {{ $document->nama_dokumen }}
@endsection

@section('title')
    Detail Dokumen - {{ $document->nama_dokumen }}
@endsection

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2">
            <!-- Document Info -->
            <div class="bg-white rounded-lg shadow overflow-hidden mb-6">
                <div class="bg-[#1e3a8a] text-white p-4 flex justify-between items-center">
                    <h5 class="text-lg font-bold m-0"><i class="fas fa-file"></i> Informasi Dokumen</h5>
                    <span class="text-sm bg-[#f59e0b] px-3 py-1 rounded"><i class="fas fa-user"></i> {{ auth()->user()->name }}</span>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <p class="text-sm text-gray-600">Nama Dokumen</p>
                        <p class="text-lg font-bold text-[#1e3a8a]"><i class="fas fa-file"></i> {{ $document->nama_dokumen }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Uploader</p>
                        <p class="text-lg font-bold">{{ $document->user->name }}</p>
                    </div>
                    @if($document->keterangan)
                        <div>
                            <p class="text-sm text-gray-600">Keterangan</p>
                            <p class="text-base">{{ $document->keterangan }}</p>
                        </div>
                    @endif
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-4 border-t border-gray-200">
                        <div>
                            <p class="text-sm text-gray-600">Pelaksana</p>
                            <span class="inline-block px-3 py-1 text-xs bg-[#3b82f6] text-white rounded font-semibold">
                                {{ $document->pelaksana ?? '-' }}
                            </span>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Kode RO</p>
                            <p class="text-base font-semibold">{{ $document->kode_ro ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Jumlah Anggaran</p>
                            <p class="text-base font-semibold">{{ $document->jumlah_anggaran !== null ? number_format($document->jumlah_anggaran, 0, ',', '.') : '-' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Status</p>
                            @if($document->status === 'approved')
                                <span class="inline-block px-3 py-1 text-xs bg-green-500 text-white rounded"><i class="fas fa-check-circle"></i> Disetujui</span>
                            @elseif($document->status === 'pending')
                                <span class="inline-block px-3 py-1 text-xs bg-[#f59e0b] text-white rounded"><i class="fas fa-hourglass-half"></i> Menunggu</span>
                            @else
                                <span class="inline-block px-3 py-1 text-xs bg-red-500 text-white rounded"><i class="fas fa-times-circle"></i> Ditolak</span>
                            @endif
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Tanggal Dibuat</p>
                            <p class="text-base font-semibold">{{ $document->created_at->format('d M Y H:i') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Nama Verifikator</p>
                            <p class="text-base font-semibold">{{ $document->nama_verifikator ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Tanggal SP2D</p>
                            <p class="text-base font-semibold">{{ $document->tanggal_sp2d ? \Carbon\Carbon::parse($document->tanggal_sp2d)->format('d M Y') : '-' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Jumlah Anggaran SP2D</p>
                            <p class="text-base font-semibold">{{ $document->jumlah_anggaran_sp2d !== null ? number_format($document->jumlah_anggaran_sp2d, 0, ',', '.') : '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- File Details -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="bg-[#1e3a8a] text-white p-4">
                    <h5 class="text-lg font-bold m-0"><i class="fas fa-file-archive"></i> Detail File</h5>
                </div>
                <div class="p-6">
                    <div class="flex items-center justify-between bg-gray-50 border border-gray-200 rounded p-4">
                        <div class="flex items-center flex-1">
                            <i class="fas fa-file text-[#3b82f6] text-2xl mr-4"></i>
                            <div class="flex-1">
                                <p class="font-semibold text-gray-800">{{ $document->nama_dokumen }}</p>
                                <p class="text-xs text-gray-500">
                                    <i class="fas fa-file-upload"></i> {{ $document->file_type }} • 
                                    <i class="fas fa-database"></i> {{ number_format($document->ukuran_file / 1024, 2) }} KB •
                                    <i class="fas fa-clock"></i> {{ $document->created_at->format('d M Y') }}
                                </p>
                            </div>
                        </div>
                        <div class="ml-4">
                            <a href="{{ route('document.download', $document) }}" class="bg-[#3b82f6] hover:bg-[#1e3a8a] text-white px-4 py-2 rounded font-semibold transition inline-block">
                                <i class="fas fa-download"></i> Download
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Approval Status for Admin -->
            @if(auth()->user()->role === 'admin' && $document->status === 'pending')
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="bg-[#f59e0b] text-white p-4">
                        <h5 class="text-sm font-bold m-0"><i class="fas fa-check-square"></i> Approval</h5>
                    </div>
                    <div class="p-4 space-y-3">
                        <form action="{{ route('admin.approve', $document) }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full bg-green-500 hover:bg-green-600 text-white font-semibold py-2 rounded-lg transition">
                                <i class="fas fa-check-circle"></i> Setujui
                            </button>
                        </form>
                        <a href="{{ route('document.reject-form', $document) }}" class="w-full block text-center bg-red-500 hover:bg-red-600 text-white font-semibold py-2 rounded-lg transition">
                            <i class="fas fa-times-circle"></i> Tolak
                        </a>
                    </div>
                </div>
            @elseif($document->status !== 'pending')
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="bg-gray-500 text-white p-4">
                        <h5 class="text-sm font-bold m-0"><i class="fas fa-lock"></i> Status</h5>
                    </div>
                    <div class="p-4">
                        @if($document->status === 'approved')
                            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded">
                                <p class="text-sm font-semibold"><i class="fas fa-check-circle"></i> Dokumen Disetujui</p>
                                <p class="text-xs mt-1">Dokumen telah disetujui oleh admin</p>
                            </div>
                        @else
                            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded">
                                <p class="text-sm font-semibold"><i class="fas fa-times-circle"></i> Dokumen Ditolak</p>
                                <p class="text-xs mt-1">Silakan perbaiki dan upload ulang</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Action Buttons -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="bg-[#1e3a8a] text-white p-4">
                    <h5 class="text-sm font-bold m-0"><i class="fas fa-cog"></i> Aksi</h5>
                </div>
                <div class="p-4 space-y-2">
                    <a href="{{ route('document.index') }}" class="w-full block text-center bg-gray-500 hover:bg-gray-600 text-white font-semibold py-2 rounded-lg transition">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                    @if(auth()->user()->id === $document->user_id && $document->status === 'pending')
                        <form action="{{ route('document.destroy', $document) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full bg-red-500 hover:bg-red-600 text-white font-semibold py-2 rounded-lg transition" onclick="return confirm('Yakin ingin menghapus dokumen ini?')">
                                <i class="fas fa-trash"></i> Hapus Dokumen
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            <!-- Info Card -->
            <div class="bg-blue-50 border-l-4 border-[#3b82f6] rounded-lg p-4">
                <h5 class="text-sm font-bold text-[#1e3a8a] mb-3"><i class="fas fa-info-circle"></i> Informasi</h5>
                <ul class="text-xs text-gray-700 space-y-2">
                    <li>
                        <strong>Pending:</strong> Menunggu persetujuan dari admin
                    </li>
                    <li>
                        <strong>Approved:</strong> Dokumen telah disetujui dan dapat diakses
                    </li>
                    <li>
                        <strong>Rejected:</strong> Dokumen ditolak, silakan perbaiki sesuai alasan
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Reject Modal removed - now using separate rejection form page -->
@endsection
