<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $users = User::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    public function approve(User $user)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $user->update(['status' => 'active']);
        return back()->with('success', "User {$user->name} berhasil disetujui.");
    }

    public function reject(User $user)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $user->update(['status' => 'rejected']);
        return back()->with('success', "User {$user->name} berhasil ditolak.");
    }

    public function updateRole(Request $request, User $user)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $request->validate([
            'role' => 'required|in:admin,karyawan',
        ]);

        $user->update(['role' => $request->role]);
        return back()->with('success', "Role user {$user->name} berhasil diubah.");
    }

    public function changePassword(Request $request, User $user)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $request->validate([
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user->update([
            'password' => Hash::make($request->password)
        ]);

        return back()->with('success', "Password user {$user->name} berhasil diubah.");
    }

    public function destroy(User $user)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        if ($user->id === auth()->id()) {
            return back()->with('error', "Anda tidak dapat menghapus akun Anda sendiri.");
        }

        $user->delete();
        return back()->with('success', "User {$user->name} berhasil dihapus.");
    }
}
