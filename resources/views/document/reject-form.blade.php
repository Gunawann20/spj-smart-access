@extends('layouts.app')

@section('page-title')
    Tolak Dokumen
@endsection

@section('title')
    Form Penolakan Dokumen
@endsection

@section('content')
    <div class="max-w-3xl mx-auto">
        <!-- Header Card -->
        <div class="bg-gradient-to-r from-red-600 to-red-700 text-white rounded-lg shadow-xl overflow-hidden mb-6">
            <div class="p-8">
                <div class="flex items-center mb-4">
                    <i class="fas fa-times-circle text-5xl mr-4 opacity-80"></i>
                    <div>
                        <h1 class="text-4xl font-bold mb-1">Penolakan Dokumen</h1>
                        <p class="text-red-100 text-lg">Berikan alasan penolakan untuk dokumen ini</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-3 gap-6 mb-8">
            <!-- Document Info Cards -->
            <div class="col-span-3 lg:col-span-2 space-y-6">
                <!-- Document Details Card -->
                <div class="bg-white rounded-lg shadow-lg overflow-hidden border-l-4 border-blue-500">
                    <div class="bg-blue-50 border-b border-blue-200 p-6">
                        <h2 class="text-xl font-bold text-blue-900 flex items-center">
                            <i class="fas fa-file-alt text-blue-600 mr-3 text-2xl"></i>
                            Informasi Dokumen
                        </h2>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="border-b border-gray-200 pb-4">
                            <p class="text-sm font-semibold text-gray-600 mb-1">Nama Dokumen</p>
                            <p class="text-lg font-bold text-gray-800">{{ $document->nama_dokumen }}</p>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="border-r border-gray-200 pr-4">
                                <p class="text-sm font-semibold text-gray-600 mb-1">Pelaksana</p>
                                <p class="text-base font-bold text-blue-600">{{ $document->pelaksana ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-600 mb-1">Kode RO</p>
                                <p class="text-base font-bold text-gray-800">{{ $document->kode_ro ?? '-' }}</p>
                            </div>
                        </div>
                        <div class="pt-4 border-t border-gray-200">
                            <p class="text-sm font-semibold text-gray-600 mb-1">Jumlah Anggaran</p>
                            <p class="text-base font-bold text-gray-800">{{ $document->jumlah_anggaran !== null ? number_format($document->jumlah_anggaran, 0, ',', '.') : '-' }}</p>
                        </div>
                        <div class="grid grid-cols-2 gap-4 pt-4 border-t border-gray-200">
                            <div>
                                <p class="text-sm font-semibold text-gray-600 mb-1">Upload oleh</p>
                                <p class="text-base font-bold text-gray-800">{{ $document->user->name }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-600 mb-1">Tanggal Upload</p>
                                <p class="text-base font-bold text-gray-800">{{ $document->created_at->format('d M Y H:i') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Rejection Reason Form Card -->
                <div class="bg-white rounded-lg shadow-lg overflow-hidden border-l-4 border-red-500">
                    <div class="bg-red-50 border-b border-red-200 p-6">
                        <h2 class="text-xl font-bold text-red-900 flex items-center">
                            <i class="fas fa-comment-dots text-red-600 mr-3 text-2xl"></i>
                            Alasan Penolakan
                        </h2>
                    </div>
                    <div class="p-8">
                        <form action="{{ route('document.reject', $document) }}" method="POST" id="rejectForm">
                            @csrf
                            @method('POST')

                            <div class="mb-6">
                                <label for="keterangan" class="block text-sm font-bold text-gray-700 mb-3">
                                    Jelaskan alasan penolakan <span class="text-red-600">*</span>
                                </label>
                                <textarea 
                                    name="keterangan" 
                                    id="keterangan"
                                    rows="7"
                                    class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 font-medium text-gray-700 placeholder-gray-500 transition-all @error('keterangan') border-red-500 bg-red-50 @enderror"
                                    placeholder="Contoh:
• Data tidak lengkap / kurang valid
• Format file tidak sesuai dengan template
• Informasi tidak jelas atau membingungkan
• Require revisi atau update data
• Alasan lainnya..."
                                    required>{{ old('keterangan') }}</textarea>
                                @error('keterangan')
                                    <span class="text-red-600 text-sm font-semibold mt-2 block"><i class="fas fa-exclamation-circle"></i> {{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex gap-4 pt-6 border-t-2 border-gray-200">
                                <a href="{{ route('document.show', $document) }}" class="flex-1 text-center bg-gray-600 hover:bg-gray-700 text-white font-bold py-4 rounded-lg transition transform hover:scale-105 shadow-md">
                                    <i class="fas fa-arrow-left mr-2"></i> Kembali
                                </a>
                                <button type="submit" class="flex-1 text-center bg-red-600 hover:bg-red-700 text-white font-bold py-4 rounded-lg transition transform hover:scale-105 shadow-md">
                                    <i class="fas fa-check-circle mr-2"></i> Tolak Dokumen
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Info Sidebar -->
            <div class="col-span-3 lg:col-span-1">
                <!-- Warning Alert -->
                <div class="bg-yellow-50 border-l-4 border-yellow-500 rounded-lg p-6 shadow-md mb-6">
                    <div class="flex items-start">
                        <i class="fas fa-exclamation-triangle text-yellow-600 text-2xl mr-4 flex-shrink-0 mt-1"></i>
                        <div>
                            <h3 class="font-bold text-yellow-900 mb-2">⚠️ Perhatian Penting</h3>
                            <p class="text-sm text-yellow-800 leading-relaxed">
                                Pengguna akan menerima notifikasi penolakan dan harus melakukan revisi atau upload ulang dokumen.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Help Card -->
                <div class="bg-blue-50 border-l-4 border-blue-500 rounded-lg p-6 shadow-md">
                    <h3 class="font-bold text-blue-900 mb-3 flex items-center">
                        <i class="fas fa-lightbulb text-blue-600 mr-2"></i> Tips Penolakan
                    </h3>
                    <ul class="text-sm text-blue-800 space-y-2">
                        <li class="flex items-start">
                            <i class="fas fa-check-circle text-blue-600 mr-2 mt-0.5 flex-shrink-0"></i>
                            <span>Jelaskan alasan dengan detail</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check-circle text-blue-600 mr-2 mt-0.5 flex-shrink-0"></i>
                            <span>Sertakan apa yang perlu diperbaiki</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check-circle text-blue-600 mr-2 mt-0.5 flex-shrink-0"></i>
                            <span>Berikan panduan jelas untuk revisi</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection
