@extends('layouts.app')

@section('page-title')
    Profil Saya
@endsection

@section('title')
    Profil Pengguna
@endsection

@section('content')
    <div class="max-w-6xl mx-auto">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-[#1e3a8a] mb-2"><i class="fas fa-user-circle"></i> Pengaturan Profil</h1>
            <p class="text-gray-600">Kelola informasi pribadi dan keamanan akun Anda</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left: Profile Summary -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow overflow-hidden border-t-4 border-[#3b82f6]">
                    <div class="bg-[#1e3a8a] p-8 text-center">
                        <div class="w-20 h-20 rounded-full bg-[#f59e0b] flex items-center justify-center text-white text-3xl font-bold mx-auto mb-4 border-2 border-white">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        <h3 class="text-white text-lg font-bold">{{ $user->name }}</h3>
                        <p class="text-blue-200 text-xs">{{ strtoupper($user->role) }}</p>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="flex items-center text-gray-700">
                            <i class="fas fa-at w-8 text-[#3b82f6]"></i>
                            <div class="flex flex-col">
                                <span class="text-[10px] text-gray-400 uppercase font-bold tracking-wider">Username</span>
                                <span class="text-sm font-semibold text-gray-700">@ {{ $user->username }}</span>
                            </div>
                        </div>
                        <div class="flex items-center text-gray-700">
                            <i class="fas fa-envelope w-8 text-[#3b82f6]"></i>
                            <div class="flex flex-col">
                                <span class="text-[10px] text-gray-400 uppercase font-bold tracking-wider">Email</span>
                                <span class="text-sm font-semibold text-gray-700">{{ $user->email }}</span>
                            </div>
                        </div>
                        <div class="flex items-center text-gray-700">
                            <i class="fas fa-calendar-check w-8 text-[#3b82f6]"></i>
                            <div class="flex flex-col">
                                <span class="text-[10px] text-gray-400 uppercase font-bold tracking-wider">Bergabung</span>
                                <span class="text-sm font-semibold text-gray-700">{{ $user->created_at->format('d M Y') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right: Edit Forms -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Profile Information -->
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="bg-[#1e3a8a] text-white p-4">
                        <h5 class="text-lg font-bold m-0"><i class="fas fa-id-card"></i> Informasi Pribadi</h5>
                    </div>
                    <form action="{{ route('profile.update') }}" method="POST" class="p-6 space-y-4">
                        @csrf
                        @method('PUT')
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Nama Lengkap</label>
                                <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                                    class="w-full px-4 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-[#3b82f6] outline-none @error('name') border-red-500 @enderror">
                                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Username</label>
                                <input type="text" name="username" value="{{ old('username', $user->username) }}" required
                                    class="w-full px-4 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-[#3b82f6] outline-none @error('username') border-red-500 @enderror">
                                @error('username') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Alamat Email</label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                                class="w-full px-4 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-[#3b82f6] outline-none @error('email') border-red-500 @enderror">
                            @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div class="flex justify-end">
                            <button type="submit" class="bg-[#3b82f6] hover:bg-[#1e3a8a] text-white font-bold py-2 px-6 rounded transition">
                                <i class="fas fa-save"></i> Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Security: Change Password -->
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="bg-[#1e3a8a] text-white p-4">
                        <h5 class="text-lg font-bold m-0"><i class="fas fa-shield-alt"></i> Keamanan & Password</h5>
                    </div>
                    <form action="{{ route('profile.password') }}" method="POST" class="p-6 space-y-4">
                        @csrf
                        @method('PUT')
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Password Saat Ini</label>
                            <input type="password" name="current_password" required
                                class="w-full px-4 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-[#f59e0b] outline-none @error('current_password') border-red-500 @enderror"
                                placeholder="Konfirmasi password lama">
                            @error('current_password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Password Baru</label>
                                <input type="password" name="password" required
                                    class="w-full px-4 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-[#f59e0b] outline-none @error('password') border-red-500 @enderror"
                                    placeholder="Min. 6 karakter">
                                @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Konfirmasi Password</label>
                                <input type="password" name="password_confirmation" required
                                    class="w-full px-4 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-[#f59e0b] outline-none"
                                    placeholder="Ulangi password baru">
                            </div>
                        </div>
                        <div class="flex justify-end">
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-6 rounded transition">
                                <i class="fas fa-key"></i> Update Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
