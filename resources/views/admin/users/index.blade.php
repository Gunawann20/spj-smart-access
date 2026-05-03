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
            <div class="bg-blue-100 text-blue-700 px-4 py-2 rounded-lg text-sm font-semibold border border-blue-200 shadow-sm">
                Total Pengguna: {{ $users->total() }}
            </div>
        </div>

        <!-- Users Table -->
        <div class="bg-white rounded-lg shadow overflow-hidden border border-gray-100">
            <div class="bg-[#1e3a8a] text-white p-4">
                <h5 class="text-lg font-bold m-0"><i class="fas fa-users"></i> Daftar Pengguna</h5>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-gray-50 text-[#1e3a8a]">
                        <tr>
                            <th class="px-6 py-3 font-bold uppercase text-xs">Info Pengguna</th>
                            <th class="px-6 py-3 font-bold uppercase text-xs">Akses</th>
                            <th class="px-6 py-3 font-bold uppercase text-xs text-center">Status</th>
                            <th class="px-6 py-3 font-bold uppercase text-xs">Tgl Daftar</th>
                            <th class="px-6 py-3 font-bold uppercase text-xs text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($users as $user)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 rounded-full bg-[#f59e0b] flex items-center justify-center text-white font-bold mr-3 shadow-sm">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                        <div class="flex flex-col">
                                            <span class="font-bold text-gray-800">{{ $user->name }}</span>
                                            <span class="text-xs text-gray-500 italic">{{ $user->email }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-col">
                                        <span class="text-sm font-bold text-[#1e3a8a]">@ {{ $user->username }}</span>
                                        <span class="text-[10px] uppercase font-bold tracking-widest {{ $user->role === 'admin' ? 'text-purple-600' : 'text-blue-600' }}">
                                            {{ $user->role }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if ($user->status === 'active')
                                        <span class="inline-block px-3 py-1 bg-green-500 text-white rounded text-[10px] font-bold uppercase shadow-sm">
                                            <i class="fas fa-check-circle mr-1"></i> Aktif
                                        </span>
                                    @elseif($user->status === 'pending')
                                        <span class="inline-block px-3 py-1 bg-[#f59e0b] text-white rounded text-[10px] font-bold uppercase shadow-sm">
                                            <i class="fas fa-clock mr-1"></i> Menunggu
                                        </span>
                                    @else
                                        <span class="inline-block px-3 py-1 bg-red-500 text-white rounded text-[10px] font-bold uppercase shadow-sm">
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
                                                <button type="submit" class="bg-green-500 hover:bg-green-600 text-white p-2 rounded text-xs transition shadow-sm" title="Setujui">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </form>
                                            <form action="{{ route('admin.users.reject', $user) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white p-2 rounded text-xs transition shadow-sm" title="Tolak">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </form>
                                        @endif

                                        <button type="button" 
                                            onclick="openRoleModal('{{ $user->id }}', '{{ $user->name }}', '{{ $user->role }}')"
                                            class="bg-blue-500 hover:bg-[#1e3a8a] text-white p-2 rounded text-xs transition shadow-sm" title="Ubah Role">
                                            <i class="fas fa-user-tag"></i>
                                        </button>

                                        <button type="button"
                                            onclick="openPasswordModal('{{ $user->id }}', '{{ $user->name }}')"
                                            class="bg-indigo-500 hover:bg-indigo-600 text-white p-2 rounded text-xs transition shadow-sm" title="Ganti Password">
                                            <i class="fas fa-key"></i>
                                        </button>

                                        @if ($user->id !== auth()->id())
                                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline" onsubmit="return confirm('Hapus user ini permanent?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white p-2 rounded text-xs transition shadow-sm" title="Hapus User">
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
        <div class="bg-white rounded-lg shadow-xl w-full max-w-md overflow-hidden">
            <div class="bg-[#1e3a8a] p-4 text-white">
                <div class="flex justify-between items-center">
                    <h5 class="text-lg font-bold m-0"><i class="fas fa-user-tag mr-2"></i> Ubah Role User</h5>
                    <button onclick="closeRoleModal()" class="text-white hover:text-gray-200 transition text-2xl">&times;</button>
                </div>
            </div>
            <form id="roleForm" method="POST" class="p-6 space-y-4">
                @csrf
                @method('PUT')
                <p class="text-sm text-gray-600 font-semibold" id="roleUserLabel"></p>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Pilih Role Baru</label>
                    <select name="role" id="roleSelect" class="w-full px-4 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-[#3b82f6] outline-none">
                        <option value="karyawan">Karyawan</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="flex-1 bg-[#3b82f6] hover:bg-[#1e3a8a] text-white font-bold py-2 rounded transition">Simpan</button>
                    <button type="button" onclick="closeRoleModal()" class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold py-2 rounded transition">Batal</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Password Modal -->
    <div id="passwordModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-md overflow-hidden">
            <div class="bg-[#1e3a8a] p-4 text-white">
                <div class="flex justify-between items-center">
                    <h5 class="text-lg font-bold m-0"><i class="fas fa-key mr-2"></i> Ganti Password</h5>
                    <button onclick="closePasswordModal()" class="text-white hover:text-gray-200 transition text-2xl">&times;</button>
                </div>
            </div>
            <form id="passwordForm" method="POST" class="p-6 space-y-4">
                @csrf
                @method('PUT')
                <p class="text-sm text-gray-600 font-semibold" id="passwordUserLabel"></p>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Password Baru</label>
                    <input type="password" name="password" required minlength="6"
                        class="w-full px-4 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-[#f59e0b] outline-none"
                        placeholder="Min. 6 karakter">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" required minlength="6"
                        class="w-full px-4 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-[#f59e0b] outline-none"
                        placeholder="Ulangi password">
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 rounded transition">Update</button>
                    <button type="button" onclick="closePasswordModal()" class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold py-2 rounded transition">Batal</button>
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
        }

        function closeRoleModal() {
            document.getElementById('roleModal').classList.add('hidden');
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
            document.getElementById('passwordModal').classList.add('hidden');
        }
    </script>
@endsection
