<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Management Dokumen')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    @stack('styles')
</head>
<body class="bg-gray-100 text-gray-800">
    <!-- Sidebar -->
    @auth
        <div class="fixed left-0 top-0 w-72 h-screen bg-gradient-to-b from-[#1e3a8a] to-[#0f172a] p-0 overflow-y-auto shadow-2xl z-50 lg:z-auto" id="sidebar">
            <div class="p-6 text-center border-b-2 border-[#f59e0b] mb-8 bg-gradient-to-b from-[#2d4fa0] to-[#1e3a8a]">
                <!-- Logo -->
                <div class="mb-4 flex justify-center">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo BKKBN" class="h-14 w-auto drop-shadow-lg">
                </div>
                <!-- Institusi Info -->
                <p class="text-[#f59e0b] text-xs m-0 leading-tight font-semibold">Direktorat Bina Akses Pelayanan KB</p>
                <p class="text-gray-300 text-xs m-0 mt-3 leading-tight">Sistem Manajemen</p>
                <p class="text-gray-300 text-xs m-0 leading-tight">Dokumen</p>
            </div>

            <ul class="list-none">
                <li>
                    <a href="{{ route('dashboard') }}" class="block px-6 py-3 text-white transition-all duration-300 border-l-4 border-transparent hover:bg-opacity-30 hover:bg-[#f59e0b] hover:border-l-[#f59e0b] hover:pl-8 @if(request()->routeIs('dashboard')) bg-opacity-10 bg-[#f59e0b] border-l-[#f59e0b] text-[#f59e0b] pl-8 @endif">
                        <i class="fas fa-chart-pie w-5 mr-3"></i>Dashboard
                    </a>
                </li>
                {{-- <li>
                    <a href="{{ route('agenda.index') }}" class="block px-6 py-3 text-white transition-all duration-300 border-l-4 border-transparent hover:bg-opacity-30 hover:bg-[#f59e0b] hover:border-l-[#f59e0b] hover:pl-8 @if(request()->routeIs('agenda.*')) bg-opacity-10 bg-[#f59e0b] border-l-[#f59e0b] text-[#f59e0b] pl-8 @endif">
                        <i class="fas fa-calendar-alt w-5 mr-3"></i>Agenda
                    </a>
                </li> --}}
                <li>
                    <a href="{{ route('document.index') }}" class="block px-6 py-3 text-white transition-all duration-300 border-l-4 border-transparent hover:bg-opacity-30 hover:bg-[#f59e0b] hover:border-l-[#f59e0b] hover:pl-8 @if(request()->routeIs('document.*')) bg-opacity-10 bg-[#f59e0b] border-l-[#f59e0b] text-[#f59e0b] pl-8 @endif">
                        <i class="fas fa-file-upload w-5 mr-3"></i>Dokumen
                    </a>
                </li>
                @if (auth()->user()->role === 'karyawan')
                    <li>
                        <a href="{{ route('rab.index') }}" class="block px-6 py-3 text-white transition-all duration-300 border-l-4 border-transparent hover:bg-opacity-30 hover:bg-[#f59e0b] hover:border-l-[#f59e0b] hover:pl-8 @if(request()->routeIs('rab.*')) bg-opacity-10 bg-[#f59e0b] border-l-[#f59e0b] text-[#f59e0b] pl-8 @endif">
                            <i class="fas fa-file-upload w-5 mr-3"></i>RAB
                        </a>
                    </li>
                @endif

                @if(auth()->user()->role === 'admin')
                    <hr class="border-opacity-30 border-[#f59e0b] my-5">
                    <li>
                        <a href="{{ route('admin.pending') }}" class="block px-6 py-3 text-white transition-all duration-300 border-l-4 border-transparent hover:bg-opacity-30 hover:bg-[#f59e0b] hover:border-l-[#f59e0b] hover:pl-8 @if(request()->routeIs('admin.pending')) bg-opacity-10 bg-[#f59e0b] border-l-[#f59e0b] text-[#f59e0b] pl-8 @endif">
                            <i class="fas fa-tasks w-5 mr-3"></i>Approval Dokumen
                            <span class="inline-block ml-2 px-2 py-1 text-xs bg-[#f59e0b] text-white rounded" id="pending-count">0</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.history') }}" class="block px-6 py-3 text-white transition-all duration-300 border-l-4 border-transparent hover:bg-opacity-30 hover:bg-[#f59e0b] hover:border-l-[#f59e0b] hover:pl-8 @if(request()->routeIs('admin.history')) bg-opacity-10 bg-[#f59e0b] border-l-[#f59e0b] text-[#f59e0b] pl-8 @endif">
                            <i class="fas fa-history w-5 mr-3"></i>Riwayat Approval
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.users.index') }}" class="block px-6 py-3 text-white transition-all duration-300 border-l-4 border-transparent hover:bg-opacity-30 hover:bg-[#f59e0b] hover:border-l-[#f59e0b] hover:pl-8 @if(request()->routeIs('admin.users.*')) bg-opacity-10 bg-[#f59e0b] border-l-[#f59e0b] text-[#f59e0b] pl-8 @endif">
                            <i class="fas fa-users-cog w-5 mr-3"></i>Manajemen User
                        </a>
                    </li>
                @endif

                <hr class="border-opacity-30 border-[#f59e0b] my-5">
                <li>
                    <a href="#" onclick="document.getElementById('logout-form').submit()" class="block px-6 py-3 text-white transition-all duration-300 border-l-4 border-transparent hover:bg-opacity-30 hover:bg-[#f59e0b] hover:border-l-[#f59e0b] hover:pl-8">
                        <i class="fas fa-sign-out-alt w-5 mr-3"></i>Logout
                    </a>
                </li>
            </ul>

            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </div>

        <!-- Main Content -->
        <div class="ml-0 lg:ml-72 min-h-screen">
            <!-- Topbar -->
            <div class="bg-white p-4 lg:p-6 shadow-md border-b-4 border-[#f59e0b] flex justify-between items-center sticky top-0 z-40">
                <div class="flex items-center gap-4">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo BKKBN" class="h-12 w-auto drop-shadow">
                    <div>
                        <h2 class="text-[#1e3a8a] text-lg font-bold m-0">@yield('page-title', 'Dashboard')</h2>
                        <p class="text-gray-600 text-xs m-0">Direktorat Bina Akses Pelayanan KB</p>
                    </div>
                </div>
                <a href="{{ route('profile.index') }}" class="flex items-center gap-3 hover:bg-gray-100 p-2 rounded-lg transition">
                    <div class="w-10 h-10 rounded-full bg-[#f59e0b] flex items-center justify-center text-white font-bold text-lg shadow-sm">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <div class="hidden md:block text-left">
                        <p class="font-bold text-[#1e3a8a] leading-tight">{{ auth()->user()->name }}</p>
                        <p class="text-[10px] text-gray-500 uppercase tracking-wider font-semibold">{{ auth()->user()->role }}</p>
                    </div>
                </a>
            </div>

            <!-- Content -->
            <div class="p-6 lg:p-8">
                @if($errors->any())
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded" role="alert">
                        <strong>Error!</strong>
                        <ul class="mb-0 mt-2">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if(session('success'))
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded" role="alert">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded" role="alert">
                        {{ session('error') }}
                    </div>
                @endif

                @yield('content')
            </div>

            <!-- Footer -->
            <div class="bg-[#1e3a8a] text-white text-center p-5 mt-10">
                <p class="m-0">&copy; 2026 Kementerian Kependudukan Pembangunan Keluarga</p>
            </div>
        </div>
    @else
        @yield('content')
    @endauth

    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
    
    @if(auth()->check() && auth()->user()->role === 'admin')
        <script>
            // Update pending approval count
            async function updatePendingCount() {
                try {
                    const response = await fetch('{{ route("admin.pending") }}');
                    const html = await response.text();
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const count = doc.querySelector('h5')?.textContent?.match(/\d+/)?.[0] || '0';
                    const badge = document.getElementById('pending-count');
                    if (badge) {
                        badge.textContent = count;
                        badge.style.display = count > 0 ? 'inline-block' : 'none';
                    }
                } catch (error) {
                    console.error('Error updating pending count:', error);
                }
            }
            
            // Update count on page load and every 30 seconds
            updatePendingCount();
            setInterval(updatePendingCount, 30000);
        </script>
    @endif
    
    @stack('scripts')
</body>
</html>
