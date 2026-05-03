@extends('layouts.app')

@section('page-title')
    Folder Dokumen
@endsection

@section('title')
    Folder Dokumen - Management Dokumen
@endsection

@section('content')
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="bg-[#1e3a8a] text-white p-4 flex justify-between items-center">
            <h5 class="text-lg font-bold m-0"><i class="fas fa-folder"></i> Daftar Folder Dokumen</h5>
            <div class="flex gap-2 items-center">
                <span class="text-sm bg-[#f59e0b] px-3 py-1 rounded"><i class="fas fa-user"></i> {{ auth()->user()->name }}</span>
                <a href="{{ route('folder.create') }}" class="bg-[#3b82f6] hover:bg-[#1e3a8a] px-4 py-2 rounded text-sm font-semibold transition">
                    <i class="fas fa-plus"></i> Buat Folder Baru
                </a>
            </div>
        </div>
        <div class="p-6">
            @if($folders->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-[#1e3a8a] text-white">
                            <tr>
                                <th class="px-6 py-3 text-left font-semibold">Nama Folder</th>
                                @if(auth()->user()->role === 'admin')
                                    <th class="px-6 py-3 text-left font-semibold">Uploader</th>
                                @endif
                                <th class="px-6 py-3 text-left font-semibold">Jenis Dokumen</th>
                                <th class="px-6 py-3 text-left font-semibold">Status</th>
                                <th class="px-6 py-3 text-left font-semibold">Tanggal</th>
                                <th class="px-6 py-3 text-left font-semibold">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($folders as $folder)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4">
                                        <i class="fas fa-folder"></i>
                                        <strong>{{ $folder->nama_folder }}</strong>
                                        @if($folder->deskripsi)
                                            <br>
                                            <small class="text-gray-500">{{ $folder->deskripsi }}</small>
                                        @endif
                                    </td>
                                    @if(auth()->user()->role === 'admin')
                                        <td class="px-6 py-4">{{ $folder->user->name }}</td>
                                    @endif
                                    <td class="px-6 py-4">
                                        <span class="inline-block px-3 py-1 text-xs bg-[#3b82f6] text-white rounded font-semibold">
                                            {{ $folder->jenis_dokumen }}
                                        </span>
                                    </td>
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
                                        <a href="{{ route('folder.show', $folder) }}" class="bg-cyan-500 hover:bg-cyan-600 text-white px-2 py-1 rounded text-xs"><i class="fas fa-eye"></i> Lihat</a>
                                        @if(auth()->user()->id === $folder->user_id && $folder->status === 'pending')
                                            <form action="{{ route('folder.destroy', $folder) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded text-xs" onclick="return confirm('Yakin ingin menghapus?')">
                                                    <i class="fas fa-trash"></i> Hapus
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-6">
                    {{ $folders->links() }}
                </div>
            @else
                <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4 rounded">
                    <i class="fas fa-info-circle"></i>
                    Belum ada folder dokumen.
                    @if(auth()->user()->role === 'karyawan')
                        <a href="{{ route('folder.create') }}" class="font-semibold underline">Buat folder baru sekarang</a>
                    @endif
                </div>
            @endif
        </div>
    </div>
@endsection
