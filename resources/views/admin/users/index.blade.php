@extends('layouts.app')

@section('page-title')
    Manajemen User
@endsection

@section('title')
    Manajemen Pengguna
@endsection

@section('content')
    <div class="space-y-6">
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-[#1e3a8a]">Manajemen Pengguna</h1>
                <p class="text-gray-600">Kelola pendaftaran akun, perizinan, dan keamanan pengguna.</p>
            </div>
            <div class="flex gap-2">
                <div class="bg-blue-100 text-blue-700 px-4 py-2 rounded-lg text-sm font-semibold border border-blue-200">
                    Total Pengguna: {{ $users->total() }}
                </div>
            </div>
        </div>

        @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-sm flex items-center">
                <i class="fas fa-check-circle mr-3"></i>
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded shadow-sm flex items-center">
                <i class="fas fa-exclamation-circle mr-3"></i>
                {{ session('error') }}
            </div>
        @endif

        <!-- Users Table -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gradient-to-r from-[#1e3a8a] to-[#3b82f6] text-white">
                            <th class="px-6 py-4 font-bold uppercase text-xs">Info Pengguna</th>
                            <th class="px-6 py-4 font-bold uppercase text-xs">Username / Email</th>
                            <th class="px-6 py-4 font-bold uppercase text-xs">Role</th>
                            <th class="px-6 py-4 font-bold uppercase text-xs text-center">Status</th>
                            <th class="px-6 py-4 font-bold uppercase text-xs">Tgl Daftar</th>
                            <th class="px-6 py-4 font-bold uppercase text-xs text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($users as $user)
                            <tr class="hover:bg-blue-50 transition">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-[#1e3a8a] font-bold border border-blue-200 mr-3">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                        <span class="font-semibold text-gray-800">{{ $user->name }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-col">
                                        <span class="text-sm text-gray-800 font-medium">@ {{ $user->username }}</span>
                                        <span class="text-xs text-gray-500 italic">{{ $user->email }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-block px-3 py-1 rounded-full text-xs font-bold {{ $user->role === 'admin' ? 'bg-purple-100 text-purple-700 border border-purple-200' : 'bg-blue-100 text-blue-700 border border-blue-200' }}">
                                        {{ strtoupper($user->role) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if ($user->status === 'active')
                                        <span class="inline-block px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-bold border border-green-200">
                                            <i class="fas fa-check-circle mr-1"></i> Aktif
                                        </span>
                                    @elseif($user->status === 'pending')
                                        <span class="inline-block px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs font-bold border border-yellow-200">
                                            <i class="fas fa-clock mr-1"></i> Menunggu
                                        </span>
                                    @else
                                        <span class="inline-block px-3 py-1 bg-red-100 text-red-700 rounded-full text-xs font-bold border border-red-200">
                                            <i class="fas fa-times-circle mr-1"></i> Ditolak
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    {{ $user->created_at->format('d/m/Y') }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-center gap-2">
                                        @if ($user->status === 'pending')
                                            <form action="{{ route('admin.users.approve', $user) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="bg-green-500 hover:bg-green-600 text-white p-2 rounded-lg text-xs transition shadow-sm" title="Setujui">
                                                    <i class="fas fa-user-check"></i> Setujui
                                                </button>
                                            </form>
                                            <form action="{{ route('admin.users.reject', $user) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white p-2 rounded-lg text-xs transition shadow-sm" title="Tolak">
                                                    <i class="fas fa-user-times"></i>
                                                </button>
                                            </form>
                                        @endif

                                        <button type="button" 
                                            onclick="openRoleModal('{{ $user->id }}', '{{ $user->name }}', '{{ $user->role }}')"
                                            class="bg-blue-500 hover:bg-blue-600 text-white p-2 rounded-lg text-xs transition shadow-sm" title="Ubah Role">
                                            <i class="fas fa-user-tag"></i>
                                        </button>

                                        <button type="button"
                                            onclick="openPasswordModal('{{ $user->id }}', '{{ $user->name }}')"
                                            class="bg-indigo-500 hover:bg-indigo-600 text-white p-2 rounded-lg text-xs transition shadow-sm" title="Ganti Password">
                                            <i class="fas fa-key"></i>
                                        </button>

                                        @if ($user->id !== auth()->id())
                                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline" onsubmit="return confirm('Hapus user ini permanent?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white p-2 rounded-lg text-xs transition shadow-sm" title="Hapus User">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            @if ($users->hasPages())
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-100">
                    {{ $users->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Role Modal -->
    <div id="roleModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden transform transition-all">
            <div class="bg-gradient-to-r from-blue-600 to-indigo-700 p-6 text-white">
                <div class="flex justify-between items-center">
                    <h3 class="text-xl font-bold"><i class="fas fa-user-tag mr-2"></i> Ubah Role User</h3>
                    <button onclick="closeRoleModal()" class="text-white hover:text-gray-200 transition text-2xl">&times;</button>
                </div>
                <p class="text-blue-100 text-sm mt-1" id="roleUserLabel"></p>
            </div>
            <form id="roleForm" method="POST" class="p-8 space-y-6">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Pilih Role Baru</label>
                    <select name="role" id="roleSelect" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition">
                        <option value="karyawan">Karyawan (Akses Terbatas)</option>
                        <option value="admin">Admin (Akses Penuh)</option>
                    </select>
                </div>
                <div class="flex gap-3">
                    <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-xl transition shadow-lg">Simpan Role</button>
                    <button type="button" onclick="closeRoleModal()" class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold py-3 rounded-xl transition">Batal</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Password Modal -->
    <div id="passwordModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden transform transition-all">
            <div class="bg-gradient-to-r from-indigo-600 to-purple-700 p-6 text-white">
                <div class="flex justify-between items-center">
                    <h3 class="text-xl font-bold"><i class="fas fa-key mr-2"></i> Ganti Password</h3>
                    <button onclick="closePasswordModal()" class="text-white hover:text-gray-200 transition text-2xl">&times;</button>
                </div>
                <p class="text-indigo-100 text-sm mt-1" id="passwordUserLabel"></p>
            </div>
            <form id="passwordForm" method="POST" class="p-8 space-y-5">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Password Baru</label>
                    <div class="relative">
                        <span class="absolute left-3 top-3.5 text-gray-400"><i class="fas fa-lock"></i></span>
                        <input type="password" name="password" required minlength="6"
                            class="w-full pl-10 pr-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition"
                            placeholder="Min. 6 karakter">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Konfirmasi Password</label>
                    <div class="relative">
                        <span class="absolute left-3 top-3.5 text-gray-400"><i class="fas fa-check-double"></i></span>
                        <input type="password" name="password_confirmation" required minlength="6"
                            class="w-full pl-10 pr-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition"
                            placeholder="Ulangi password">
                    </div>
                </div>
                <div class="flex gap-3 pt-2">
                    <button type="submit" class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 rounded-xl transition shadow-lg">Ganti Password</button>
                    <button type="button" onclick="closePasswordModal()" class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold py-3 rounded-xl transition">Batal</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openRoleModal(id, name, currentRole) {
            const modal = document.getElementById('roleModal');
            const form = document.getElementById('roleForm');
            const label = document.getElementById('roleUserLabel');
            const select = document.getElementById('roleSelect');
            
            label.textContent = "Mengubah role untuk: " + name;
            select.value = currentRole;
            form.action = `/admin/users/${id}/role`;
            
            modal.classList.remove('hidden');
            setTimeout(() => modal.children[0].classList.add('scale-100'), 10);
        }

        function closeRoleModal() {
            const modal = document.getElementById('roleModal');
            modal.classList.add('hidden');
        }

        function openPasswordModal(id, name) {
            const modal = document.getElementById('passwordModal');
            const form = document.getElementById('passwordForm');
            const label = document.getElementById('passwordUserLabel');
            
            label.textContent = "Ganti password untuk: " + name;
            form.action = `/admin/users/${id}/password`;
            
            modal.classList.remove('hidden');
        }

        function closePasswordModal() {
            const modal = document.getElementById('passwordModal');
            modal.classList.add('hidden');
        }

        // Close on escape
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                closeRoleModal();
                closePasswordModal();
            }
        });
    </script>
@endsection
