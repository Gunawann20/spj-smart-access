@extends('layouts.app')

@section('page-title')
    Daftar RAB
@endsection

@section('title')
    Rencana Anggaran Biaya (RAB)
@endsection

@section('content')
    <div class="max-w-7xl mx-auto">
        <!-- Header & Action -->
        <div class="mb-8 flex justify-between items-center">
            <div>
                <h1 class="text-4xl font-bold text-[#1e3a8a] mb-2"><i class="fas fa-file-invoice-dollar"></i> Rencana Anggaran Biaya</h1>
                <p class="text-gray-600">Kelola semua dokumen RAB Anda</p>
            </div>
            <a href="{{ route('rab.create') }}" class="bg-gradient-to-r from-[#3b82f6] to-[#1e3a8a] hover:from-[#1e3a8a] hover:to-[#0f172a] text-white px-6 py-3 rounded-lg font-semibold transition transform hover:scale-105">
                <i class="fas fa-plus"></i> Buat RAB Baru
            </a>
        </div>

        <!-- RAB List -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="bg-[#1e3a8a] text-white p-4">
                <h2 class="text-lg font-bold m-0"><i class="fas fa-list"></i> Daftar RAB ({{ $rabs->total() }})</h2>
            </div>

            @if($rabs->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-6 py-3 text-left text-sm font-bold text-gray-700">Judul RAB</th>
                                <th class="px-6 py-3 text-left text-sm font-bold text-gray-700">Nomor</th>
                                <th class="px-6 py-3 text-left text-sm font-bold text-gray-700">Pembuat</th>
                                <th class="px-6 py-3 text-left text-sm font-bold text-gray-700">Tahun</th>
                                <th class="px-6 py-3 text-left text-sm font-bold text-gray-700">Total Biaya</th>
                                <th class="px-6 py-3 text-left text-sm font-bold text-gray-700">Status</th>
                                <th class="px-6 py-3 text-left text-sm font-bold text-gray-700">Tanggal</th>
                                <th class="px-6 py-3 text-center text-sm font-bold text-gray-700">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($rabs as $rab)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4">
                                        <div class="font-semibold text-gray-900">{{ $rab->judul_rab }}</div>
                                        @if($rab->agenda)
                                            <div class="text-xs text-gray-500"><i class="fas fa-calendar"></i> {{ $rab->agenda->judul }}</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm">
                                        <span class="font-mono bg-gray-100 px-2 py-1 rounded">{{ $rab->nomor_rab }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-sm">
                                        <i class="fas fa-user text-[#3b82f6]"></i> {{ $rab->user->name }}
                                    </td>
                                    <td class="px-6 py-4 text-sm">
                                        {{ $rab->tahun_anggaran }}
                                    </td>
                                    <td class="px-6 py-4 text-sm font-bold text-[#1e3a8a]">
                                        Rp {{ number_format($rab->total_jumlah, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 text-sm">
                                        @if($rab->status === 'draft')
                                            <span class="inline-block px-3 py-1 text-xs bg-gray-200 text-gray-800 rounded font-semibold"><i class="fas fa-file"></i> Draft</span>
                                        @elseif($rab->status === 'submitted')
                                            <span class="inline-block px-3 py-1 text-sm bg-blue-200 text-blue-800 rounded font-bold"><i class="fas fa-hourglass-half"></i> Menunggu Approval</span>
                                        @elseif($rab->status === 'approved')
                                            <span class="inline-block px-3 py-1 text-xs bg-green-200 text-green-800 rounded font-semibold"><i class="fas fa-check"></i> Approved</span>
                                        @else
                                            <span class="inline-block px-3 py-1 text-xs bg-red-200 text-red-800 rounded font-semibold"><i class="fas fa-times"></i> Rejected</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600">
                                        {{ $rab->created_at->format('d M Y') }}
                                    </td>
                                    <td class="px-6 py-4 text-center space-x-2">
                                        <a href="{{ route('rab.show', $rab) }}" class="bg-cyan-500 hover:bg-cyan-600 text-white px-3 py-1 rounded text-xs font-semibold transition inline-block">
                                            <i class="fas fa-eye"></i> Lihat
                                        </a>
                                        @if(auth()->id() === $rab->user_id || auth()->user()->role === 'admin')
                                            <a href="{{ route('rab.edit', $rab) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded text-xs font-semibold transition inline-block">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                            <form action="{{ route('rab.destroy', $rab) }}" method="POST" class="inline" onsubmit="return confirm('Hapus RAB ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-xs font-semibold transition">
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

                <!-- Pagination -->
                <div class="p-6">
                    {{ $rabs->links() }}
                </div>
            @else
                <div class="p-12 text-center">
                    <i class="fas fa-inbox text-6xl text-gray-300 mb-4 block"></i>
                    <p class="text-gray-600 text-lg font-semibold">Belum ada RAB</p>
                    <p class="text-gray-500 mb-6">Mulai dengan membuat RAB baru</p>
                    <a href="{{ route('rab.create') }}" class="bg-[#3b82f6] hover:bg-[#1e3a8a] text-white px-6 py-3 rounded-lg font-semibold transition">
                        <i class="fas fa-plus"></i> Buat RAB Baru
                    </a>
                </div>
            @endif
        </div>
    </div>
@endsection
