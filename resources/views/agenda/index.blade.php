@extends('layouts.app')

@section('page-title')
    Daftar Agenda
@endsection

@section('title')
    Agenda - Management Dokumen
@endsection

@section('content')
    <div class="mb-6 flex justify-between items-center">
        <h2 class="text-2xl font-bold text-[#1e3a8a]">Daftar Agenda</h2>
        @if(auth()->user()->role === 'admin')
            <a href="{{ route('agenda.create') }}" class="bg-[#3b82f6] hover:bg-[#1e3a8a] text-white px-4 py-2 rounded font-semibold transition flex items-center gap-2">
                <i class="fas fa-plus"></i> Buat Agenda Baru
            </a>
        @endif
    </div>

    @if($agendas->count() > 0)
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-[#1e3a8a] text-white">
                        <tr>
                            <th class="px-6 py-3 text-left font-semibold">Judul Agenda</th>
                            <th class="px-6 py-3 text-left font-semibold">Tanggal</th>
                            <th class="px-6 py-3 text-left font-semibold">Status</th>
                            <th class="px-6 py-3 text-left font-semibold">Dokumen</th>
                            <th class="px-6 py-3 text-left font-semibold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($agendas as $agenda)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <i class="fas fa-calendar-alt text-[#3b82f6]"></i>
                                    <strong>{{ $agenda->judul }}</strong>
                                    @if($agenda->deskripsi)
                                        <br>
                                        <small class="text-gray-500">{{ Str::limit($agenda->deskripsi, 60) }}</small>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    {{ $agenda->tanggal_mulai->format('d M Y') }}
                                    @if($agenda->tanggal_akhir)
                                        <br>s.d. {{ $agenda->tanggal_akhir->format('d M Y') }}
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if($agenda->status === 'active')
                                        <span class="inline-block px-3 py-1 text-xs bg-green-500 text-white rounded"><i class="fas fa-check-circle"></i> Aktif</span>
                                    @elseif($agenda->status === 'inactive')
                                        <span class="inline-block px-3 py-1 text-xs bg-[#f59e0b] text-white rounded"><i class="fas fa-pause-circle"></i> Nonaktif</span>
                                    @else
                                        <span class="inline-block px-3 py-1 text-xs bg-red-500 text-white rounded"><i class="fas fa-times-circle"></i> Ditutup</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-block px-3 py-1 text-xs bg-blue-100 text-blue-700 rounded">
                                        {{ $agenda->documents()->count() }} file
                                    </span>
                                </td>
                                <td class="px-6 py-4 space-x-2">
                                    <a href="{{ route('agenda.show', $agenda) }}" class="bg-cyan-500 hover:bg-cyan-600 text-white px-3 py-1 rounded text-sm inline-block"><i class="fas fa-eye"></i> Lihat</a>
                                    @if(auth()->user()->role === 'admin')
                                        <a href="{{ route('agenda.edit', $agenda) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded text-sm inline-block"><i class="fas fa-edit"></i> Edit</a>
                                        <form action="{{ route('agenda.destroy', $agenda) }}" method="POST" class="inline" onsubmit="return confirm('Hapus agenda ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm"><i class="fas fa-trash"></i> Hapus</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-6">
            {{ $agendas->links() }}
        </div>
    @else
        <div class="bg-white rounded-lg shadow p-8 text-center">
            <i class="fas fa-calendar text-gray-400 text-6xl mb-4 block"></i>
            <h3 class="text-xl font-semibold text-gray-700 mb-2">Belum Ada Agenda</h3>
            <p class="text-gray-600 mb-6">Belum ada agenda yang dibuat.</p>
            @if(auth()->user()->role === 'admin')
                <a href="{{ route('agenda.create') }}" class="bg-[#3b82f6] hover:bg-[#1e3a8a] text-white px-6 py-3 rounded font-semibold transition">
                    <i class="fas fa-plus"></i> Buat Agenda Baru
                </a>
            @endif
        </div>
    @endif
@endsection
