@extends('layouts.app')

@section('page-title')
    Dashboard Karyawan
@endsection

@section('title')
    Dashboard Karyawan - Management Dokumen
@endsection

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-8">
        <div class="bg-white rounded-lg p-6 shadow hover:shadow-lg transition border-t-4 border-[#3b82f6]">
            <i class="fas fa-folder text-2xl text-[#3b82f6] mb-3 block"></i>
            <div class="text-3xl font-bold text-[#1e3a8a] mb-2">{{ $myFolders }}</div>
            <p class="text-gray-600 text-sm">Folder Saya</p>
        </div>

        <div class="bg-white rounded-lg p-6 shadow hover:shadow-lg transition border-t-4 border-green-500">
            <i class="fas fa-check-circle text-2xl text-green-500 mb-3 block"></i>
            <div class="text-3xl font-bold text-[#1e3a8a] mb-2">{{ $approvedFolders }}</div>
            <p class="text-gray-600 text-sm">Disetujui</p>
        </div>

        <div class="bg-white rounded-lg p-6 shadow hover:shadow-lg transition border-t-4 border-[#f59e0b]">
            <i class="fas fa-hourglass-half text-2xl text-[#f59e0b] mb-3 block"></i>
            <div class="text-3xl font-bold text-[#1e3a8a] mb-2">{{ $pendingFolders }}</div>
            <p class="text-gray-600 text-sm">Menunggu Approval</p>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow mb-8">
        <div class="bg-[#1e3a8a] text-white p-4 rounded-t-lg">
            <h5 class="text-lg font-bold m-0"><i class="fas fa-tasks"></i> Aksi Cepat</h5>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <a href="{{ route('document.create') }}" class="bg-[#3b82f6] hover:bg-[#1e3a8a] text-white py-3 px-4 rounded font-semibold transition text-center">
                    <i class="fas fa-plus"></i> Upload Dokumen Baru
                </a>
                <a href="{{ route('document.index') }}" class="bg-cyan-500 hover:bg-cyan-600 text-white py-3 px-4 rounded font-semibold transition text-center">
                    <i class="fas fa-file-upload"></i> Lihat Semua Dokumen
                </a>
            </div>
        </div>
    </div>

    @if($recentFolders->count() > 0)
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="bg-[#1e3a8a] text-white p-4">
                <h5 class="text-lg font-bold m-0"><i class="fas fa-clock"></i> Dokumen Terbaru</h5>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-[#1e3a8a] text-white">
                        <tr>
                            <th class="px-6 py-3 text-left font-semibold">Nama Dokumen</th>
                            <th class="px-6 py-3 text-left font-semibold">Jenis Dokumen</th>
                            <th class="px-6 py-3 text-left font-semibold">Status</th>
                            <th class="px-6 py-3 text-left font-semibold">Tanggal Dibuat</th>
                            <th class="px-6 py-3 text-left font-semibold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($recentFolders as $folder)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4"><i class="fas fa-file"></i> <strong>{{ $folder->nama_folder }}</strong></td>
                                <td class="px-6 py-4">{{ $folder->jenis_dokumen }}</td>
                                <td class="px-6 py-4">
                                    @if($folder->status === 'approved')
                                        <span class="inline-block px-3 py-1 text-xs bg-green-500 text-white rounded"><i class="fas fa-check-circle"></i> Disetujui</span>
                                    @elseif($folder->status === 'pending')
                                        <span class="inline-block px-3 py-1 text-xs bg-[#f59e0b] text-white rounded"><i class="fas fa-hourglass-half"></i> Menunggu</span>
                                    @else
                                        <span class="inline-block px-3 py-1 text-xs bg-red-500 text-white rounded"><i class="fas fa-times-circle"></i> Ditolak</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">{{ $folder->created_at->format('d M Y') }}</td>
                                <td class="px-6 py-4">
                                    <a href="{{ route('document.show', $folder) }}" class="bg-cyan-500 hover:bg-cyan-600 text-white px-3 py-1 rounded text-sm"><i class="fas fa-eye"></i> Lihat</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
@endsection
