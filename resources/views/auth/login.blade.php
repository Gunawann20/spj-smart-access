<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Management Dokumen</title>
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
            <p class="text-sm font-semibold opacity-90">Direktorat Bina Akses Pelayanan KB</p>
            <p class="text-xs opacity-85 mt-3">Sistem Manajemen Dokumen</p>
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

            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-3 mb-4 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('login.post') }}" method="POST">
                @csrf

                <div class="mb-5">
                    <label for="username" class="block text-[#1e3a8a] font-semibold mb-2 text-sm">
                        <i class="fas fa-user"></i> Username
                    </label>
                    <input 
                        type="text" 
                        class="w-full border-2 border-gray-300 rounded px-4 py-3 text-sm focus:outline-none focus:border-[#3b82f6] transition @error('username') border-red-500 @enderror"
                        id="username" 
                        name="username" 
                        value="{{ old('username') }}"
                        placeholder="Masukkan username Anda"
                        required
                    >
                </div>

                <div class="mb-6">
                    <label for="password" class="block text-[#1e3a8a] font-semibold mb-2 text-sm">
                        <i class="fas fa-lock"></i> Password
                    </label>
                    <input 
                        type="password" 
                        class="w-full border-2 border-gray-300 rounded px-4 py-3 text-sm focus:outline-none focus:border-[#3b82f6] transition @error('password') border-red-500 @enderror"
                        id="password" 
                        name="password"
                        placeholder="Masukkan password Anda"
                        required
                    >
                </div>

                <button type="submit" class="w-full bg-gradient-to-r from-[#3b82f6] to-[#1e3a8a] hover:shadow-lg text-white py-3 rounded font-semibold transition transform hover:-translate-y-0.5">
                    <i class="fas fa-sign-in-alt"></i> Login
                </button>
                
                <div class="mt-6 text-center">
                    <p class="text-sm text-gray-600">
                        Belum punya akun? 
                        <a href="{{ route('register') }}" class="text-[#3b82f6] font-semibold hover:underline transition">Daftar di sini</a>
                    </p>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
