@extends('layouts.app')

@section('page-title')
    Riwayat Approval
@endsection

@section('title')
    Riwayat Approval Dokumen
@endsection

@section('content')
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="bg-[#1e3a8a] text-white p-4 flex justify-between items-center">
            <h5 class="text-lg font-bold m-0"><i class="fas fa-history"></i> Riwayat Approval</h5>
            <a href="{{ route('admin.pending') }}" class="bg-[#f59e0b] hover:bg-[#d97706] px-4 py-2 rounded text-sm font-semibold transition">
                <i class="fas fa-hourglass-half"></i> Menunggu
            </a>
        </div>
        <div class="p-6">
            @if($approvals->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-[#1e3a8a] text-white">
                            <tr>
                                <th class="px-6 py-3 text-left font-semibold">Nama Dokumen</th>
                                <th class="px-6 py-3 text-left font-semibold">Uploader</th>
                                <th class="px-6 py-3 text-left font-semibold">Status</th>
                                <th class="px-6 py-3 text-left font-semibold">Keterangan</th>
                                <th class="px-6 py-3 text-left font-semibold">Tanggal</th>
                                <th class="px-6 py-3 text-left font-semibold">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($approvals as $document)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4">
                                        <i class="fas fa-file"></i>
                                        <strong>{{ $document->nama_dokumen }}</strong>
                                    </td>
                                    <td class="px-6 py-4">
                                        <i class="fas fa-user"></i> {{ $document->user?->name ?? 'User Dihapus' }}
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($document->status === 'approved')
                                            <span class="inline-block px-3 py-1 text-xs bg-green-500 text-white rounded font-semibold">
                                                <i class="fas fa-check-circle"></i> Disetujui
                                            </span>
                                        @else
                                            <span class="inline-block px-3 py-1 text-xs bg-red-500 text-white rounded font-semibold">
                                                <i class="fas fa-times-circle"></i> Ditolak
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <small class="text-gray-600">{{ $document->keterangan ?? '-' }}</small>
                                    </td>
                                    <td class="px-6 py-4 text-sm">{{ $document->updated_at->format('d M Y H:i') }}</td>
                                    <td class="px-6 py-4">
                                        <a href="{{ route('document.show', $document) }}" class="bg-cyan-500 hover:bg-cyan-600 text-white px-2 py-1 rounded text-xs font-semibold">
                                            <i class="fas fa-eye"></i> Lihat
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-6">
                    {{ $approvals->links() }}
                </div>
            @else
                <div class="bg-blue-100 border-l-4 border-[#3b82f6] text-blue-700 p-4 rounded text-center py-8">
                    <i class="fas fa-info-circle text-3xl mb-2 block"></i>
                    <p class="font-semibold">Belum ada riwayat approval</p>
                    <p class="text-sm mt-1">Mulai setujui dokumen yang menunggu</p>
                </div>
            @endif
        </div>
    </div>
@endsection
