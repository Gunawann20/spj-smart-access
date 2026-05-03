@extends('layouts.app')

@section('page-title')
    Edit Dokumen
@endsection

@section('title')
    Edit Dokumen
@endsection

@section('content')
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-[#1e3a8a] mb-2"><i class="fas fa-edit"></i> Edit Dokumen</h1>
            <p class="text-gray-600">Perbarui informasi atau ganti file dokumen Anda</p>
        </div>

        @if($document->status === 'rejected' && $document->keterangan)
            <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-8 rounded shadow-sm">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-triangle text-red-500"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-red-700 font-bold">Alasan Penolakan:</p>
                        <p class="text-sm text-red-600 mt-1">{{ $document->keterangan }}</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Main Edit Container -->
        <form action="{{ route('document.update', $document) }}" method="POST" enctype="multipart/form-data" id="uploadForm" class="bg-white rounded-lg shadow-lg overflow-hidden">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-0">
                <!-- Left: Drag & Drop Area / Current File -->
                <div class="bg-gradient-to-br from-[#1e3a8a] to-[#0f172a] text-white p-12 flex flex-col justify-center items-center relative overflow-hidden"
                    id="dropZone"
                    ondragover="handleDragOver(event)"
                    ondragleave="handleDragLeave(event)"
                    ondrop="handleDrop(event)">
                    
                    <!-- Background decoration -->
                    <div class="absolute top-0 right-0 w-40 h-40 bg-[#3b82f6] opacity-10 rounded-full -mr-20 -mt-20"></div>
                    <div class="absolute bottom-0 left-0 w-32 h-32 bg-[#f59e0b] opacity-10 rounded-full -ml-16 -mb-16"></div>

                    <div class="relative z-10 w-full">
                        <!-- Current File Display -->
                        <div class="mb-8 p-6 bg-white bg-opacity-10 backdrop-blur-md rounded-xl border border-white border-opacity-20">
                            <h3 class="text-sm font-bold mb-4 border-b border-white border-opacity-20 pb-2 uppercase tracking-wider text-blue-200">File Saat Ini</h3>
                            <div class="flex items-center">
                                <div class="bg-white bg-opacity-20 p-4 rounded-lg mr-4">
                                    <i class="fas fa-file-alt text-4xl {{ $document->file_type === 'pdf' ? 'text-red-400' : 'text-blue-400' }}"></i>
                                </div>
                                <div class="overflow-hidden">
                                    <p class="font-semibold truncate text-lg">{{ basename($document->file_path) }}</p>
                                    <p class="text-xs text-blue-200 mt-1 uppercase">{{ $document->file_type }} • {{ number_format($document->ukuran_file / 1024 / 1024, 2) }} MB</p>
                                </div>
                            </div>
                        </div>

                        <!-- Drop Zone Content -->
                        <div class="text-center cursor-pointer" onclick="document.getElementById('document').click()">
                            <div class="mb-6">
                                <i class="fas fa-cloud-upload-alt text-6xl mb-4 block opacity-80 hover:opacity-100 transition"></i>
                            </div>
                            <h3 class="text-2xl font-bold mb-2">Ganti Dokumen</h3>
                            <p class="text-blue-200 mb-6">Drag & Drop file baru atau klik untuk memilih</p>
                            <button type="button" class="bg-[#f59e0b] hover:bg-[#d97706] text-white font-bold py-3 px-8 rounded-lg transition transform hover:scale-105">
                                <i class="fas fa-folder-open"></i> Pilih File Baru
                            </button>
                            <p class="text-blue-200 text-sm mt-6 border-t border-blue-400 pt-6">
                                Format: PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX, ZIP<br>
                                Biarkan kosong jika tidak ingin mengganti file
                            </p>
                        </div>
                    </div>

                    <input type="file" name="document" id="document" class="hidden" 
                        accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.zip"
                        onchange="updateFileList()">

                    <!-- File Preview (New File Selected) -->
                    <div id="filePreview" class="relative z-10 mt-8 w-full hidden">
                        <div class="bg-white bg-opacity-10 backdrop-blur-sm rounded-lg p-4">
                            <div id="fileListPreview" class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <i class="fas fa-check-circle text-green-400 mr-2"></i>
                                    <span id="newFileName" class="text-sm font-semibold truncate max-w-[200px]"></span>
                                </div>
                                <button type="button" onclick="clearNewFile()" class="text-red-300 hover:text-red-100 transition">
                                    <i class="fas fa-times-circle"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right: Form Area -->
                <div class="p-12 bg-gray-50">
                        <div class="mb-6">
                            <label for="pelaksana" class="block text-sm font-bold text-gray-700 mb-3">
                                <i class="fas fa-user-tie"></i> Pelaksana <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="pelaksana" id="pelaksana"
                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-[#3b82f6] focus:border-[#3b82f6] outline-none transition @error('pelaksana') border-red-500 @enderror"
                                placeholder="Masukkan nama pelaksana" value="{{ old('pelaksana', $document->pelaksana) }}" required>
                            @error('pelaksana')
                                <span class="text-red-500 text-sm mt-2 block"><i class="fas fa-exclamation-circle"></i> {{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <label for="nama_dokumen" class="block text-sm font-bold text-gray-700 mb-3">
                                <i class="fas fa-heading"></i> Judul Dokumen <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="nama_dokumen" id="nama_dokumen" 
                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-[#3b82f6] focus:border-[#3b82f6] outline-none transition @error('nama_dokumen') border-red-500 @enderror"
                                placeholder="Masukkan judul dokumen" value="{{ old('nama_dokumen', $document->nama_dokumen) }}" required>
                            @error('nama_dokumen')
                                <span class="text-red-500 text-sm mt-2 block"><i class="fas fa-exclamation-circle"></i> {{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <label for="keterangan" class="block text-sm font-bold text-gray-700 mb-3">
                                <i class="fas fa-align-left"></i> Deskripsi / Keterangan
                            </label>
                            <textarea name="keterangan" id="keterangan" rows="3"
                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-[#3b82f6] focus:border-[#3b82f6] outline-none transition @error('keterangan') border-red-500 @enderror"
                                placeholder="Masukkan deskripsi dokumen (opsional)">{{ old('keterangan', $document->keterangan) }}</textarea>
                            @error('keterangan')
                                <span class="text-red-500 text-sm mt-2 block"><i class="fas fa-exclamation-circle"></i> {{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <label for="kode_ro" class="block text-sm font-bold text-gray-700 mb-3">
                                <i class="fas fa-code"></i> Kode RO <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="kode_ro" id="kode_ro"
                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-[#3b82f6] focus:border-[#3b82f6] outline-none transition @error('kode_ro') border-red-500 @enderror"
                                placeholder="Masukkan Kode RO" value="{{ old('kode_ro', $document->kode_ro) }}" required>
                            @error('kode_ro')
                                <span class="text-red-500 text-sm mt-2 block"><i class="fas fa-exclamation-circle"></i> {{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-8">
                            <label for="jumlah_anggaran" class="block text-sm font-bold text-gray-700 mb-3">
                                <i class="fas fa-money-bill-wave"></i> Jumlah Anggaran <span class="text-red-500">*</span>
                            </label>
                            <input type="number" name="jumlah_anggaran" id="jumlah_anggaran"
                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-[#3b82f6] focus:border-[#3b82f6] outline-none transition @error('jumlah_anggaran') border-red-500 @enderror"
                                placeholder="0" min="0" step="0.01" value="{{ old('jumlah_anggaran', $document->jumlah_anggaran) }}" required>
                            @error('jumlah_anggaran')
                                <span class="text-red-500 text-sm mt-2 block"><i class="fas fa-exclamation-circle"></i> {{ $message }}</span>
                            @enderror
                        </div>

                        <div class="flex gap-3">
                            <button type="submit" class="flex-1 bg-gradient-to-r from-[#3b82f6] to-[#1e3a8a] hover:from-[#1e3a8a] hover:to-[#0f172a] text-white font-bold py-3 rounded-lg transition transform hover:scale-105">
                                <i class="fas fa-save"></i> Perbarui & Ajukan Ulang
                            </button>
                            <a href="{{ route('document.index') }}" class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-3 rounded-lg text-center transition">
                                <i class="fas fa-times"></i> Batal
                            </a>
                        </div>
                </div>
            </div>
        </form>
    </div>

    <script>
        const dropZone = document.getElementById('dropZone');
        const fileInput = document.getElementById('document');
        const filePreview = document.getElementById('filePreview');
        const newFileName = document.getElementById('newFileName');

        function handleDragOver(e) {
            e.preventDefault();
            e.stopPropagation();
            dropZone.classList.add('border-4', 'border-[#f59e0b]', 'bg-opacity-20');
        }

        function handleDragLeave(e) {
            e.preventDefault();
            e.stopPropagation();
            dropZone.classList.remove('border-4', 'border-[#f59e0b]', 'bg-opacity-20');
        }

        function handleDrop(e) {
            e.preventDefault();
            e.stopPropagation();
            dropZone.classList.remove('border-4', 'border-[#f59e0b]', 'bg-opacity-20');
            fileInput.files = e.dataTransfer.files;
            updateFileList();
        }

        function updateFileList() {
            if (fileInput.files.length > 0) {
                filePreview.classList.remove('hidden');
                newFileName.textContent = fileInput.files[0].name;
            } else {
                filePreview.classList.add('hidden');
            }
        }

        function clearNewFile() {
            fileInput.value = '';
            filePreview.classList.add('hidden');
        }
    </script>
@endsection
