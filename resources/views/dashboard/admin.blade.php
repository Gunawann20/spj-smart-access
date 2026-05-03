@extends('layouts.app')

@section('page-title')
    Dashboard Admin
@endsection

@section('title')
    Dashboard Admin - Management Dokumen
@endsection

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 mb-8">
        <div class="bg-white rounded-lg p-6 shadow hover:shadow-lg transition border-t-4 border-[#3b82f6]">
            <i class="fas fa-file text-2xl text-[#3b82f6] mb-3 block"></i>
            <div class="text-3xl font-bold text-[#1e3a8a] mb-2">{{ $totalDocuments ?? 0 }}</div>
            <p class="text-gray-600 text-sm">Total Dokumen</p>
        </div>

        <div class="bg-white rounded-lg p-6 shadow hover:shadow-lg transition border-t-4 border-[#f59e0b]">
            <i class="fas fa-hourglass-half text-2xl text-[#f59e0b] mb-3 block"></i>
            <div class="text-3xl font-bold text-[#1e3a8a] mb-2">{{ $pendingDocuments ?? 0 }}</div>
            <p class="text-gray-600 text-sm">Menunggu Approval</p>
        </div>

        <div class="bg-white rounded-lg p-6 shadow hover:shadow-lg transition border-t-4 border-green-500">
            <i class="fas fa-check-circle text-2xl text-green-500 mb-3 block"></i>
            <div class="text-3xl font-bold text-[#1e3a8a] mb-2">{{ $approvedDocuments ?? 0 }}</div>
            <p class="text-gray-600 text-sm">Disetujui</p>
        </div>

        <div class="bg-white rounded-lg p-6 shadow hover:shadow-lg transition border-t-4 border-red-500">
            <i class="fas fa-times-circle text-2xl text-red-500 mb-3 block"></i>
            <div class="text-3xl font-bold text-[#1e3a8a] mb-2">{{ $rejectedDocuments ?? 0 }}</div>
            <p class="text-gray-600 text-sm">Ditolak</p>
        </div>

        <div class="bg-white rounded-lg p-6 shadow hover:shadow-lg transition border-t-4 border-[#1e3a8a]">
            <i class="fas fa-users text-2xl text-[#1e3a8a] mb-3 block"></i>
            <div class="text-3xl font-bold text-[#1e3a8a] mb-2">{{ $totalUsers }}</div>
            <p class="text-gray-600 text-sm">Total Karyawan</p>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow">
        <div class="bg-[#1e3a8a] text-white p-4 rounded-t-lg">
            <h5 class="text-lg font-bold m-0"><i class="fas fa-tasks"></i> Aksi Cepat</h5>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <a href="{{ route('admin.pending') }}" class="bg-[#3b82f6] hover:bg-[#1e3a8a] text-white py-3 px-4 rounded font-semibold transition text-center">
                    <i class="fas fa-check-square"></i> Approval Dokumen Menunggu
                </a>
                <a href="{{ route('admin.history') }}" class="bg-cyan-500 hover:bg-cyan-600 text-white py-3 px-4 rounded font-semibold transition text-center">
                    <i class="fas fa-history"></i> Riwayat Approval
                </a>
                <a href="{{ route('document.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white py-3 px-4 rounded font-semibold transition text-center">
                    <i class="fas fa-file-upload"></i> Lihat Semua Dokumen
                </a>
            </div>
        </div>
    </div>
@endsection
