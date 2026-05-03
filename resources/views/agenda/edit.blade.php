@extends('layouts.app')

@section('page-title')
    Edit Agenda
@endsection

@section('title')
    Edit Agenda: {{ $agenda->judul }}
@endsection

@section('content')
    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-lg shadow-lg p-8">
            <h2 class="text-3xl font-bold text-[#1e3a8a] mb-6"><i class="fas fa-edit"></i> Edit Agenda</h2>

            <form action="{{ route('agenda.update', $agenda) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-6">
                    <label for="judul" class="block text-sm font-bold text-gray-700 mb-3">
                        <i class="fas fa-heading"></i> Judul Agenda <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="judul" id="judul" 
                        class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-[#3b82f6] focus:border-[#3b82f6] outline-none transition @error('judul') border-red-500 @enderror"
                        placeholder="Contoh: Rapat Ramadhan 2026" value="{{ old('judul', $agenda->judul) }}" required>
                    @error('judul')
                        <span class="text-red-500 text-sm mt-2 block"><i class="fas fa-exclamation-circle"></i> {{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="deskripsi" class="block text-sm font-bold text-gray-700 mb-3">
                        <i class="fas fa-align-left"></i> Deskripsi
                    </label>
                    <textarea name="deskripsi" id="deskripsi" rows="4"
                        class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-[#3b82f6] focus:border-[#3b82f6] outline-none transition @error('deskripsi') border-red-500 @enderror"
                        placeholder="Masukkan deskripsi agenda (opsional)">{{ old('deskripsi', $agenda->deskripsi) }}</textarea>
                    @error('deskripsi')
                        <span class="text-red-500 text-sm mt-2 block"><i class="fas fa-exclamation-circle"></i> {{ $message }}</span>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="tanggal_mulai" class="block text-sm font-bold text-gray-700 mb-3">
                            <i class="fas fa-calendar"></i> Tanggal Mulai <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="tanggal_mulai" id="tanggal_mulai" 
                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-[#3b82f6] focus:border-[#3b82f6] outline-none transition @error('tanggal_mulai') border-red-500 @enderror"
                            value="{{ old('tanggal_mulai', $agenda->tanggal_mulai->format('Y-m-d')) }}" required>
                        @error('tanggal_mulai')
                            <span class="text-red-500 text-sm mt-2 block"><i class="fas fa-exclamation-circle"></i> {{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label for="tanggal_akhir" class="block text-sm font-bold text-gray-700 mb-3">
                            <i class="fas fa-calendar"></i> Tanggal Akhir
                        </label>
                        <input type="date" name="tanggal_akhir" id="tanggal_akhir" 
                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-[#3b82f6] focus:border-[#3b82f6] outline-none transition @error('tanggal_akhir') border-red-500 @enderror"
                            value="{{ old('tanggal_akhir', $agenda->tanggal_akhir?->format('Y-m-d') ?? '') }}">
                        @error('tanggal_akhir')
                            <span class="text-red-500 text-sm mt-2 block"><i class="fas fa-exclamation-circle"></i> {{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="mb-8">
                    <label for="status" class="block text-sm font-bold text-gray-700 mb-3">
                        <i class="fas fa-toggle-on"></i> Status <span class="text-red-500">*</span>
                    </label>
                    <select name="status" id="status" 
                        class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-[#3b82f6] focus:border-[#3b82f6] outline-none transition @error('status') border-red-500 @enderror"
                        required>
                        <option value="active" {{ old('status', $agenda->status) === 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="inactive" {{ old('status', $agenda->status) === 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                        <option value="closed" {{ old('status', $agenda->status) === 'closed' ? 'selected' : '' }}>Ditutup</option>
                    </select>
                    @error('status')
                        <span class="text-red-500 text-sm mt-2 block"><i class="fas fa-exclamation-circle"></i> {{ $message }}</span>
                    @enderror
                </div>

                <div class="flex gap-3">
                    <button type="submit" class="flex-1 bg-gradient-to-r from-[#3b82f6] to-[#1e3a8a] hover:from-[#1e3a8a] hover:to-[#0f172a] text-white font-bold py-3 rounded-lg transition transform hover:scale-105">
                        <i class="fas fa-save"></i> Simpan Perubahan
                    </button>
                    <a href="{{ route('agenda.show', $agenda) }}" class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-3 rounded-lg text-center transition">
                        <i class="fas fa-times"></i> Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
