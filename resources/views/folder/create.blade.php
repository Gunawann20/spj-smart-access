@extends('layouts.app')

@section('page-title')
    Upload Dokumen
@endsection

@section('title')
    Upload Dokumen Baru
@endsection

@section('content')
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-[#1e3a8a] mb-2"><i class="fas fa-cloud-upload-alt"></i> Upload Dokumen Baru</h1>
            <p class="text-gray-600">Kelola dan simpan dokumen Anda dengan mudah</p>
        </div>

        <!-- Main Upload Container -->
        <form action="{{ route('folder.store') }}" method="POST" enctype="multipart/form-data" id="uploadForm" class="bg-white rounded-lg shadow-lg overflow-hidden">
            @csrf
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-0">
                <!-- Left: Drag & Drop Area -->
                <div class="bg-gradient-to-br from-[#1e3a8a] to-[#0f172a] text-white p-12 flex flex-col justify-center items-center relative overflow-hidden"
                    id="dropZone"
                    ondragover="handleDragOver(event)"
                    ondragleave="handleDragLeave(event)"
                    ondrop="handleDrop(event)">
                    
                    <!-- Background decoration -->
                    <div class="absolute top-0 right-0 w-40 h-40 bg-[#3b82f6] opacity-10 rounded-full -mr-20 -mt-20"></div>
                    <div class="absolute bottom-0 left-0 w-32 h-32 bg-[#f59e0b] opacity-10 rounded-full -ml-16 -mb-16"></div>

                    <div class="relative z-10 text-center cursor-pointer" onclick="document.getElementById('documents').click()">
                        <div class="mb-6">
                            <i class="fas fa-cloud-upload-alt text-6xl mb-4 block opacity-80 hover:opacity-100 transition"></i>
                        </div>
                        <h3 class="text-2xl font-bold mb-2">Drag & Drop Dokumen Anda</h3>
                        <p class="text-blue-200 mb-6">atau klik untuk memilih file</p>
                        <button type="button" class="bg-[#f59e0b] hover:bg-[#d97706] text-white font-bold py-3 px-8 rounded-lg transition transform hover:scale-105">
                            <i class="fas fa-folder-open"></i> Pilih File
                        </button>
                        <p class="text-blue-200 text-sm mt-6 border-t border-blue-400 pt-6">
                            Format: PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX, ZIP<br>
                            Maksimal 50 MB per file
                        </p>
                    </div>

                    <input type="file" name="documents[]" id="documents" multiple class="hidden" 
                        accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.zip"
                        onchange="updateFileList()">

                    <!-- File Preview -->
                    <div id="filePreview" class="relative z-10 mt-8 w-full hidden">
                        <div class="bg-white bg-opacity-10 backdrop-blur-sm rounded-lg p-4 max-h-48 overflow-y-auto">
                            <div id="fileListPreview" class="space-y-2"></div>
                        </div>
                    </div>
                </div>

                <!-- Right: Form Area -->
                <div class="p-12 bg-gray-50">

                        <div class="mb-6">
                            <label for="jenis_dokumen" class="block text-sm font-bold text-gray-700 mb-3">
                                <i class="fas fa-list-check"></i> Jenis Dokumen <span class="text-red-500">*</span>
                            </label>
                            <select name="jenis_dokumen" id="jenis_dokumen" 
                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-[#3b82f6] focus:border-[#3b82f6] outline-none transition @error('jenis_dokumen') border-red-500 @enderror"
                                required>
                                <option value="">-- Pilih Jenis Dokumen --</option>
                                <option value="RKAKL" {{ old('jenis_dokumen') === 'RKAKL' ? 'selected' : '' }}>RKAKL (Rencana Kerja Anggaran Kinerja Lembaga)</option>
                                <option value="RAPK" {{ old('jenis_dokumen') === 'RAPK' ? 'selected' : '' }}>RAPK (Rencana Alokasi Program & Kegiatan)</option>
                                <option value="SPJ" {{ old('jenis_dokumen') === 'SPJ' ? 'selected' : '' }}>SPJ (Surat Pertanggungjawaban)</option>
                                <option value="LKJ" {{ old('jenis_dokumen') === 'LKJ' ? 'selected' : '' }}>LKJ (Laporan Kinerja Jabatan)</option>
                                <option value="LAKIP" {{ old('jenis_dokumen') === 'LAKIP' ? 'selected' : '' }}>LAKIP (Laporan Akuntabilitas Kinerja Instansi Pemerintah)</option>
                            </select>
                            @error('jenis_dokumen')
                                <span class="text-red-500 text-sm mt-2 block"><i class="fas fa-exclamation-circle"></i> {{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <label for="nama_folder" class="block text-sm font-bold text-gray-700 mb-3">
                                <i class="fas fa-heading"></i> Judul Dokumen <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="nama_folder" id="nama_folder" 
                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-[#3b82f6] focus:border-[#3b82f6] outline-none transition @error('nama_folder') border-red-500 @enderror"
                                placeholder="Masukkan judul dokumen" value="{{ old('nama_folder') }}" required>
                            @error('nama_folder')
                                <span class="text-red-500 text-sm mt-2 block"><i class="fas fa-exclamation-circle"></i> {{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <label for="deskripsi" class="block text-sm font-bold text-gray-700 mb-3">
                                <i class="fas fa-align-left"></i> Deskripsi / Keterangan
                            </label>
                            <textarea name="deskripsi" id="deskripsi" rows="3"
                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-[#3b82f6] focus:border-[#3b82f6] outline-none transition @error('deskripsi') border-red-500 @enderror"
                                placeholder="Masukkan deskripsi dokumen (opsional)">{{ old('deskripsi') }}</textarea>
                            @error('deskripsi')
                                <span class="text-red-500 text-sm mt-2 block"><i class="fas fa-exclamation-circle"></i> {{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-8">
                            <label for="tahun" class="block text-sm font-bold text-gray-700 mb-3">
                                <i class="fas fa-calendar-alt"></i> Tahun Anggaran <span class="text-red-500">*</span>
                            </label>
                            <input type="number" name="tahun" id="tahun" 
                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-[#3b82f6] focus:border-[#3b82f6] outline-none transition @error('tahun') border-red-500 @enderror"
                                placeholder="2026" min="2020" max="2099" value="{{ old('tahun', date('Y')) }}" required>
                            @error('tahun')
                                <span class="text-red-500 text-sm mt-2 block"><i class="fas fa-exclamation-circle"></i> {{ $message }}</span>
                            @enderror
                        </div>

                        <div class="flex gap-3">
                            <button type="submit" class="flex-1 bg-gradient-to-r from-[#3b82f6] to-[#1e3a8a] hover:from-[#1e3a8a] hover:to-[#0f172a] text-white font-bold py-3 rounded-lg transition transform hover:scale-105">
                                <i class="fas fa-upload"></i> Upload Dokumen
                            </button>
                            <a href="{{ route('folder.index') }}" class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-3 rounded-lg text-center transition">
                                <i class="fas fa-times"></i> Batal
                            </a>
                        </div>

                        @error('documents')
                            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mt-4 rounded">
                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                            </div>
                        @enderror

                        <!-- Username indicator -->
                        <div class="mt-6 pt-6 border-t-2 border-gray-300">
                            <p class="text-sm text-gray-600"><i class="fas fa-user-circle"></i> Pengguna: <strong>{{ auth()->user()->name }}</strong></p>
                        </div>
                </div>
            </div>
        </form>

        <!-- File List Section -->
        <div id="uploadedFiles" class="mt-8 hidden">
            <h3 class="text-xl font-bold text-[#1e3a8a] mb-4"><i class="fas fa-list"></i> File yang Dipilih</h3>
            <div id="fileListContainer" class="space-y-2"></div>
        </div>
    </div>

    <script>
        const dropZone = document.getElementById('dropZone');
        const fileInput = document.getElementById('documents');
        const filePreview = document.getElementById('filePreview');
        const fileListPreview = document.getElementById('fileListPreview');
        const uploadedFiles = document.getElementById('uploadedFiles');
        const fileListContainer = document.getElementById('fileListContainer');

        // Drag and drop handlers
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

        fileInput.addEventListener('change', updateFileList);

        function updateFileList() {
            const files = Array.from(fileInput.files);

            if (files.length > 0) {
                filePreview.classList.remove('hidden');
                uploadedFiles.classList.remove('hidden');
                fileListPreview.innerHTML = '';
                fileListContainer.innerHTML = '';

                files.forEach((file, index) => {
                    const icon = getFileIcon(file.name);
                    const size = (file.size / 1024).toFixed(2);

                    // Preview in drop zone
                    const previewItem = document.createElement('div');
                    previewItem.className = 'flex items-center justify-between bg-white bg-opacity-10 rounded px-3 py-2 text-sm';
                    previewItem.innerHTML = `
                        <span><i class="fas ${icon} mr-2"></i>${file.name}</span>
                        <button type="button" onclick="removeFile(${index})" class="text-red-300 hover:text-red-100 transition">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    `;
                    fileListPreview.appendChild(previewItem);

                    // File list in container
                    const item = document.createElement('div');
                    item.className = 'flex items-center justify-between bg-gradient-to-r from-blue-50 to-transparent border-l-4 border-[#3b82f6] rounded-lg px-4 py-3 hover:shadow-md transition';
                    item.innerHTML = `
                        <div class="flex items-center flex-1">
                            <i class="fas ${icon} text-lg text-[#3b82f6] mr-3"></i>
                            <div class="flex-1">
                                <p class="font-semibold text-gray-800">${file.name}</p>
                                <p class="text-xs text-gray-500">${size} KB</p>
                            </div>
                        </div>
                        <button type="button" onclick="removeFile(${index})" class="text-red-600 hover:text-red-800 hover:bg-red-100 px-3 py-1 rounded transition">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    `;
                    fileListContainer.appendChild(item);
                });
            } else {
                filePreview.classList.add('hidden');
                uploadedFiles.classList.add('hidden');
            }
        }

        function getFileIcon(fileName) {
            const ext = fileName.split('.').pop().toLowerCase();
            if (ext === 'pdf') return 'fa-file-pdf text-red-500';
            if (['doc', 'docx'].includes(ext)) return 'fa-file-word text-blue-500';
            if (['xls', 'xlsx'].includes(ext)) return 'fa-file-excel text-green-500';
            if (['ppt', 'pptx'].includes(ext)) return 'fa-file-powerpoint text-orange-500';
            if (ext === 'zip') return 'fa-file-archive text-purple-500';
            return 'fa-file text-gray-500';
        }

        function removeFile(index) {
            const dt = new DataTransfer();
            Array.from(fileInput.files).forEach((file, i) => {
                if (i !== index) dt.items.add(file);
            });
            fileInput.files = dt.files;
            updateFileList();
        }

        // Form validation
        document.getElementById('uploadForm').addEventListener('submit', function(e) {
            if (fileInput.files.length === 0) {
                e.preventDefault();
                // Show error in a styled alert
                const errorDiv = document.createElement('div');
                errorDiv.className = 'bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded mb-4';
                errorDiv.innerHTML = '<i class="fas fa-exclamation-circle"></i> <strong>Error!</strong> Silakan pilih minimal satu file untuk di-upload.';
                
                const form = document.getElementById('uploadForm');
                form.parentElement.insertBefore(errorDiv, form);
                
                setTimeout(() => errorDiv.remove(), 5000);
                return false;
            }
        });
    </script>
@endsection
