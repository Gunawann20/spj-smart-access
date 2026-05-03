@extends('layouts.app')

@section('page-title')
    Dokumen Saya
@endsection

@section('title')
    Dokumen Saya - Management Dokumen
@endsection

@section('content')
    <div class="mb-6 flex gap-3">
        <a href="{{ route('document.create') }}" class="bg-[#3b82f6] hover:bg-[#1e3a8a] text-white px-4 py-2 rounded font-semibold transition flex items-center gap-2">
            <i class="fas fa-plus"></i> Upload Dokumen Baru
        </a>
        <a href="{{ route('document.export.excel') }}" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded font-semibold transition flex items-center gap-2">
            <i class="fas fa-download"></i> Download
        </a>
    </div>

    @if($documents->count() > 0)
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="bg-[#1e3a8a] text-white p-4">
                <h5 class="text-lg font-bold m-0"><i class="fas fa-file-upload"></i> Daftar Dokumen</h5>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-[#1e3a8a] text-white">
                        <tr>
                            <th class="px-6 py-3 text-left font-semibold">Nama Dokumen</th>
                            <th class="px-6 py-3 text-left font-semibold">Pelaksana</th>
                            <th class="px-6 py-3 text-left font-semibold">Kode RO</th>
                            <th class="px-6 py-3 text-left font-semibold">Jumlah Anggaran</th>
                            <th class="px-6 py-3 text-left font-semibold">Status</th>
                            <th class="px-6 py-3 text-left font-semibold">Tanggal Dibuat</th>
                            <th class="px-6 py-3 text-left font-semibold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($documents as $document)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <i class="fas fa-file"></i> 
                                    <strong>{{ $document->nama_dokumen }}</strong>
                                    @php $associatedRab = $document->associatedRab(); @endphp
                                    @if($associatedRab)
                                        <br>
                                        <a href="{{ route('rab.show', $associatedRab) }}" class="text-xs text-[#3b82f6] hover:underline">
                                            <i class="fas fa-external-link-alt"></i> Lihat Detail RAB
                                        </a>
                                    @endif
                                </td>
                                <td class="px-6 py-4">{{ $document->pelaksana ?? '-' }}</td>
                                <td class="px-6 py-4">{{ $document->kode_ro ?? '-' }}</td>
                                <td class="px-6 py-4">{{ $document->jumlah_anggaran !== null ? number_format($document->jumlah_anggaran, 0, ',', '.') : '-' }}</td>
                                <td class="px-6 py-4">
                                    @if($document->status === 'approved')
                                        <span class="inline-block px-3 py-1 text-xs bg-green-500 text-white rounded"><i class="fas fa-check-circle"></i> Disetujui</span>
                                    @elseif($document->status === 'pending')
                                        <span class="inline-block px-3 py-1 text-xs bg-[#f59e0b] text-white rounded"><i class="fas fa-hourglass-half"></i> Menunggu</span>
                                    @else
                                        <span class="inline-block px-3 py-1 text-xs bg-red-500 text-white rounded"><i class="fas fa-times-circle"></i> Ditolak</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">{{ $document->created_at->format('d M Y') }}</td>
                                <td class="px-6 py-4">
                                    <div class="flex gap-2 flex-wrap">
                                        @if (auth()->user()->role == 'admin')
                                            <button type="button"
                                                onclick="openVerificationModal('{{ $document->id }}', '{{ addslashes($document->nama_verifikator ?? '') }}')"
                                                class="inline-flex items-center gap-1 bg-purple-500 hover:bg-purple-600 text-white px-4 py-2 rounded-lg text-xs font-semibold transition shadow-sm hover:shadow-md">
                                                <i class="fas fa-user-check"></i> Verifikasi
                                            </button>
                                            <button type="button"
                                                onclick="openSp2dModal('{{ $document->id }}', '{{ $document->tanggal_sp2d ?? '' }}', '{{ $document->jumlah_anggaran_sp2d ?? '' }}')"
                                                class="inline-flex items-center gap-1 bg-indigo-500 hover:bg-indigo-600 text-white px-4 py-2 rounded-lg text-xs font-semibold transition shadow-sm hover:shadow-md">
                                                <i class="fas fa-calendar-check"></i> SP2D
                                            </button>
                                            <a href="{{ route('document.show', $document) }}" class="inline-flex items-center gap-1 bg-cyan-500 hover:bg-cyan-600 text-white px-4 py-2 rounded-lg text-xs font-semibold transition shadow-sm hover:shadow-md"><i class="fas fa-eye"></i> Lihat</a>
                                            <a href="{{ route('document.download', $document) }}" class="inline-flex items-center gap-1 bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-xs font-semibold transition shadow-sm hover:shadow-md"><i class="fas fa-download"></i> Download</a>
                                            @if($document->status === 'pending' && auth()->user()->id === $document->user_id)
                                                <form action="{{ route('document.destroy', $document) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="inline-flex items-center gap-1 bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg text-xs font-semibold transition shadow-sm hover:shadow-md"><i class="fas fa-trash"></i> Hapus</button>
                                                </form>
                                            @endif
                                        @else
                                            <a href="{{ route('document.show', $document) }}" class="inline-flex items-center gap-1 bg-cyan-500 hover:bg-cyan-600 text-white px-4 py-2 rounded-lg text-xs font-semibold transition shadow-sm hover:shadow-md"><i class="fas fa-eye"></i> Lihat</a>
                                            <a href="{{ route('document.download', $document) }}" class="inline-flex items-center gap-1 bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-xs font-semibold transition shadow-sm hover:shadow-md"><i class="fas fa-download"></i> Download</a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <div class="bg-white rounded-lg shadow p-8 text-center">
            <i class="fas fa-inbox text-gray-400 text-6xl mb-4 block"></i>
            <h3 class="text-xl font-semibold text-gray-700 mb-2">Belum Ada Dokumen</h3>
            <p class="text-gray-600 mb-6">Anda belum memiliki dokumen. Mulai dengan upload dokumen baru.</p>
            <a href="{{ route('document.create') }}" class="bg-[#3b82f6] hover:bg-[#1e3a8a] text-white px-6 py-3 rounded font-semibold transition">
                <i class="fas fa-plus"></i> Upload Dokumen
            </a>
        </div>
    @endif

    <!-- Modal Verifikasi -->
    <div id="verificationModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40 p-4" onclick="closeVerificationModal(event)">
        <div class="w-full max-w-md rounded-lg bg-white shadow-xl" onclick="event.stopPropagation()">
            <div class="flex items-center justify-between border-b px-6 py-4">
                <h3 class="text-lg font-bold text-gray-800"><i class="fas fa-user-check text-purple-600"></i> Verifikasi</h3>
                <button type="button" onclick="closeVerificationModal()" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="verificationForm" method="POST" class="p-6">
                @csrf
                <div>
                    <label for="nama_verifikator" class="mb-2 block text-sm font-semibold text-gray-700">Nama Verifikator</label>
                    <input type="text" name="nama_verifikator" id="nama_verifikator" required
                        class="w-full rounded-lg border-2 border-gray-300 px-4 py-3 outline-none transition focus:border-purple-500 focus:ring-2 focus:ring-purple-500">
                </div>
                <div class="mt-6 flex gap-3">
                    <button type="button" onclick="closeVerificationModal()" class="flex-1 rounded-lg bg-gray-200 py-2 font-semibold text-gray-700 hover:bg-gray-300">Batal</button>
                    <button type="submit" class="flex-1 rounded-lg bg-purple-600 py-2 font-semibold text-white hover:bg-purple-700">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal SP2D -->
    <div id="sp2dModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40 p-4" onclick="closeSp2dModal(event)">
        <div class="w-full max-w-md rounded-lg bg-white shadow-xl" onclick="event.stopPropagation()">
            <div class="flex items-center justify-between border-b px-6 py-4">
                <h3 class="text-lg font-bold text-gray-800"><i class="fas fa-calendar-check text-indigo-600"></i> SP2D</h3>
                <button type="button" onclick="closeSp2dModal()" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="sp2dForm" method="POST" class="p-6">
                @csrf
                <div>
                    <label for="tanggal_sp2d" class="mb-2 block text-sm font-semibold text-gray-700">Tanggal SP2D</label>
                    <input type="date" name="tanggal_sp2d" id="tanggal_sp2d" required
                        class="w-full rounded-lg border-2 border-gray-300 px-4 py-3 outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500">
                </div>
                <div class="mt-4">
                    <label for="jumlah_anggaran_sp2d" class="mb-2 block text-sm font-semibold text-gray-700">Jumlah Anggaran SP2D</label>
                    <input type="number" name="jumlah_anggaran_sp2d" id="jumlah_anggaran_sp2d" required min="0" step="0.01"
                        class="w-full rounded-lg border-2 border-gray-300 px-4 py-3 outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500">
                </div>
                <div class="mt-6 flex gap-3">
                    <button type="button" onclick="closeSp2dModal()" class="flex-1 rounded-lg bg-gray-200 py-2 font-semibold text-gray-700 hover:bg-gray-300">Batal</button>
                    <button type="submit" class="flex-1 rounded-lg bg-indigo-600 py-2 font-semibold text-white hover:bg-indigo-700">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const verificationRouteTemplate = '{{ route('document.verify', ':id') }}';
        const sp2dRouteTemplate = '{{ route('document.sp2d', ':id') }}';

        function openVerificationModal(documentId, namaVerifikator) {
            const modal = document.getElementById('verificationModal');
            const form = document.getElementById('verificationForm');
            const input = document.getElementById('nama_verifikator');

            form.action = verificationRouteTemplate.replace(':id', documentId);
            input.value = namaVerifikator || '';

            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeVerificationModal(event) {
            if (event && event.target.id !== 'verificationModal') {
                return;
            }

            const modal = document.getElementById('verificationModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        function openSp2dModal(documentId, tanggalSp2d, jumlahAnggaranSp2d) {
            const modal = document.getElementById('sp2dModal');
            const form = document.getElementById('sp2dForm');
            const tanggalInput = document.getElementById('tanggal_sp2d');
            const jumlahAnggaranInput = document.getElementById('jumlah_anggaran_sp2d');

            form.action = sp2dRouteTemplate.replace(':id', documentId);
            tanggalInput.value = tanggalSp2d || '';
            jumlahAnggaranInput.value = jumlahAnggaranSp2d || '';

            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeSp2dModal(event) {
            if (event && event.target.id !== 'sp2dModal') {
                return;
            }

            const modal = document.getElementById('sp2dModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    </script>
@endsection
