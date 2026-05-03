<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Management Dokumen</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gradient-to-br from-[#1e3a8a] to-[#0f172a] min-h-screen flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-2xl overflow-hidden max-w-md w-full">
        <!-- Header -->
        <div class="bg-gradient-to-r from-[#1e3a8a] to-[#3b82f6] text-white p-10 text-center">
            <div class="mb-4 flex justify-center">
                <img src="{{ asset('images/logo.png') }}" alt="Logo BKKBN" class="h-16 w-auto filter brightness-0 invert drop-shadow-lg">
            </div>
            <p class="text-sm font-semibold opacity-90">Kementerian Kependudukan</p>
            <p class="text-sm font-semibold opacity-90">Pembangunan Keluarga</p>
            <p class="text-xs opacity-85 mt-3">Daftar Akun Baru</p>
        </div>

        <!-- Form -->
        <div class="p-8">
            @if($errors->any())
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-3 mb-4 rounded">
                    @foreach($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('register.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="name" class="block text-[#1e3a8a] font-semibold mb-1 text-sm">
                        <i class="fas fa-user"></i> Nama Lengkap
                    </label>
                    <input 
                        type="text" 
                        class="w-full border-2 border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:border-[#3b82f6] transition @error('name') border-red-500 @enderror"
                        id="name" 
                        name="name" 
                        value="{{ old('name') }}"
                        placeholder="Nama lengkap"
                        required
                    >
                </div>

                <div class="mb-3">
                    <label for="username" class="block text-[#1e3a8a] font-semibold mb-1 text-sm">
                        <i class="fas fa-at"></i> Username
                    </label>
                    <input 
                        type="text" 
                        class="w-full border-2 border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:border-[#3b82f6] transition @error('username') border-red-500 @enderror"
                        id="username" 
                        name="username" 
                        value="{{ old('username') }}"
                        placeholder="Username unik"
                        required
                    >
                </div>

                <div class="mb-3">
                    <label for="email" class="block text-[#1e3a8a] font-semibold mb-1 text-sm">
                        <i class="fas fa-envelope"></i> Email
                    </label>
                    <input 
                        type="email" 
                        class="w-full border-2 border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:border-[#3b82f6] transition @error('email') border-red-500 @enderror"
                        id="email" 
                        name="email" 
                        value="{{ old('email') }}"
                        placeholder="Email Anda"
                        required
                    >
                </div>

                <div class="mb-3">
                    <label for="password" class="block text-[#1e3a8a] font-semibold mb-1 text-sm">
                        <i class="fas fa-lock"></i> Password
                    </label>
                    <input 
                        type="password" 
                        class="w-full border-2 border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:border-[#3b82f6] transition @error('password') border-red-500 @enderror"
                        id="password" 
                        name="password"
                        placeholder="Password minimal 6 karakter"
                        required
                    >
                </div>

                <div class="mb-6">
                    <label for="password_confirmation" class="block text-[#1e3a8a] font-semibold mb-1 text-sm">
                        <i class="fas fa-lock"></i> Konfirmasi Password
                    </label>
                    <input 
                        type="password" 
                        class="w-full border-2 border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:border-[#3b82f6] transition"
                        id="password_confirmation" 
                        name="password_confirmation"
                        placeholder="Ulangi password Anda"
                        required
                    >
                </div>

                <button type="submit" class="w-full bg-gradient-to-r from-[#3b82f6] to-[#1e3a8a] hover:shadow-lg text-white py-2 rounded font-semibold transition transform hover:-translate-y-0.5 text-sm mb-2">
                    <i class="fas fa-user-plus"></i> Daftar
                </button>
            </form>

            <div class="border-t pt-3 mt-3">
                <p class="text-gray-600 text-sm mb-2">Sudah punya akun?</p>
                <a href="{{ route('login') }}" class="block text-center px-4 py-2 border-2 border-[#f59e0b] text-[#f59e0b] hover:bg-[#f59e0b] hover:text-[#1e3a8a] rounded font-semibold transition text-sm">
                    <i class="fas fa-sign-in-alt"></i> Login di sini
                </a>
            </div>
        </div>
    </div>
</body>
</html>
