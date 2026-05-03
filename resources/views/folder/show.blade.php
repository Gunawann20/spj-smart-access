@extends('layouts.app')

@section('page-title')
    {{ $folder->nama_folder }}
@endsection

@section('title')
    Detail Folder - {{ $folder->nama_folder }}
@endsection

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2">
            <!-- Folder Info -->
            <div class="bg-white rounded-lg shadow overflow-hidden mb-6">
                <div class="bg-[#1e3a8a] text-white p-4 flex justify-between items-center">
                    <h5 class="text-lg font-bold m-0"><i class="fas fa-folder-open"></i> Informasi Folder</h5>
                    <span class="text-sm bg-[#f59e0b] px-3 py-1 rounded"><i class="fas fa-user"></i> {{ auth()->user()->name }}</span>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <p class="text-sm text-gray-600">Nama Folder</p>
                        <p class="text-lg font-bold text-[#1e3a8a]"><i class="fas fa-folder"></i> {{ $folder->nama_folder }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Uploader</p>
                        <p class="text-lg font-bold">{{ $folder->user->name }}</p>
                    </div>
                    @if($folder->deskripsi)
                        <div>
                            <p class="text-sm text-gray-600">Deskripsi</p>
                            <p class="text-base">{{ $folder->deskripsi }}</p>
                        </div>
                    @endif
                    <div class="grid grid-cols-2 gap-4 pt-4 border-t border-gray-200">
                        <div>
                            <p class="text-sm text-gray-600">Jenis Dokumen</p>
                            <span class="inline-block px-3 py-1 text-xs bg-[#3b82f6] text-white rounded font-semibold">
                                {{ $folder->jenis_dokumen }}
                            </span>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Status</p>
                            @if($folder->status === 'approved')
                                <span class="inline-block px-3 py-1 text-xs bg-green-500 text-white rounded"><i class="fas fa-check-circle"></i> Disetujui</span>
                            @elseif($folder->status === 'pending')
                                <span class="inline-block px-3 py-1 text-xs bg-[#f59e0b] text-white rounded"><i class="fas fa-hourglass-half"></i> Menunggu</span>
                            @else
                                <span class="inline-block px-3 py-1 text-xs bg-red-500 text-white rounded"><i class="fas fa-times-circle"></i> Ditolak</span>
                            @endif
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Tanggal Dibuat</p>
                            <p class="text-base font-semibold">{{ $folder->created_at->format('d M Y H:i') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Terakhir Diubah</p>
                            <p class="text-base font-semibold">{{ $folder->updated_at->format('d M Y H:i') }}</p>
                        </div>
                    </div>
                    @if($folder->status === 'rejected' && $folder->alasan_reject)
                        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded mt-4">
                            <p class="text-sm font-semibold"><i class="fas fa-times-circle"></i> Alasan Penolakan</p>
                            <p class="text-sm mt-1">{{ $folder->alasan_reject }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Documents List -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="bg-[#1e3a8a] text-white p-4 flex justify-between items-center">
                    <h5 class="text-lg font-bold m-0"><i class="fas fa-file-archive"></i> Dokumen ({{ $folder->documents->count() }})</h5>
                </div>
                <div class="p-6">
                    @if($folder->documents->count() > 0)
                        <div class="space-y-3">
                            @foreach($folder->documents as $doc)
                                <div class="flex items-center justify-between bg-gray-50 border border-gray-200 rounded p-4 hover:bg-gray-100 transition">
                                    <div class="flex items-center flex-1">
                                        <i class="fas fa-file text-[#3b82f6] text-xl mr-3"></i>
                                        <div class="flex-1">
                                            <p class="font-semibold text-gray-800">{{ $doc->nama_dokumen }}</p>
                                            <p class="text-xs text-gray-500">
                                                <i class="fas fa-file-pdf"></i> {{ $doc->file_type }} • 
                                                <i class="fas fa-database"></i> {{ number_format($doc->ukuran_file / 1024, 2) }} KB •
                                                <i class="fas fa-clock"></i> {{ $doc->created_at->format('d M Y') }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <a href="{{ route('document.download', $doc) }}" class="inline-flex items-center gap-1 bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-xs font-semibold transition shadow-sm hover:shadow-md">
                                            <i class="fas fa-download"></i> Download
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="bg-yellow-100 border-l-4 border-[#f59e0b] text-yellow-700 p-4 rounded">
                            <i class="fas fa-info-circle"></i> Folder ini tidak memiliki dokumen.
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Approval Status for Admin -->
            @if(auth()->user()->role === 'admin' && $folder->status === 'pending')
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="bg-[#f59e0b] text-white p-4">
                        <h5 class="text-sm font-bold m-0"><i class="fas fa-check-square"></i> Approval</h5>
                    </div>
                    <div class="p-4 space-y-3">
                        <form action="{{ route('folder.approve', $folder) }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full bg-green-500 hover:bg-green-600 text-white font-semibold py-2 rounded-lg transition">
                                <i class="fas fa-check-circle"></i> Setujui
                            </button>
                        </form>
                        <button type="button" onclick="openRejectModal()" class="w-full bg-red-500 hover:bg-red-600 text-white font-semibold py-2 rounded-lg transition">
                            <i class="fas fa-times-circle"></i> Tolak
                        </button>
                    </div>
                </div>
            @elseif($folder->status !== 'pending')
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="bg-gray-500 text-white p-4">
                        <h5 class="text-sm font-bold m-0"><i class="fas fa-lock"></i> Status</h5>
                    </div>
                    <div class="p-4">
                        @if($folder->status === 'approved')
                            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded">
                                <p class="text-sm font-semibold"><i class="fas fa-check-circle"></i> Folder Disetujui</p>
                                <p class="text-xs mt-1">Folder telah disetujui oleh admin</p>
                            </div>
                        @else
                            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded">
                                <p class="text-sm font-semibold"><i class="fas fa-times-circle"></i> Folder Ditolak</p>
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
                    <a href="{{ route('folder.index') }}" class="w-full block text-center bg-gray-500 hover:bg-gray-600 text-white font-semibold py-2 rounded-lg transition">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                    @if(auth()->user()->id === $folder->user_id && $folder->status === 'pending')
                        <form action="{{ route('folder.destroy', $folder) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full bg-red-500 hover:bg-red-600 text-white font-semibold py-2 rounded-lg transition" onclick="return confirm('Yakin ingin menghapus folder ini?')">
                                <i class="fas fa-trash"></i> Hapus Folder
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
                        <strong>Approved:</strong> Folder telah disetujui dan dapat diakses
                    </li>
                    <li>
                        <strong>Rejected:</strong> Folder ditolak, silakan perbaiki sesuai alasan
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Reject Modal -->
    <div id="rejectModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
        <div class="bg-white rounded-lg shadow-lg max-w-md w-full mx-4">
            <div class="bg-red-500 text-white p-4">
                <h5 class="text-lg font-bold m-0"><i class="fas fa-times-circle"></i> Tolak Folder</h5>
            </div>
            <form action="{{ route('folder.reject', $folder) }}" method="POST" class="p-6">
                @csrf
                <div class="mb-4">
                    <label for="alasan_reject" class="block text-sm font-semibold text-gray-700 mb-2">
                        Alasan Penolakan
                    </label>
                    <textarea name="alasan_reject" id="alasan_reject" rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent outline-none transition" required></textarea>
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="flex-1 bg-red-500 hover:bg-red-600 text-white font-semibold py-2 rounded-lg transition">
                        <i class="fas fa-times-circle"></i> Tolak
                    </button>
                    <button type="button" onclick="closeRejectModal()" class="flex-1 bg-gray-500 hover:bg-gray-600 text-white font-semibold py-2 rounded-lg transition">
                        <i class="fas fa-times"></i> Batal
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openRejectModal() {
            document.getElementById('rejectModal').classList.remove('hidden');
        }

        function closeRejectModal() {
            document.getElementById('rejectModal').classList.add('hidden');
        }

        // Close modal when clicking outside
        document.getElementById('rejectModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeRejectModal();
            }
        });
    </script>
@endsection
