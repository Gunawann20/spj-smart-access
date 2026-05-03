<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        if (Auth::attempt(['username' => $request->username, 'password' => $request->password])) {
            if (Auth::user()->status !== 'active') {
                $status = Auth::user()->status;
                Auth::logout();
                $message = $status === 'pending' 
                    ? 'Akun Anda sedang menunggu persetujuan admin.' 
                    : 'Akun Anda telah ditolak. Silakan hubungi admin.';
                return back()->withErrors(['username' => $message]);
            }
            $request->session()->regenerate();
            return redirect()->route('dashboard');
        }

        return back()->withErrors(['username' => 'Username atau password salah']);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    public function register()
    {
        return view('auth.register');
    }

    public function storeRegister(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|unique:users|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'karyawan', // Default role set to karyawan
            'status' => 'pending', // Default status set to pending
        ]);

        return redirect()->route('login')->with('success', 'Pendaftaran berhasil! Akun Anda sedang menunggu persetujuan admin.');
    }
}
