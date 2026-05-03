@extends('layouts.app')

@section('page-title')
    {{ $agenda->judul }}
@endsection

@section('title')
    Agenda: {{ $agenda->judul }}
@endsection

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2">
            <!-- Agenda Info -->
            <div class="bg-white rounded-lg shadow overflow-hidden mb-6">
                <div class="bg-[#1e3a8a] text-white p-4">
                    <h2 class="text-2xl font-bold m-0"><i class="fas fa-calendar-alt"></i> {{ $agenda->judul }}</h2>
                </div>
                <div class="p-6">
                    @if($agenda->deskripsi)
                        <div class="mb-4">
                            <p class="text-sm text-gray-600">Deskripsi</p>
                            <p class="text-base">{{ $agenda->deskripsi }}</p>
                        </div>
                    @endif
                    
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <p class="text-sm text-gray-600">Tanggal Mulai</p>
                            <p class="font-semibold">{{ $agenda->tanggal_mulai->format('d M Y') }}</p>
                        </div>
                        @if($agenda->tanggal_akhir)
                            <div>
                                <p class="text-sm text-gray-600">Tanggal Akhir</p>
                                <p class="font-semibold">{{ $agenda->tanggal_akhir->format('d M Y') }}</p>
                            </div>
                        @endif
                    </div>

                    <div>
                        <p class="text-sm text-gray-600">Status</p>
                        @if($agenda->status === 'active')
                            <span class="inline-block px-3 py-1 text-xs bg-green-500 text-white rounded"><i class="fas fa-check-circle"></i> Aktif</span>
                        @elseif($agenda->status === 'inactive')
                            <span class="inline-block px-3 py-1 text-xs bg-[#f59e0b] text-white rounded"><i class="fas fa-pause-circle"></i> Nonaktif</span>
                        @else
                            <span class="inline-block px-3 py-1 text-xs bg-red-500 text-white rounded"><i class="fas fa-times-circle"></i> Ditutup</span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Document Stats -->
            <div class="bg-white rounded-lg shadow overflow-hidden mb-6">
                <div class="bg-[#1e3a8a] text-white p-4">
                    <h3 class="text-lg font-bold m-0"><i class="fas fa-chart-bar"></i> Persentase Upload Dokumen</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        @foreach($stats as $jenis => $stat)
                            <div class="cursor-pointer hover:opacity-80 transition" onclick="openDocumentModal('{{ $jenis }}')">
                                <div class="flex justify-between items-center mb-2">
                                    <span class="font-semibold text-gray-700">{{ $jenis }}</span>
                                    <span class="text-sm font-bold text-[#1e3a8a] hover:text-blue-600">{{ $stat['uploaded'] }}/{{ $stat['total'] }} ({{ $stat['percentage'] }}%)</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-4 overflow-hidden border border-gray-300 hover:border-[#3b82f6]">
                                    <div class="bg-gradient-to-r from-[#3b82f6] to-[#1e3a8a] h-4 rounded-full transition-all duration-300" 
                                        style="width: {{ $stat['percentage'] }}%">
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-6 p-4 bg-blue-50 border-l-4 border-[#3b82f6] rounded">
                        <p class="text-sm text-gray-700">
                            <i class="fas fa-info-circle"></i>
                            <strong>Total Karyawan:</strong> {{ $totalKaryawan }} orang
                        </p>
                    </div>
                </div>
            </div>

            <!-- User Upload Status (for karyawan) -->
            @if(auth()->user()->role === 'karyawan')
                <div class="bg-white rounded-lg shadow overflow-hidden mb-6">
                    <div class="bg-[#1e3a8a] text-white p-4">
                        <h3 class="text-lg font-bold m-0"><i class="fas fa-list-check"></i> Status Upload Anda</h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-2">
                            @foreach(['RKAKL', 'RAPK', 'SPJ', 'LKJ', 'LAKIP', 'RAB'] as $jenis)
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded border-l-4 {{ in_array($jenis, $userDocuments) ? 'border-green-500 bg-green-50' : 'border-gray-300' }}">
                                    <span class="font-semibold text-gray-700">{{ $jenis }}</span>
                                    @if(in_array($jenis, $userDocuments))
                                        <span class="inline-block px-3 py-1 text-xs bg-green-500 text-white rounded"><i class="fas fa-check-circle"></i> Sudah Upload</span>
                                    @else
                                        <span class="inline-block px-3 py-1 text-xs bg-gray-300 text-gray-700 rounded"><i class="fas fa-times-circle"></i> Belum Upload</span>
                                    @endif
                                </div>
                            @endforeach
                        </div>

                        @if(auth()->user()->role === 'karyawan' && $agenda->status !== 'closed')
                            <div class="mt-6 flex gap-3">
                                <a href="{{ route('document.create', ['agenda_id' => $agenda->id]) }}" class="flex-1 block text-center bg-gradient-to-r from-[#3b82f6] to-[#1e3a8a] hover:from-[#1e3a8a] hover:to-[#0f172a] text-white font-bold py-3 rounded-lg transition">
                                    <i class="fas fa-upload"></i> Upload Dokumen
                                </a>
                                <a href="{{ route('rab.create-for-agenda', $agenda) }}" class="flex-1 block text-center bg-gradient-to-r from-[#10b981] to-[#059669] hover:from-[#059669] hover:to-[#047857] text-white font-bold py-3 rounded-lg transition">
                                    <i class="fas fa-file-invoice-dollar"></i> Buat RAB
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Documents List -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="bg-[#1e3a8a] text-white p-4 flex justify-between items-center">
                    <h3 class="text-lg font-bold m-0"><i class="fas fa-file"></i> Daftar Dokumen ({{ $documents->total() }})</h3>
                    @if(auth()->user()->role === 'karyawan' && $agenda->status !== 'closed')
                        <a href="{{ route('document.create', ['agenda_id' => $agenda->id]) }}" class="bg-[#f59e0b] hover:bg-[#d97706] px-3 py-1 rounded text-sm font-semibold transition">
                            <i class="fas fa-plus"></i> Upload
                        </a>
                    @endif
                </div>
                <div class="p-6">
                    @if($documents->count() > 0)
                        <div class="space-y-3">
                            @foreach($documents as $doc)
                                <div class="flex items-center justify-between bg-gray-50 border border-gray-200 rounded p-4 hover:bg-gray-100 transition">
                                    <div class="flex items-center flex-1">
                                        <i class="fas fa-file text-[#3b82f6] text-xl mr-3"></i>
                                        <div class="flex-1">
                                            <p class="font-semibold text-gray-800">{{ $doc->nama_dokumen }}</p>
                                            <p class="text-xs text-gray-500">
                                                <i class="fas fa-user"></i> {{ $doc->user->name }} • 
                                                <i class="fas fa-tag"></i> {{ $doc->pelaksana ?? '-' }} •
                                                <i class="fas fa-database"></i> {{ number_format($doc->ukuran_file / 1024, 2) }} KB
                                            </p>
                                        </div>
                                    </div>
                                    <div class="ml-4 flex items-center gap-2">
                                        @if($doc->status === 'approved')
                                            <span class="inline-block px-3 py-1 text-xs bg-green-500 text-white rounded"><i class="fas fa-check-circle"></i> Disetujui</span>
                                        @elseif($doc->status === 'pending')
                                            <span class="inline-block px-3 py-1 text-xs bg-[#f59e0b] text-white rounded"><i class="fas fa-hourglass-half"></i> Menunggu</span>
                                        @else
                                            <span class="inline-block px-3 py-1 text-xs bg-red-500 text-white rounded"><i class="fas fa-times-circle"></i> Ditolak</span>
                                        @endif
                                        <a href="{{ route('document.download', $doc) }}" class="inline-flex items-center gap-1 bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-xs font-semibold transition shadow-sm hover:shadow-md">
                                            <i class="fas fa-download"></i> Download
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-6">
                            {{ $documents->links() }}
                        </div>
                    @else
                        <div class="bg-yellow-100 border-l-4 border-[#f59e0b] text-yellow-700 p-4 rounded">
                            <i class="fas fa-info-circle"></i> Belum ada dokumen yang diupload untuk agenda ini.
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="bg-[#1e3a8a] text-white p-4">
                    <h3 class="text-sm font-bold m-0"><i class="fas fa-cog"></i> Aksi</h3>
                </div>
                <div class="p-4 space-y-2">
                    <a href="{{ route('agenda.index') }}" class="w-full block text-center bg-gray-500 hover:bg-gray-600 text-white font-semibold py-2 rounded-lg transition">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                    @if(auth()->user()->role === 'admin')
                        <a href="{{ route('agenda.edit', $agenda) }}" class="w-full block text-center bg-yellow-500 hover:bg-yellow-600 text-white font-semibold py-2 rounded-lg transition">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <form action="{{ route('agenda.destroy', $agenda) }}" method="POST" onsubmit="return confirm('Hapus agenda ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full bg-red-500 hover:bg-red-600 text-white font-semibold py-2 rounded-lg transition">
                                <i class="fas fa-trash"></i> Hapus
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            <!-- Info Card -->
            <div class="bg-blue-50 border-l-4 border-[#3b82f6] rounded-lg p-4 mt-6">
                <h4 class="text-sm font-bold text-[#1e3a8a] mb-3"><i class="fas fa-info-circle"></i> Informasi</h4>
                <ul class="text-xs text-gray-700 space-y-2">
                    <li>
                        <strong>Aktif:</strong> Karyawan bisa upload dokumen
                    </li>
                    <li>
                        <strong>Nonaktif:</strong> Agenda tidak ditampilkan
                    </li>
                    <li>
                        <strong>Ditutup:</strong> Tidak bisa upload lagi
                    </li>
                </ul>
            </div>

            <!-- Photo Documentation Section -->
            <div class="bg-white rounded-lg shadow overflow-hidden mt-6">
                <div class="bg-purple-600 text-white p-3">
                    <h3 class="text-xs font-bold m-0"><i class="fas fa-images"></i> Dokumentasi Foto</h3>
                </div>
                <div class="p-3">
                    <!-- Upload Form for Admin -->
                    @if(auth()->user()->role === 'admin')
                        <form action="{{ route('photo.store', $agenda) }}" method="POST" enctype="multipart/form-data" class="mb-3 pb-3 border-b space-y-2">
                            @csrf
                            <div>
                                <label for="foto" class="block text-xs font-semibold text-gray-700 mb-1">Foto</label>
                                <input type="file" name="foto" id="foto" accept="image/*" required class="w-full px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-2 focus:ring-purple-500">
                            </div>
                            <div>
                                <label for="keterangan" class="block text-xs font-semibold text-gray-700 mb-1">Ket</label>
                                <input type="text" name="keterangan" id="keterangan" placeholder="Singkat..." class="w-full px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-2 focus:ring-purple-500">
                            </div>
                            <button type="submit" class="w-full bg-purple-600 hover:bg-purple-700 text-white font-semibold py-1 rounded text-xs transition">
                                <i class="fas fa-upload"></i> Upload
                            </button>
                        </form>
                    @endif

                    <!-- Photo Gallery -->
                    @if($photos->count() > 0)
                        <div>
                            <p class="text-xs font-semibold text-gray-700 mb-2">{{ $photos->count() }} Foto</p>
                            <div class="grid grid-cols-3 gap-2">
                                @foreach($photos as $photo)
                                    <div class="relative group cursor-pointer rounded overflow-hidden border border-gray-200 hover:border-purple-500 transition bg-gradient-to-br from-gray-100 to-gray-200 h-20" onclick="openPhotoModal('{{ route('photo.show', $photo) }}', '{{ addslashes($photo->keterangan ?? '') }}')">
                                        <img src="{{ route('photo.show', $photo) }}" alt="Foto" class="w-full h-full object-cover group-hover:scale-110 transition duration-300" loading="eager" style="display:block;">
                                        <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 transition flex items-center justify-center">
                                            <i class="fas fa-search-plus text-white text-sm opacity-0 group-hover:opacity-100 transition"></i>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        @if(auth()->user()->role === 'admin')
                            <div class="mt-2 pt-2 border-t space-y-1">
                                @foreach($photos as $photo)
                                    <form action="{{ route('photo.destroy', $photo) }}" method="POST" onsubmit="return confirm('Hapus?');" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-xs text-red-500 hover:text-red-700 font-semibold">
                                            <i class="fas fa-trash text-xs"></i>
                                        </button>
                                    </form>
                                @endforeach
                            </div>
                        @endif
                    @else
                        <p class="text-xs text-gray-500 text-center py-3">
                            <i class="fas fa-image text-gray-400"></i><br>Belum ada
                        </p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modal untuk lihat dokumen per jenis -->
    <div id="documentModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4" onclick="closeDocumentModal(event)">
        <div class="bg-white rounded-lg shadow-xl max-w-3xl w-full max-h-[90vh] overflow-y-auto" onclick="event.stopPropagation()">
            <!-- Modal Header -->
            <div class="bg-[#1e3a8a] text-white p-4 sticky top-0 z-10 flex justify-between items-center">
                <h3 class="text-lg font-bold m-0">
                    <i class="fas fa-file"></i> Dokumen: <span id="modalJenis"></span>
                </h3>
                <button onclick="closeDocumentModal()" class="text-white hover:text-gray-200 text-2xl">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="p-6" id="modalContent">
                <div class="text-center py-8">
                    <i class="fas fa-spinner fa-spin text-4xl text-[#3b82f6]"></i>
                    <p class="text-gray-600 mt-3">Memuat data...</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        async function openDocumentModal(jenis) {
            document.getElementById('documentModal').classList.remove('hidden');
            document.getElementById('modalJenis').textContent = jenis;
            
            try {
                const response = await fetch(`{{ route('api.agenda.documents', $agenda->id) }}?jenis=${jenis}`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                
                const data = await response.json();
                displayDocuments(data, jenis);
            } catch (error) {
                document.getElementById('modalContent').innerHTML = '<div class="text-red-500 text-center py-8"><i class="fas fa-exclamation-circle"></i> Gagal memuat data</div>';
            }
        }

        function displayDocuments(data, jenis) {
            const isAdmin = {{ auth()->user()->role === 'admin' ? 'true' : 'false' }};
            let html = '';

            if (data.documents.length === 0) {
                html = '<div class="text-center py-8 bg-yellow-100 rounded p-4"><i class="fas fa-info-circle"></i> Belum ada dokumen jenis ' + jenis + ' yang diupload.</div>';
            } else {
                html = '<div class="space-y-3">';
                data.documents.forEach(doc => {
                    const statusColor = doc.status === 'approved' ? 'green' : (doc.status === 'pending' ? 'yellow' : 'red');
                    const statusIcon = doc.status === 'approved' ? 'check-circle' : (doc.status === 'pending' ? 'hourglass-half' : 'times-circle');
                    const statusText = doc.status === 'approved' ? 'Disetujui' : (doc.status === 'pending' ? 'Menunggu' : 'Ditolak');
                    
                    html += `
                        <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition">
                            <div class="flex items-start justify-between mb-2">
                                <div class="flex-1">
                                    <p class="font-semibold text-gray-800"><i class="fas fa-file"></i> ${doc.nama_dokumen}</p>
                                    <p class="text-xs text-gray-600 mt-1">
                                        <i class="fas fa-user"></i> <strong>Upload oleh:</strong> ${doc.user_name}<br>
                                        <i class="fas fa-calendar"></i> <strong>Tanggal:</strong> ${doc.created_at}<br>
                                        <i class="fas fa-database"></i> <strong>Ukuran:</strong> ${(doc.ukuran_file / 1024).toFixed(2)} KB
                                    </p>
                                </div>
                                <span class="inline-block px-3 py-1 text-xs bg-${statusColor}-500 text-white rounded whitespace-nowrap ml-2">
                                    <i class="fas fa-${statusIcon}"></i> ${statusText}
                                </span>
                            </div>
                            
                            <div class="flex gap-2 mt-3 pt-3 border-t border-gray-200">
                                <a href="/documents/${doc.id}/download" class="flex-1 text-center bg-cyan-500 hover:bg-cyan-600 text-white px-3 py-2 rounded text-xs font-semibold transition">
                                    <i class="fas fa-download"></i> Download
                                </a>
    `;
                    
                    if (isAdmin) {
                        html += `
                                <button onclick="approveDocument(${doc.id}, '${doc.jenis_dokumen}')" class="flex-1 bg-green-500 hover:bg-green-600 text-white px-3 py-2 rounded text-xs font-semibold transition">
                                    <i class="fas fa-check-circle"></i> Setujui
                                </button>
                                <button onclick="rejectDocument(${doc.id}, '${doc.jenis_dokumen}')" class="flex-1 bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded text-xs font-semibold transition">
                                    <i class="fas fa-times-circle"></i> Tolak
                                </button>
                        `;
                    }
                    
                    html += `
                            </div>
                        </div>
                    `;
                });
                html += '</div>';
            }
            
            document.getElementById('modalContent').innerHTML = html;
        }

        function closeDocumentModal(event) {
            if (event && event.target !== document.getElementById('documentModal')) return;
            document.getElementById('documentModal').classList.add('hidden');
        }

        async function approveDocument(documentId, jenis) {
            if (!confirm('Setujui dokumen ini?')) return;
            
            try {
                const response = await fetch(`/documents/${documentId}/approve`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                
                if (response.ok) {
                    alert('Dokumen disetujui!');
                    openDocumentModal(jenis);
                } else {
                    alert('Gagal menyetujui dokumen!');
                }
            } catch (error) {
                alert('Error: ' + error.message);
            }
        }

        async function rejectDocument(documentId, jenis) {
            // Redirect to rejection form instead of directly rejecting
            window.location.href = `/documents/${documentId}/reject`;
        }

        // Close modal when pressing Escape
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeDocumentModal();
            }
        });

        // Photo Modal Functions
        function openPhotoModal(photoUrl, keterangan) {
            const modal = document.getElementById('photoModal');
            document.getElementById('photoImage').src = photoUrl;
            document.getElementById('photoKeterangan').textContent = keterangan || 'Tanpa keterangan';
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closePhotoModal() {
            const modal = document.getElementById('photoModal');
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        // Close photo modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closePhotoModal();
            }
        });
    </script>

    <!-- Photo Lightbox Modal -->
    <div id="photoModal" class="hidden fixed inset-0 bg-black bg-opacity-80 z-50 flex items-center justify-center p-4" onclick="if(event.target === this) closePhotoModal()">
        <div class="bg-white rounded-lg shadow-2xl overflow-hidden max-w-4xl w-full max-h-[90vh] flex flex-col" onclick="event.stopPropagation()">
            <!-- Header -->
            <div class="bg-gray-800 text-white p-3 flex justify-between items-center">
                <h3 class="text-sm font-bold"><i class="fas fa-image"></i> Lihat Foto</h3>
                <button onclick="closePhotoModal()" class="text-white hover:text-gray-300 text-2xl">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <!-- Image -->
            <div class="flex-1 flex items-center justify-center bg-black overflow-auto">
                <img id="photoImage" src="" alt="Foto" class="max-w-full max-h-full object-contain">
            </div>
            <!-- Footer -->
            <div class="bg-gray-100 p-3 text-center">
                <p id="photoKeterangan" class="text-sm text-gray-700"></p>
            </div>
        </div>
    </div>

@endsection
