<?php

namespace App\Http\Controllers;

use App\Models\LevelModel;
use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login()
    {
        if (Auth::check()) { 
            return redirect('/');
        }
        return view('auth.login');
    }

    public function postlogin(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $credentials = $request->only('username', 'password');

            if (Auth::attempt($credentials)) {
                return response()->json([
                    'status' => true,
                    'message' => 'Login Berhasil',
                    'redirect' => url('/')
                ]);
            }

            return response()->json([
                'status' => false,
                'message' => 'Login Gagal'
            ]);
        }

        return redirect('login');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('login');
    }

    public function register()
    {
        if(Auth::check()) {
            return redirect('/');
        }
        
        // Ambil data level untuk dropdown
        $levels = LevelModel::all();
        return view('auth.register', compact('levels'));
    }

    public function postRegister(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|min:4|max:20|unique:m_user,username',
            'nama' => 'required|max:100',
            'password' => 'required|min:6|confirmed',
            'level_id' => 'required|exists:m_level,level_id'
        ]);

        // Hash password
        $validated['password'] = Hash::make($validated['password']);

        // Buat user baru
        $user = UserModel::create($validated);

        // Login user setelah registrasi (opsional)
        Auth::login($user);

        return redirect('/')->with('success', 'Registrasi berhasil!');
    }
}
